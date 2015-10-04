# Lenddo PHP SDK

This SDK will currently only allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular
GuzzleHttp/Guzzle package. Calling the clientScore() and clientVerification() methods will return a 
`Psr\Http\Message\ResponseInterface` object from the Guzzle Library.

# Installation


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
```php
<?php

$response = $client->clientVerification('CLIENT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

/**
Returns a JSON object for the requested verification with the following pattern:
stdClass Object
(
    [updated] => integer
    [created] => integer
    [flags] => Array
        (
            [0] => string
        )

    [verifications] => stdClass Object
        (
            [name] => integer verified == 1
            [university] => integer verified == 1
            [employer] => integer verified == 1
            [facebook_verified] => integer verified == 1
            [birthday] => integer verified == 1
            [top_employer] => integer verified == 1
        )

    [client_id] => CLIENT_ID
    [probes] => stdClass Object
        (
            [name] => Array
                (
                    [0] => string first
                    [1] => string middle
                    [2] => string last
                )

            [university] => stdClass Object
                (
                    [university] => string
                )

            [employer] => stdClass Object
                (
                    [employer] => string
                )

            [facebook_verified] => Array
                (
                    [0] => string verified_facebook_id
                )

            [birthday] => Array
                (
                    [0] => integer year
                    [1] => integer month
                    [2] => integer day
                )

            [top_employer] => string
        )

)

**/
$verification_results = json_decode($clientScore->getBody()->getContents());

$name_verified = $verification_results->verifications->name == 1;
$verification_reason_codes = $verification_results->flags; // array
```