<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\cases\BaseClientTestTrait;
use Lenddo\tests\mocks\ServiceClientMock;

class ServiceClientTest extends \PHPUnit_Framework_TestCase
{
	use BaseClientTestTrait;

	const API_USER = 'foo';
	const API_SECRET = 'bar';
	const CLIENT_ID = 'CLIENT_ID_123';

	protected function _buildServiceClient($options = array())
	{
		return new ServiceClientMock(static::API_USER, static::API_SECRET, $options);
	}

	public function testClientInstantiation()
	{
		$client = $this->_buildServiceClient();

		// Ensure the proper "default" host is being defined here.
		$this->assertEquals($this->_getExpectedBaseUri(), $client->getHosts()['score_service']);
	}

	public function testClientScore()
	{
		$expect_path = '/ClientScore/' . static::CLIENT_ID;

		$mock_result = $this->_buildServiceClient()->clientScore(static::CLIENT_ID);
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'GET', $expect_path);

		// Analyze Headers
		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:4hVTKBeF+2ZSc7jxhfQ/iF8jj2w=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => null
		), $request_options);
	}

	public function testClientVerification()
	{
		$expect_path = '/ClientVerification/' . static::CLIENT_ID;
		$mock_result = $this->_buildServiceClient()->clientVerification(static::CLIENT_ID);
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'GET', $expect_path);

		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:zj+dYWKbSox8AOPv1MiLt91hUDo=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => null
		), $request_options);
	}


	/**
	 * @return String
	 */
	protected function _getExpectedBaseUri()
	{
		return 'https://scoreservice.lenddo.com/';
	}
}