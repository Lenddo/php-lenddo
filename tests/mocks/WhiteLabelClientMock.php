<?php

namespace Lenddo\tests\mocks;

use Lenddo\WhiteLabelClient;

class WhiteLabelClientMock extends WhiteLabelClient {
	use clients\BaseTrait;

	public function __construct($api_app_id, $api_secret, array $options)
	{
		parent::__construct($api_app_id, $api_secret, $options);
		$this->setMockHttpClient();
	}
}