# ezknock-php-sdk

[![packagist](https://img.shields.io/packagist/v/ezknock/ezknock-php-sdk)](https://packagist.org/packages/ezknock/ezknock-php-sdk)

> Official PHP bindings to the [EZ Knock API](https://api.ezknockmarketplace.com/docs)

## Project Updates

## Installation

This library supports PHP 7.3 and later

This library uses [HTTPlug](https://github.com/php-http/httplug) as HTTP client. HTTPlug is an abstraction that allows this library to support many different HTTP Clients. Therefore, you need to provide it with an adapter for the HTTP library you prefer. You can find all the available adapters [in Packagist](https://packagist.org/providers/php-http/client-implementation). This documentation assumes you use the Guzzle7 Client, but you can replace it with any adapter that you prefer.

The recommended way to install ezknock-php-sdk is through [Composer](https://getcomposer.org):

```sh
composer require ezknock/ezknock-php-sdk php-http/guzzle7-adapter
```

## Clients

Initialize your client using your access token:

```php
use EZKnock\Client as EZKClient;

$client = new EZKClient('<insert_token_here>');
```

> If you already have an access token you can find it [here](https://developers.ezknockmarketplace.com/apps). If you want to create or learn more about access tokens then you can find more info [here](https://developers.ezknockmarketplace.com/docs#section-access-tokens).

For most use cases the code snippet above should suffice. However, if needed, you can customize the EZKnock client as follows:

### Use a custom HTTP client

This client needs to implement `Psr\Http\Client\ClientInterface`

```php
$client->setHttpClient($yourHttpClient);
```

### Use a custom request factory

This factory needs to implement `Http\Message\RequestFactory`

```php
$client->setRequestFactory($yourRequestFactory);
```

### Use a custom URI factory

This factory needs to implement `Http\Message\UriFactory`

```php
$client->setUriFactory($yourUriFactory); 
```

## Coverage

Get coverage information by Zip code.

```php
try {
    $data = $client->buyers->coverage('73301');
    print_r($data);
} catch (Exception $ex) {
    print_r($ex->getMessage());
    print_r($ex->getDebug());
}
```

## Create order

Create an order

```php
$data = [
    'recipients' => [
        [
            'firstname' => 'Ryann',
            'lastname' => 'Ullrich',
            'company' => 'Romaguera, Yundt and Marvin',
            'email' => 'nikita67@kihn.com'
        ]
    ],
    'address' => '2453 W Vita Locks Rapids',
    'address_2' => 'BLDG 377',
    'city' => 'Austin',
    'state' => 'TX',
    'zip' => '78749',
    'return_type' => 'default',
    'originals' => false,
    'witnessfees' => true,
    'instructions' => 'Aut perferendis et necessitatibus. Vel tempore molestiae ut nihil dolore. Rem dolor sed nulla cupiditate.',
    'min_attempts' => 3,
    'documents' => 'ORDER FOR ALTERNATIVE SERVICE AND TO EXTEND TIME FOR SERVICE OF PROCESS; SUMMONS; COMPLAINT; EXHIBIT; NOTICE TO DEFENDANT',
    'attempt_by' => '2021-05-10',
    'diligence_by' => '2021-05-15',
    'complete_by' => '2021-05-20',
    'service_files' => [fopen('/path/to/file.pdf', 'r')],
    'other_files' => [fopen('/path/to/file.pdf', 'r')],
    'return_file' => fopen('/path/to/file.pdf', 'r'),
    'qualifications' => [1, 2, 3],
    'costs' => [
        [
            'name' => 'delivery',
            'amount' => 50
        ],
        [
            'name' => 'rush',
            'amount' => 20
        ]
    ]
];

try {
    $order = $client->buyers->createOrder($data);
    print_r($order);
} catch (Exception $ex) {
    print_r($ex->getMessage());
    print_r($ex->getDebug());
}
```

## Rate Limits

Rate limit info is passed via the rate limit headers.
You can access this information as follows:

```php
$rate_limit = $client->getRateLimit();
print("{$rate_limit['remaining']} {$rate_limit['limit']} \n");
print_r($rate_limit['reset_at']->format(DateTime::ISO8601));
```

For more info on rate limits and these headers please see the [API reference docs](https://developers.ezknockmarketplace.com/docs#rate-limiting)

## Pagination

When listing, the EZ Knock API may return a pagination object:

```json
{
  "pages": {
    "next": "..."
  }
}
```

You can grab the next page of results using the client:

```php
$client->nextPage($response->pages);
```

## Exceptions

Exceptions are handled by HTTPlug. Every exception thrown implements `Http\Client\Exception`. See the [http client exceptions](http://docs.php-http.org/en/latest/httplug/exceptions.html) and the [client and server errors](http://docs.php-http.org/en/latest/plugins/error.html).
The EZ Knock API may return an unsuccessful HTTP response, for example when a resource is not found (404).
If you want to catch errors you can wrap your API call into a try/catch block:

```php
try {
    $user = $client->buyers->coverage('73301');
} catch(Http\Client\Exception $e) {
    if ($e->getCode() == '404') {
        // Handle 404 error
        return;
    } else {
        throw $e;
    }
}
```

## Pull Requests

- **Add tests!** Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour**. Make sure the README and any other
  relevant documentation are kept up-to-date.

- **Create topic branches**. Don't ask us to pull from your master branch.

- **One pull request per feature**. If you want to do more than one thing, send
  multiple pull requests.

- **Send coherent history**. Make sure each individual commit in your pull
  request is meaningful. If you had to make multiple intermediate commits while
  developing, please squash them before sending them to us.
