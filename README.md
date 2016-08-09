# Lenddo PHP SDK

[![Build Status](https://travis-ci.org/Lenddo/php-lenddo.svg?branch=master)](https://travis-ci.org/Lenddo/php-lenddo) [![codecov.io](https://img.shields.io/codecov/c/github/Lenddo/php-lenddo.svg)](http://codecov.io/github/Lenddo/php-lenddo?branch=master) [![Packagist](https://img.shields.io/packagist/v/lenddo/sdk.svg)](https://packagist.org/packages/lenddo/sdk)

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of Contents**

- [Requirements](#requirements)
- [Installation](#installation)
- [REST Services](#rest-services)
  - [Service Client](#service-client)
  - [Whitelabel Client](#whitelabel-client)
  - [Handling Exceptions](#handling-exceptions)
- [Webhook Management](#webhook-management)
- [ChangeLog](#changelog)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Requirements
This SDK requires the following:
* >=PHP 5.3.3
* _Guzzle 3,4,5, or 6 !_
* _HAWK Authentication !_

_! These packages will be handled by the composer install process. They're listed for purposes of compatibility. If you are using a Guzzle version not compatible with our platform in your codebase already, please let us know and we'll upgrade the SDK as appropriate._

## Installation
The Lenddo PHP SDK is available via Composer and can be installed by running the `composer require lenddo/sdk` command. A local copy of composer is included if you do not have a global copy. This can be invoked by performing `php composer.phar install` in the root of this repository.

Please refer to the packagist link here: https://packagist.org/packages/lenddo/sdk.

## REST Services
The **ServiceClient** and **WhiteLabelClient** This SDK will allow you to contact Lenddo's REST based services. It acts as a wrapper around the  popular **GuzzleHttp\Guzzle** package. A common interface will be returned regardless of the Guzzle version used and the original Guzzle request/response objects are exposed for your convenience.

### Service Client
The **ServiceClient** will allow you to retrieve the scoring, verification, and decision results from Lenddo.
- [Read the documentation here](docs/service_client.md)

### Whitelabel Client
The **WhiteLabelClient** will allow you to utilize Lenddo services without any Lenddo branding. This method of implementation is the most complex but allows you to fully customize your users' experience.
- [Read the documentation here](docs/whitelabel_client.md)

### Handling Exceptions
Both the _whitelabel client_ and the _service client_ have a common interface for making requests. Because of this you
can utilize a single method for error handling for both classes.
- [Read the documentation here](docs/handling_exceptions.md)

## Webhook Management
While the REST Services allow you to retrieve the results of a scoring or verification job they require you to continue contacting Lenddo until results are available. For many situations this is less than ideal. Due to this Lenddo offers a webhook service. The webhook service is a feature which allows you to receive a POST request at a designated URL the moment a result is available.
- [Read the documentation here](docs/webhooks.md)

## ChangeLog
**v2.4** Release Notes - https://github.com/Lenddo/php-lenddo/releases/tag/v2.3
> **Summary**
> * Adding support for the new ExtraApplicationData endpoint
>     [Read the documentation here](docs/service_client.md#send-extra-application-data)

**v2.3** Release Notes - https://github.com/Lenddo/php-lenddo/releases/tag/v2.3
> **Summary**
> * Adding new utility to test webhooks locally.
>     [Read the documentation here](docs/testing_webhooks.md)

**v2.2** Release Notes - https://github.com/Lenddo/php-lenddo/releases/tag/v2.2

> **Summary**
> * Adding new Verification management class for whitelabel verification probes.

**v2.1** Release Notes - https://github.com/Lenddo/php-lenddo/releases/tag/v2.1

> **Summary**
> * Enhanced exception management
>   * New exception classes for reporting back error cases on REST API calls.
> * Documentation for exception management

**v2.0** Release Notes - https://github.com/Lenddo/php-lenddo/releases/tag/v2.0

> **Summary**
> * Breaking changes from v1.x
> * Support for PHP 5.3, 5.4
> * Support for Guzzle 3, 4, 5
> * Updated Documentation
> * Certificate Authority Root Inclusion
