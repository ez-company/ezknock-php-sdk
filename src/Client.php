<?php

namespace EZKnock;

use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Authentication\Bearer;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

class Client {

	const SDK_VERSION = '1.0.0';

	const ENV_SANDBOX = 'sandbox';
	const ENV_PRODUCTION = 'production';

	private $env;
	private $http;
	private $request_factory;
	private $stream_factory;
	private $uri_factory;
	private $rate_limit;
	private $auth_token;

	public $buyers;
    public $whoknocked;
    public $options;

	public function __construct(string $auth_token, string $env = self::ENV_SANDBOX) {
		$this->env = $env;
		$this->auth_token = $auth_token;

		$this->buyers = new Buyers($this);
        $this->whoknocked = new Whoknocked($this);
        $this->options =new Options($this);

		$this->http = $this->getDefaultHttpClient();
        $this->request_factory = Psr17FactoryDiscovery::findRequestFactory();
        $this->stream_factory = Psr17FactoryDiscovery::findStreamFactory();
        $this->uri_factory = Psr17FactoryDiscovery::findUriFactory();
	}

    /**
     * Sets the HTTP client.
     *
     * @param HttpClient $client
     */
    public function setHttpClient(HttpClient $client) {
        $this->http = $client;
    }

    /**
     * Sets the request factory.
     *
     * @param RequestFactoryInterface $factory
     */
    public function setRequestFactory(RequestFactoryInterface $factory) {
        $this->request_factory = $factory;
    }

    /**
     * Sets the stream factory.
     *
     * @param StreamFactoryInterface $factory
     */
    public function setStreamFactory(StreamFactoryInterface $factory) {
        $this->stream_factory = $factory;
    }

    /**
     * Sets the URI factory.
     *
     * @param UriFactoryInterface $factory
     */
    public function setUriFactory(UriFactoryInterface $factory) {
        $this->uri_factory = $factory;
    }

	/**
	 * Build the EZ Knock API Url
	 *
	 * @param  string $endpoint
	 * @return string
	 */
	private function getUri($endpoint) {
		switch ($this->env) {
			case self::ENV_SANDBOX:
				$base_uri = 'https://test.ezknockmarketplace.com/api/v1';
				break;

            case self::ENV_PRODUCTION:
                $base_uri = 'https://ezknockmarketplace.com/api/v1';
                break;

			default:
				throw new \Exception('Environment is not supported');
				break;
		}

		return $base_uri.$endpoint;
	}

	/**
     * @return array
     */
    private function getRequestHeaders($content_type = 'application/json') {
        return [
            'Content-Type' => $content_type,
            'User-Agent' => 'EZKnock-PHP/' . self::SDK_VERSION,
        ];
    }

    /**
     * Returns authentication parameters
     *
     * @return Authentication
     */
    private function getAuth() {
        if (!empty($this->auth_token)) {
            return new Bearer($this->auth_token);
        }

        return null;
    }

    /**
     * Authenticates a request object
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    private function authenticateRequest(RequestInterface $request) {
        $auth = $this->getAuth();
        return $auth ? $auth->authenticate($request) : $request;
    }

	/**
	 * Send http request
	 *
	 * @param  string $method
	 * @param  string $uri
	 * @param  mixed $body
	 *
	 * @return ResponseInterface
	 * @throws ClientExceptionInterface
	 */
    private function sendRequest($method, $uri, $body = null, $content_type = 'application/json') {
        $headers = $this->getRequestHeaders($content_type);
        $body = is_array($body) && $content_type === 'application/json' ? json_encode($body) : $body;

        $request = $this->request_factory->createRequest($method, $uri);
        
        // Add headers
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        
        // Add body if present
        if ($body !== null) {
            $stream = $this->stream_factory->createStream($body);
            $request = $request->withBody($stream);
        }
        
        $authenticated_request = $this->authenticateRequest($request);

        try {
            return $this->http->sendRequest($authenticated_request);
        } catch (ClientExceptionInterface $e) {
            throw new ClientHttpException($e);
        }
    }

    /**
     * Handle the reponse
     * Creates the context object if available
     *
     * @param ResponseInterface $response
     * @param string $resource
     *
     * @return stdClass
     */
    private function handleResponse(ResponseInterface $response, $resource = null) {
        $this->setRateLimit($response);

        if ($resource) {
            return new $resource($this, $response);
        } else {
            $stream = $response->getBody()->getContents();
            return json_decode($stream);
        }
    }

    /**
     * Gets the rate limit details.
     *
     * @return array
     */
    public function getRateLimit() {
        return $this->rate_limit;
    }

    /**
     * @return HttpClient
     */
    private function getDefaultHttpClient() {
        return new PluginClient(
            HttpClientDiscovery::find(),
            [new ErrorPlugin()]
        );
    }

    /**
     * @param ResponseInterface $response
     */
    private function setRateLimit(ResponseInterface $response) {
        $this->rate_limit = [
            'limit' => $response->hasHeader('X-RateLimit-Limit')
                ? (int)$response->getHeader('X-RateLimit-Limit')[0]
                : null,
            'remaining' => $response->hasHeader('X-RateLimit-Remaining')
                ? (int)$response->getHeader('X-RateLimit-Remaining')[0]
                : null,
            'reset_at' => $response->hasHeader('X-RateLimit-Reset')
                ? (new \DateTimeImmutable())->setTimestamp((int)$response->getHeader('X-RateLimit-Reset')[0])
                : null,
        ];
    }

	/**
     * Sends POST request to EZ Knock API.
     *
     * @param string $endpoint
     * @param array|null $data
     * @param string $resource
     *
     * @return stdClass
     */
    public function post($endpoint, $data = null, $resource = null, $content_type = 'application/json') {
        $uri = $this->getUri($endpoint);
        $response = $this->sendRequest('POST', $uri, $data, $content_type);
        return $this->handleResponse($response, $resource);
    }

    /**
     * Sends GET request to EZ Knock API.
     *
     * @param string $endpoint
     * @param array|null  $queryParams
     * @param string $resource
     *
     * @return stdClass
     */
    public function get($endpoint, array $params = null, $resource = null) {
        $uri = $this->uri_factory->createUri($this->getUri($endpoint));
        if (!empty($params)) $uri = $uri->withQuery(http_build_query($params));

        $response = $this->sendRequest('GET', $uri);
        return $this->handleResponse($response, $resource);
    }

    /**
     * Returns the next page of the result.
     *
     * @param  stdClass $pages
     * @return stdClass
     */
    public function nextPage($pages) {
        $response = $this->sendRequest('GET', $pages->next);
        return $this->handleResponse($response);
    }
}
