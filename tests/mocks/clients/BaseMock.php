<?php

namespace Lenddo\tests\mocks\clients;

use Lenddo\clients\Base;

class BaseMock extends Base {
	use BaseTrait;

	public function __construct($api_app_id, $api_secret, array $options)
	{
		parent::__construct($api_app_id, $api_secret, $options);
		$this->setMockHttpClient();
	}
}