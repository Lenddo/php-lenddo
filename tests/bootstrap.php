<?php

namespace Lenddo\tests;

require_once dirname(__DIR__) . '/vendor/autoload.php';

require_once __DIR__ . '/cases/BaseClientTestTrait.php';

require_once __DIR__ . '/mocks/GuzzleClientMock.php';
require_once __DIR__ . '/mocks/clients/BaseTrait.php';
require_once __DIR__ . '/mocks/clients/BaseMock.php';
require_once __DIR__ . '/mocks/ServiceClientMock.php';
require_once __DIR__ . '/mocks/WhiteLabelClientMock.php';
require_once __DIR__ . '/mocks/WebhookAuthenticationMock.php';
require_once __DIR__ . '/mocks/_shim/apache_request_headers.php';
require_once __DIR__ . '/mocks/HawkServerMock.php';