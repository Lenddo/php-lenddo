# Lenddo PHP SDK

[![Build Status](https://travis-ci.org/Lenddo/php-lenddo.svg?branch=master)](https://travis-ci.org/Lenddo/php-lenddo) [![codecov.io](https://img.shields.io/codecov/c/github/Lenddo/php-lenddo.svg)](http://codecov.io/github/Lenddo/php-lenddo?branch=master) [![Packagist](https://img.shields.io/packagist/v/lenddo/sdk.svg)](https://packagist.org/packages/lenddo/sdk)

This SDK will currently only allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular
GuzzleHttp\Guzzle package. Calling the clientScore() and clientVerification() methods will return a 
`Psr\Http\Message\ResponseInterface` object from the Guzzle Library.

# Installation
The Lenddo PHP SDK is available via Composer and can be installed by running the `composer require lenddo/sdk` command.

More information can be found here: https://packagist.org/packages/lenddo/sdk


# Sample Usage
## Create the Lenddo REST Service Client
```php
<?php

// Fill out the ID & Secret provided to you by your contact at Lenddo.
$api_app_id = '';
$api_app_secret = '';

// Require the Composer autoloader
require 'vendor/autoload.php';

// Instantiate the Lenddo Service Client
$client = new Lenddo\ServiceClient( $id, $secret );
```

### Get the score for your Lenddo Client
```php
<?php

$response = $client->clientScore('CLIENT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$score_results = json_decode($clientScore->getBody()->getContents());

// Return the score value and reason flags.
$score_value = $score_results->score;
$score_flags = $score_results->flags;
```

### Get the verification results for your Lenddo Client
Please refer to the [verification response documentation](docs/verification_response.md) to understand the returned 
structure of the verification object.

```php
<?php

$response = $client->clientVerification('CLIENT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Returns a JSON object for the requested verification.
$verification_results = json_decode($clientScore->getBody()->getContents());

$name_verified = $verification_results->verifications->name == 1;
$verification_reason_codes = $verification_results->flags; // array
```
