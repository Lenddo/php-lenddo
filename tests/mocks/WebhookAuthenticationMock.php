<?php

namespace Lenddo\tests\mocks;

use Lenddo\WebhookAuthentication;

class WebhookAuthenticationMock extends WebhookAuthentication {
	public function __construct($webhook_key, $partner_script_id, array $options = array())
	{
		parent::__construct($webhook_key, $partner_script_id, $options);

		if(isset($options['MockOverrideHawkServer'])) {
			$this->_hawk_server = new HawkServerMock();
		}
	}

	public function mockGetHawkServer() {
		return $this->_hawk_server;
	}

	public function mockCredentialsProvider($webhook_key, $provider_id) {
		return $this->_credentialsProvider($webhook_key, $provider_id);
	}

	public function mockGetAuthorizationHeader() {
		return $this->_get_authorization_header();
	}
}