<?php

namespace Lenddo\tests\cases;

use Lenddo\WebhookAuthentication;

class WebhookAuthenticationTest extends \PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
		$_SERVER = array(
			'HTTP_HOST' => 'lenddo.com',
			'REQUEST_URI' => '/path/to/page?foo=bar'
		);
	}


	public function testClientInstantiation() {
		new WebhookAuthentication('aaa', 'bbb');
	}
}