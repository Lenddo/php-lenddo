# Lenddo PHP SDK

[![Build Status](https://travis-ci.org/Lenddo/php-lenddo.svg?branch=master)](https://travis-ci.org/Lenddo/php-lenddo) [![codecov.io](https://img.shields.io/codecov/c/github/Lenddo/php-lenddo.svg)](http://codecov.io/github/Lenddo/php-lenddo?branch=master) [![Packagist](https://img.shields.io/packagist/v/lenddo/sdk.svg)](https://packagist.org/packages/lenddo/sdk)

This SDK will currently only allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular
GuzzleHttp\Guzzle package. Calling the clientScore() and clientVerification() methods will return a 
`Psr\Http\Message\ResponseInterface` object from the Guzzle Library.

# Installation
The Lenddo PHP SDK is available via Composer and can be installed by running the `composer require lenddo/sdk` command.

More information can be found here: https://packagist.org/packages/lenddo/sdk


# Primary Service Client Sample Usage
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

# White Label Client
## Introduction
_Use Lenddo services while keeping your own branding_

The white label package comes in two service calls made to Lenddo which are meant to allow you to utilize Lenddo 
    services without having the user leave your own ecosystem.

1. The first service call is the **PartnerToken** call which will allow you to send social network oauth tokens to
    Lenddo. These tokens will be used in the second step to provide scoring services for your client. This call returns
    a **profile_id** which you will be required to save so that you can send it to use for the second call.
2. The second call which you make to Lenddo will be the **CommitPartnerJob** service call. This call creates a job for
    scoring based on the a one time use id _(known as the client_id)_, a list of **profile_ids** which you gathered from
    the first service call, and finally a **partner_script_id** which dictates how Lenddo will inform you of the results.

## Instantiating the Client
```php
<?php

// Fill out the ID & Secret provided to you by your contact at Lenddo.
$api_app_id = '';
$api_app_secret = '';

// Require the Composer autoloader
require 'vendor/autoload.php';

// Instantiate the Lenddo Service Client
$client = new Lenddo\WhiteLabelClient( $id, $secret );
```

### PartnerToken
**Note**: All token providers must be **OAuth 2.0**

PartnerToken has the following arguments:

1. **provider** - this is the token provider. Valid values are as follows:
    `Facebook`, ` LinkedIn`, ` Yahoo`, ` WindowsLive`, or ` Google`

2. **oauth_key** - this is the key returned by oauth for interacting with the token.

3. **oauth_secret** - optional, leave `null` if not applicable. Some OAuth providers may return a secret, when this
    is returned Lenddo will required the secret to use the token.

4. **token_data** - This is the raw token as it was received from the provider in Array format.
    This may include an **extra_data** key.

```php
<?php

$response = $client->PartnerToken('Facebook', $oauth_key, $oauth_secret, $token_data);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$score_results = json_decode($clientScore->getBody()->getContents());

// Return the score value and reason flags.
$score_value = $score_results->score;
$score_flags = $score_results->flags;
```


### CommitPartnerJob