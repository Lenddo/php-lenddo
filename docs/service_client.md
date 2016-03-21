# Result Service Client
The `ServiceClient` allows partners to retrieve scoring and verification results from Lenddo REST API's.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Create the Lenddo REST Service Client](#create-the-lenddo-rest-service-client)
- [Get a Score](#get-a-score)
- [Get a Verification](#get-a-verification)
- [Get an Application Decision](#get-an-application-decision)

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
Please refer to the [scoring response documentation](scoring_response.md) to understand the returned 
structure of the verification object.

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