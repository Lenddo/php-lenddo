# White Label Client
_Use Lenddo services while keeping your own branding_

The white label package comes in two service calls made to Lenddo which are meant to allow you to utilize Lenddo 
    services without having the user leave your own ecosystem.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Instantiating the Client](#instantiating-the-client)
- [PartnerToken](#partnertoken)
- [CommitPartnerJob](#commitpartnerjob)
- [Error Handling](#error-handling)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->


## Instantiating the Client
The Lenddo WhiteLabel client provides two primary functions, sending a network token, and sending an application.

1. The first service call is the **partnerToken** call which will allow you to send social network oauth tokens to
    Lenddo. These tokens will be used in the second step to provide scoring services for your client. This call returns
    a **profile_id** which you will be required to save so that you can send it to use for the second call.
2. The second call which you make to Lenddo will be the **commitPartnerJob** service call. This call creates a job for
    scoring based on the a one time use id _(known as the APPLICATION_ID)_, a list of **profile_ids** which you gathered from
    the first service call, and finally a **partner_script_id** which dictates how Lenddo will inform you of the results.

```php
<?php

// Fill out the ID & Secret provided to you by your contact at Lenddo.
$api_app_id = '';
$api_app_secret = '';

// Require the Composer autoloader
require 'vendor/autoload.php';

// Instantiate the Lenddo Service Client
$client = new Lenddo\WhiteLabelClient( $api_app_id , $api_app_secret );
```

## PartnerToken
**Note**: All token providers must be **OAuth 2.0**

PartnerToken has the following arguments:

1. **APPLICATION_ID** - this is the client id that you're posting the token for. This must match the APPLICATION_ID you use in
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

$response = $client->partnerToken($APPLICATION_ID, 'Facebook', $oauth_key, $oauth_secret, $token_data);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$post_token_results = $response->getBody();

// Get the profile ID
$profile_id = $post_token_results->profile_id; // string - for example: 123FB
```

### Errors
* **BAD_REQUEST** _HTTP Status Code: 400_
    Request was malformed, or missing required data.
    
* **INVALID_TOKEN** _HTTP Status Code: 400_
    Token data was missing required fields or fields had invalid values.

* **TOKEN_FAILURE** _HTTP Status Code: 400_
    Failure upon attempt to use the token.
    
* **INTERNAL_ERROR** _HTTP Status Code: 500_
    An internal error occurred. If this persists please contact a Lenddo Representative.

## CommitPartnerJob

CommitPartnerJob has the following arguments:

1. **partner script id** - Please reference the [developer section](https://partners.lenddo.com/developer_settings) 
    of the partner dashboard. This will define how you're notified of scoring results.

2. **application id** - This is essentially a one time use transaction id. Once this ID is used it cannot be used again.
    You can use this value in the [`ServiceClient::applicationScore`](#get-the-score-for-your-lenddo-application)
    to retrieve the score results.
    
3. **profile ids** - This is an array of ID's composed from the results of the
    [`WhiteLabelClient::PartnerToken`](#partnertoken) service call.

```php
<?php

// $profile_ids will be an array of the profile ID's that we've received as a response from PartnerToken
$profile_ids = array( '123FB' );
$application_id = '20160418-130';

$response = $client->commitPartnerJob($partner_script_id, $application_id, $profile_ids);

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$commit_job_results = $response->getBody();

// Get the profile ID
$success = $status_code === 200 && $commit_job_results->success = true;
```

### Errors
* **BAD_REQUEST** _HTTP Status Code: 400_
    Request was malformed, or missing required data.
    
* **PARTNER_CLIENT_ALREADY_PROCESSED** _HTTP Status Code 400_
    This occurs when the specified *APPLICATION_ID* has already been used.
    
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
    $error_body = $e->getBody();
}
```