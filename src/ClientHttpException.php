<?php

namespace EZKnock;

use Psr\Http\Client\ClientExceptionInterface;

class ClientHttpException extends \Exception {

    private $debug;

    public function __construct(ClientExceptionInterface $e) {
        $message = $e->getMessage();
        $code = $e->getCode();

        $response = $e->getResponse();
        $stream = $response->getBody()->getContents();
        if ($body = json_decode($stream)) {
            $message = $body->message ?? $message;
            $code = $response->getStatusCode();
            $this->debug = $body->debug ?? null;
        }

        parent::__construct($message, $code);
    }

    /**
     * Get debug information
     * Only available in sandbox environment
     *
     * @return array
     */
    public function getDebug() {
        return $this->debug;
    }
}
