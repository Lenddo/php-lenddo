# Lenddo PHP SDK (v1.1)

[![Build Status](https://travis-ci.org/Lenddo/php-lenddo.svg?branch=master)](https://travis-ci.org/Lenddo/php-lenddo) [![codecov.io](https://img.shields.io/codecov/c/github/Lenddo/php-lenddo.svg)](http://codecov.io/github/Lenddo/php-lenddo?branch=master) [![Packagist](https://img.shields.io/packagist/v/lenddo/sdk.svg)](https://packagist.org/packages/lenddo/sdk)

## Installation
The Lenddo PHP SDK is available via Composer and can be installed by running the `composer require lenddo/sdk` command.

More information can be found here: https://packagist.org/packages/lenddo/sdk

## Introduction
### REST Services
The [ServiceClient](#result-service-client) and [WhiteLabelClient](#white-label-client) This SDK will allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular **GuzzleHttp\Guzzle** package. Calling the methods on the `ServiceClient` or `WhiteLabelClient` classes will return a `Psr\Http\Message\ResponseInterface` object from the Guzzle Library.

The **ServiceClient** will allow you to retrieve the scoring and verification results from Lenddo.

The **WhiteLabelClient** will allow you to utilize Lenddo services without any Lenddo branding. This method of implementation is the most complex but allows you to fully customize your users' experience.

### Webhook Services
While the REST Services allow you to retrieve the results of a scoring or verification job they require you to continue contacting Lenddo until results are available. For many situations this is less than ideal, due to this Lenddo offers a webhook service. The **webhook service** is a feature which allows you to receive a POST request at a designated URL the moment a result is available. For more please continue to the [Webhook Authentication](#webhooks) section of this documentation.

## Table of Contents
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->


- [Result Service Client](#result-service-client)
  - [Create the Lenddo REST Service Client](#create-the-lenddo-rest-service-client)
    - [Get the score for your Lenddo Client](#get-the-score-for-your-lenddo-client)
    - [Get the verification results for your Lenddo Client](#get-the-verification-results-for-your-lenddo-client)
- [White Label Client](#white-label-client)
  - [Introduction](#introduction)
  - [Instantiating the Client](#instantiating-the-client)
    - [PartnerToken](#partnertoken)
      - [Errors](#errors)
    - [CommitPartnerJob](#commitpartnerjob)
      - [Errors](#errors-1)
  - [Error Handling](#error-handling)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

# Result Service Client
The `ServiceClient` allows partners to retrieve scoring and verification results from Lenddo REST API's.

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

1. **client_id** - this is the client id that you're posting the token for. This must match the client_id you use in
    the **CommitPartnerJob** step.

2. **provider** - this is the token provider. Valid values are as follows:
    `Facebook`, ` LinkedIn`, ` Yahoo`, ` WindowsLive`, or ` Google`

3. **oauth key** - this is the key returned by oauth for interacting with the token.
    > **note:** The **key** and **secret** are not your _application_ key and secret.
    > They're the values which are returned after a user successfully authenticates with the social network's oauth.

4. **oauth secret** - optional, leave `null` if not applicable. Some OAuth providers may return a secret, when this
    is returned Lenddo will required the secret to use the token.

5. **token data** - This is the raw token as it was received from the provider in Array format.
    This may include an **extra_data** key.

```php
<?php

$response = $client->partnerToken($client_id, 'Facebook', $oauth_key, $oauth_secret, $token_data);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$post_token_results = json_decode($response->getBody()->getContents());

// Get the profile ID
$profile_id = $post_token_results->profile_id; // string - for example: 123FB
```

#### Errors
* **BAD_REQUEST** _HTTP Status Code: 400_
    Request was malformed, or missing required data.
    
* **INVALID_TOKEN** _HTTP Status Code: 400_
    Token data was missing required fields or fields had invalid values.

* **TOKEN_FAILURE** _HTTP Status Code: 400_
    Failure upon attempt to use the token.
    
* **INTERNAL_ERROR** _HTTP Status Code: 500_
    An internal error occurred. If this persists please contact a Lenddo Representative.

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

#### Errors
* **BAD_REQUEST** _HTTP Status Code: 400_
    Request was malformed, or missing required data.
    
* **PARTNER_CLIENT_ALREADY_PROCESSED** _HTTP Status Code 400_
    This occurs when the specified *client_id* has already been used.
    
* **INTERNAL_ERROR** _HTTP Status Code: 500_
    An internal error occurred. If this persists please contact a Lenddo Representative.

## Error Handling
You can retrieve the body of an error via the following method:
```php
try {
    //.. your request code here
} catch ( Exception $e ) {
    $http_status = $e->getResponse()->getStatusCode(); // 400
    
    // {"message": "Missing required token field refresh_token.", "name": "INVALID_TOKEN"}
    $error_body = json_decode($e->getResponse()->getBody()->getContents());
}
```


# Webhooks
Webhooks allow partners to receive notification that results are complete along with the results of scoring or verification the moment they're available. This is the fastest and most efficient way to receive results from Lenddo.

## Webhook Setup
Before doing any code implementation you'll need to perform the following steps:

1. Navigate to the [Partners Dashboard](https://partners.lenddo.com) and **log in**
2. On the left hand menu click **developers** and navigate to [**settings**](https://partners.lenddo.com/developer_settings)
3. For each **partner script** that you want to receive notifications on go to **webhook details**
    ![webhook details image](docs/img/webhook_details.PNG)
4. Define a **webhook url** - this is the url that we will contact when results are available.
5. Click **reset** near the **"Webhook Hash Key"** field and copy the new value.
6. Click **save** at the bottom of the Partner Script settings.

## Using the SDK
### Requirements
You will need two pieces of information to use the authentication portion of the SDK:
