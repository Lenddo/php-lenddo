# Result Service Client
The `ServiceClient` allows partners to retrieve scoring and verification results from Lenddo REST API's.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Create the Lenddo REST Service Client](#create-the-lenddo-rest-service-client)
- [Get a Score](#get-a-score)
- [Get a Verification](#get-a-verification)
- [Get an Application Decision](#get-an-application-decision)
- [Send Extra Application Data](#send-extra-application-data)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->


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

## Get a Score
Please refer to the [scoring response documentation](scoring_response.md) to understand the returned result.

To retrieve the score you'll need the application ID and the partner script ID that you used to create the application.

```php
<?php

$response = $client->applicationScore('APPLICATION_ID', 'PARTNER_SCRIPT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$score_results = $response->getBody();

// Return the score value and reason flags.
$score_value = $score_results->score;
$score_flags = $score_results->flags;
```

## Get results for a Multiple Score model
To retrieve the results for a multiple score model you'll need the application ID and the partner script ID that you 
used to create the application. Please use this if directed by your Lenddo Representative.

```php
<?php

$response = $client->applicationMultipleScores('APPLICATION_ID', 'PARTNER_SCRIPT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Retrieve the body of the response
$score_results = $response->getBody();

// Return the score value and reason flags.
$scores_array = $score_results->scores; // array of scores
$flags = $score_results->flags; // all flags

// first score result
$score_1_model = $scores_array->scores[0]->model_id;
$score_1_value = $scores_array->scores[0]->score;
$score_1_flags = $scores_array->scores[0]->flags;
$score_1_created = $scores_array->scores[0]->created;
$score_1_features_values = $scores_array->scores[0]->feature_values;
```

## Get a Verification
Please refer to the [verification response documentation](https://github.com/Lenddo/php-lenddo/blob/master/docs/verification_response.md) to understand the returned 
structure of the verification object.

To retrieve the verification you'll need the application ID and the partner script ID that you used to create the application.

```php
<?php

$response = $client->applicationVerification('APPLICATION_ID', 'PARTNER_SCRIPT_ID');

// Get the Status Code for the response
$status_code = $response->getStatusCode(); // 200

// Returns a JSON object for the requested verification.
$verification_results = $response->getBody();

$name_verified = $verification_results->verifications->name == 1;
$verification_reason_codes = $verification_results->flags; // array
```

## Get an Application Decision
Please refer to the [application decision response documentation](application_decision_response.md) to understand
the returned structure of the application decision object.

To retrieve the decision you'll need the application ID and the partner script ID that you used to create the application.

```php
<?php

$response = $client->applicationDecision('APPLICATION_ID', 'PARTNER_SCRIPT_ID');

// Get the status code for the response
$status_code = $response->getStatusCode(); // 200

$application_decision_results = $response->getBody();

// Get the decision
switch($application_decision_results->decision) {
    case "APPROVE":
        // approved logic here
        break;
    case "DENY":
        // deny logic here
        break;
    case "NO_DECISION":
        // logic for further testing of the applicant here
        break;
    default:
        // Notify necessary staff here
        throw new Error('Unknown Application Decision Result!');
}
```

## Send Extra Application Data
If you're sending extra information with your application you can use this method to submit it. Extra Application Data 
may be used to enhance the model performance based on data you may already have about a user.

### Notes
1. You cannot make this request more than once per application/partner script combination. Doing so will result in a 
    _BAD_REQUEST_ response from the service. Please read the [documentation here](handling_exceptions.md) on how to 
    handle errors.
2. If you do not know what this functionality is but would like to submit data for Lenddo to work with please contact 
    your Lenddo representative.
3. Format of the data being sent in the _$data_ field should be pre-negotiated with Lenddo and shouldn't deviate from 
    agreement to maximize the use of this call.

### Definition
_extraApplicationData($application_id, $partner_script_id, $data)_

* _$application_id_ & _$partner_script_id_ are the respective ID's you sent the user to the Lenddo service with.
* _$data_ is an **Array** and must contain at least one element

### Return
The return result is an instance of _Lenddo\clients\guzzle_handlers\response\ResponseInterface_.

#### Successful Response
```json
{"success": true}
```
```php
$success = $response->getBody()->success;
```

### Usage
```php
$result = $client->extraApplicationData('APPLICATION_ID', 'PARTNER_SCRIPT_ID', array(
    'balance' => 1234.56
));

if ($result->getBody()->success) {
    // success!
}
```