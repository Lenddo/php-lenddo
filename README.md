# Lenddo PHP SDK

[![Build Status](https://travis-ci.org/Lenddo/php-lenddo.svg?branch=master)](https://travis-ci.org/Lenddo/php-lenddo) [![codecov.io](https://img.shields.io/codecov/c/github/Lenddo/php-lenddo.svg)](http://codecov.io/github/Lenddo/php-lenddo?branch=master) [![Packagist](https://img.shields.io/packagist/v/lenddo/sdk.svg)](https://packagist.org/packages/lenddo/sdk)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**  *generated with [DocToc](https://github.com/thlorenz/doctoc)*

  - [Introduction](#introduction)
  - [Installation](#installation)
- [Primary Service Client Sample Usage](#primary-service-client-sample-usage)
  - [Create the Lenddo REST Service Client](#create-the-lenddo-rest-service-client)
    - [Get the score for your Lenddo Client](#get-the-score-for-your-lenddo-client)
    - [Get the verification results for your Lenddo Client](#get-the-verification-results-for-your-lenddo-client)
- [White Label Client](#white-label-client)
  - [Introduction](#introduction-1)
  - [Instantiating the Client](#instantiating-the-client)
    - [PartnerToken](#partnertoken)
    - [CommitPartnerJob](#commitpartnerjob)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Introduction

This SDK will currently only allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular
**GuzzleHttp\Guzzle** package. Calling the methods on `ServiceClient` or `WhiteLabelClient` will return a 
`Psr\Http\Message\ResponseInterface` object from the Guzzle Library.

## Installation
The Lenddo PHP SDK is available via Composer and can be installed by running the `composer require lenddo/sdk` command.

More information can be found here: https://packagist.org/packages/lenddo/sdk


# Primary Service Client Sample Usage
## Create the Lenddo REST Service Client
```php
<?php

// Fill out the ID & Secret provided to you by your contact at Lenddo.
$id = '';
$secret = '';

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
$score_results = json_decode($response->getBody()->getContents());

// Return the score value and reason flags.
$score_value = $score_results->score;
$score_flags = $score_results->flags;
```

### Get the verification results for your Lenddo Client
Please refer to the [verification response documentation](https://github.com/Lenddo/php-lenddo/blob/master/docs/verification_response.md) to understand the returned 
structure of the verification object.

```php
<?php

$response = $client->clientVerification('CLIENT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Returns a JSON object for the requested verification.
$verification_results = json_decode($response->getBody()->getContents());

$name_verified = $verification_results->verifications->name == 1;
$verification_reason_codes = $verification_results->flags; // array
```

# White Label Client
## Introduction
_Use Lenddo services while keeping your own branding_

The white label package comes in two service calls made to Lenddo which are meant to allow you to utilize Lenddo 
    services without having the user leave your own ecosystem.

1. The first service call is the **partnerToken** call which will allow you to send social network oauth tokens to
    Lenddo. These tokens will be used in the second step to provide scoring services for your client. This call returns
    a **profile_id** which you will be required to save so that you can send it to use for the second call.
2. The second call which you make to Lenddo will be the **commitPartnerJob** service call. This call creates a job for
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

2. **oauth key** - this is the key returned by oauth for interacting with the token.

3. **oauth secret** - optional, leave `null` if not applicable. Some OAuth providers may return a secret, when this
    is returned Lenddo will required the secret to use the token.

4. **token data** - This is the raw token as it was received from the provider in Array format.
    This may include an **extra_data** key.

```php
<?php

$response = $client->partnerToken('Facebook', $oauth_key, $oauth_secret, $token_data);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$post_token_results = json_decode($response->getBody()->getContents());

// Get the profile ID
$profile_id = $post_token_results->profile_id; // string - for example: 123FB
```


### CommitPartnerJob

CommitPartnerJob has the following arguments:

1. **partner script id** - Please reference the [developer section](https://partners.lenddo.com/developer_settings) 
    of the partner dashboard. This will define how you're notified of scoring results.

2. **client id** - This is essentially a one time use transaction id. Once this ID is used it cannot be used again.
    You can use this value in the [`ServiceClient::clientScore`](#get-the-score-for-your-lenddo-client)
    to retrieve the score results.
    
3. **profile ids** - This is an array of ID's composed from the results of the
    [`WhiteLabelClient::PartnerToken`](#partnertoken) service call.

```php
<?php

// $profile_ids will be an array of the profile ID's that we've received as a response from PartnerToken
$profile_ids = array( '123FB' );

$response = $client->commitPartnerJob($partner_script_id, $client_id, $profile_ids);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$commit_job_results = json_decode($response->getBody()->getContents());

// Get the profile ID
$success = $status_code === 200 && $commit_job_results->success = true;
```
