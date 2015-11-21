<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\mocks\ServiceClientMock;

class ServiceClientTest extends \PHPUnit_Framework_TestCase
{
	const API_USER = 'foo';
	const API_SECRET = 'bar';
	const CLIENT_ID = 'CLIENT_ID_123';

	protected function _buildServiceClient($options = array())
	{
		return new ServiceClientMock(static::API_USER, static::API_SECRET, $options);
	}

	public function testClientInstantiation()
	{
		$endpoint = 'http://foo.bar';
		$guzzle_request_options = array(
			'verify' => false // disable ssl verification
		);

		$client = $this->_buildServiceClient(array(
			'hosts' => array(
				'score_service' => $endpoint
			),
			'guzzle_request_options' => $guzzle_request_options
		));

		$this->assertInstanceOf('Lenddo\ServiceClient', $client);
		$this->assertEquals(static::API_USER, $client->getApiAppId());
		$this->assertEquals(static::API_SECRET, $client->getApiSecret());
		$this->assertEquals($endpoint, $client->getHosts()['score_service']);
		$this->assertEquals($guzzle_request_options, $client->getGuzzleRequestOptions());
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
	 * Exists for purposes of DRY code. This doesn't change from test to test so no reason to keep re-writing it.
	 *
	 * @param $mock_result \Lenddo\tests\mocks\GuzzleClientMock
	 * @param $expect_method
	 * @param $expect_path
	 * @return mixed
	 */
	protected function _testResultGetRequestOptions($mock_result, $expect_method, $expect_path)
	{
		list($method, $path, $request_options) = $mock_result->getRequestArgs();
		$construct_options = $mock_result->getConstructArgs()[0];

		$this->assertEquals($expect_method, $method);
		$this->assertEquals($expect_path, $path);

		$this->assertArrayHasKey('headers', $request_options);
		$this->assertArrayHasKey('Date', $request_options['headers']);

		// Analyze Construction
		$this->assertEquals(array(
			'base_uri' => 'https://scoreservice.lenddo.com/'
		), $construct_options);

		return $request_options;
	}
}