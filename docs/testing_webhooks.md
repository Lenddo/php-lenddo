# Testing Webhooks
Once you've implemented the webhooks you'll want to test your implementation. For this purpose you can use the `Lenddo\util\TestWebhook` class. This documentation serves to walk you through using this class.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [1. Gathering Required Data](#1-gathering-required-data)
- [2. Choosing a payload type](#2-choosing-a-payload-type)
- [3. Testing](#3-testing)
- [Understanding Errors](#understanding-errors)
  - [ReferenceException: Could not find Authorization Header!](#referenceexception-could-not-find-authorization-header)
  - [AuthorizationException: UnauthorizedException: Bad MAC](#authorizationexception-unauthorizedexception-bad-mac)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## 1. Gathering Required Data
To utilize the `Lenddo\util\TestWebhook` class you will want to ensure you have the following required pieces of information:

* **uri** - This is the fully qualified uri for where your script will live. This includes:
    * scheme _(http/https)_
    * domain
    * port _(if not standard to the scheme)_
    * path
    * query string
    
    This is essentially the URL you will provide Lenddo when configuring the webhook section of your partner script.

    For example:
    https://lenddo.com:9000/example/webhook.php?query=params

* **hash_key** - this is the key you configured retrieved from Lenddo's partner script configuration. You can revisit [Webhook Setup](webhooks.md#webhook-setup) from the webhooks document for more information.

* **partner_script_id** - this is your script id you retrieved from the Lenddo partner's dashboard. You can revisit [Webhook Setup](webhooks.md#webhook-setup) from the webhooks document for more information.

* **payload type** - Please read the next section.

## 2. Choosing a payload type
Webhooks are designed to send different types of data based on the services you subscribe to with Lenddo. With each of these payload types you can have different outcomes. The `Lenddo\util\TestWebhook` class is designed with this in mind by exposing multiple selectable payloads. The following are available payloads:

* **scoring_complete_no_flags** - this is a higher score sample with no flags.
* **scoring_complete_flags** - this payload has a lower score and a few indication flags.
* **verification_complete** - a sample outcome for a verification.
* **application_decision_complete_approved** - sample approval payload
* **application_decision_complete_denied** - sample denial payload
* **application_decision_complete_no_decision** - sample no decision payload with flags.

## 3. Testing
```php

$uri = 'https://lenddo.com:9000/example/webhook.php?query=params'; // reference step 1 above
$hash_key = ''; // reference step 1 above
$partner_script_id = ''; // reference step 1 above
$payload_type = 'scoring_complete_no_flags'; // reference step 2 above

$test = new TestWebhook($payload_type, $hash_key, $partner_script_id);
$request_options = $test->buildRequest($uri);

$response_body = $test->request($request_options);

if ($response_body === true) {
    // if the $response_body variable is strictly equal to (===) true
    echo 'Webhook success';
    exit;
}

echo "Webhook failed with the following body:\r\n" . $response_body;
```

## Understanding Errors
When you call the endpoint which includes the `authenticateRequest(...)` method of the `Lenddo\WebhookAuthentication` class you may encounter an error. This is usually due to a misconfiguration of either the test or the implementation.

### ReferenceException: Could not find Authorization Header!
This error occurs when the code is unable to find an **Authorization** request header. This should be sent from the client to the server. This error should not occur as long as you are using the `Lenddo\util\TestWebhook` class. If you do encounter this error while using the test class you may have a proxy, server, or some other component removing this header before it can be read by the `Lenddo\WebhookAuthentication::authenticateRequest(..)` method.

### AuthorizationException: UnauthorizedException: Bad MAC
This exception is the most common and it often has to do of one of a few conditions. All conditions involve incorrect or missing data. Due to the nature of signing a request it's not possible to determine in code what piece of data is wrong.

In all scenarios below you'll want to ensure the data defined in _step 1_ of this document is defined the same on both sides.

#### Misconfiguration on the server
This is the most common configuration foul. If the script is running behind a proxy, load balancer, or extension _(such as mod_rewrite)_ this can cause the signature to be signed from the caller using the external uri while the script is seeing a different uri. Be sure that everything mentioned in the **uri** section of step 1 above matches internally for the external url.

Please read the [Optional Configuration](webhooks.md#optional-configuration) section of the webhooks instructions for information on how to override host, port, and request path portions of the request uri serverside.