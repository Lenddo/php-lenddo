<?php

namespace Lenddo\tests\cases\clients;

use Lenddo\tests\mocks\clients\BaseMock;

class BaseTest extends \PHPUnit_Framework_TestCase
{
	const API_USER = 'foo';
	const API_SECRET = 'bar';

	protected function _buildServiceClient($options = array())
	{
		return new BaseMock(static::API_USER, static::API_SECRET, $options);
	}

	public function testClientInstantiation()
	{
		$guzzle_request_options = array(
			'verify' => false // disable ssl verification
		);

		$client_config = array(
			'hosts' => array(
				'some_service' => 'http://foo.bar'
			),
			'guzzle_request_options' => $guzzle_request_options
		);

		$client = $this->_buildServiceClient($client_config);

		$this->assertEquals(static::API_USER, $client->getApiAppId());
		$this->assertEquals(static::API_SECRET, $client->getApiSecret());
		$this->assertEquals($client_config['hosts']['some_service'], $client->getHosts()['some_service']);
		$this->assertEquals($guzzle_request_options, $client->getGuzzleRequestOptions());
	}
}