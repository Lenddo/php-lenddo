# Request Exception Management
The request classes for the Lenddo SDK `\Lenddo\ServiceClient` and `\Lenddo\WhiteLabelClient` will both throw
an exception in the event the response is anything other than _200 OK_ or _201 CREATED_. These exceptions have been 
broken out into different classes which can be found in [src/clients/exceptions](../src/clients/exceptions).

The purpose of this page is to document each exception class as well as how to handle them.

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Request Exception Classes](#request-exception-classes)
- [Handling Request Exceptions](#handling-request-exceptions)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Request Exception Classes
**Lenddo\clients\exceptions\BadRequestException**

> Something was incorrect about the request made. Please refer to `$e->getBody()->message` to understand why.

**Lenddo\clients\exceptions\ForbiddenException**

> Either your credentials are not allowed to access the API or your credentials are incorrect. Please refer to the
dashboard found @ https://partners.lenddo.com. If you feel this is in error please contact your Lenddo Account Manager.

**Lenddo\clients\exceptions\InternalErrorException**

> This should not happen. If you find this occurring please contact Lenddo.

**Lenddo\clients\exceptions\NotFoundException**

> This means that the resource you have requested does not exist in our system. This means that if, for example, you are
 requesting the score or verification for a specific application ID, our system is unaware of it. This does not mean
 that our system will _never_ be aware of the application however and you may wish to continue to try for a while.
 The amount of time you spend trying varies based on your business model. If you have doubt please contact your Lenddo 
 representative. A good way of avoiding these (in general) is to utilize the [webhook system](webhooks.md).
 
 **Lenddo\clients\exceptions\UnknownExceptions**
 
 > The error received by the client was not known to this SDK. Please contact Lenddo if you encounter this. Sometimes 
 will find that the SDK may require an update which properly handles this error.

## Handling Request Exceptions
The following example will show how to handle exceptions while making a request for an **Application Score**. Any 
HTTP request can be filled in in place of the ApplicationScore call in the following example.

Please do not use the following code verbatim. You should handle these cases in the code below based on your business
logic. For example, the section for NotFoundException should have logic as to how you plan to handle a condition for not
finding a score.

```php
try {
	// Replace this request with any of the other SDK requests available.
	$response = $client->applicationScore($application_id, $partner_script_id);
} catch(\Lenddo\clients\exceptions\HttpException $e) {
	switch(true) {
		case $e instanceof \Lenddo\clients\exceptions\NotFoundException:
			echo 'NOT FOUND - The requested resource was not found';
			break;
		case $e instanceof \Lenddo\clients\exceptions\BadRequestException:
			$error_message = $e->getBody()->message;
			echo 'BAD REQUEST, Reason: ' . $error_message;
			break;
		case $e instanceof \Lenddo\clients\exceptions\ForbiddenException:
			echo 'FORBIDDEN - the credentials you have provided are not correct or cannot access this service.';
			break;
		case $e instanceof \Lenddo\clients\exceptions\InternalErrorException:
		case $e instanceof \Lenddo\clients\exceptions\UnknownException:
			echo 'Unhandled or Internal Exception. Please contact Lenddo.';
			break;
	}
}
```
