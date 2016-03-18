<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\mocks\ServiceClientMock;

class ServiceClientTest extends \Lenddo\tests\cases\BaseClientTest
{
	const API_USER = 'foo';
	const API_SECRET = 'bar';
	const PARTNER_SCRIPT_ID = '01234567890123456789abcd';
	const APPLICATION_ID = 'APPLICATION_ID_123';

	protected function _buildServiceClient($options = array())
	{
		return new ServiceClientMock(static::API_USER, static::API_SECRET, $options);
	}

	public function testClientInstantiation()
	{
		$client = $this->_buildServiceClient();
		$hosts = $client->getHosts();

		// Ensure the proper "default" host is being defined here.
		$this->assertEquals($this->_getExpectedBaseUri(), $hosts['score_service']);
	}

	public function testClientScore()
	{
		$expect_path = '/ClientScore/' . static::APPLICATION_ID;

		$mock_result = $this->_buildServiceClient()->clientScore(static::APPLICATION_ID);
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'GET', $expect_path);

		// Analyze Headers
		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:4J4eL1vy86eZT/1e8nehluJbD9U=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => null,
			'query' => array(),
			'method' => 'GET',
			'path' => '/ClientScore/APPLICATION_ID_123',
			'guzzle_options' => Array ()
		), $request_options);
	}

	public function testClientVerification()
	{
		$expect_path = '/ClientVerification/' . static::APPLICATION_ID;
		$mock_result = $this->_buildServiceClient()->clientVerification(static::APPLICATION_ID);
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'GET', $expect_path);

		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:u4b2StQKkbckv+wkRdOw6omCwIE=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => null,
			'query' => array(),
			'method' => 'GET',
			'path' => '/ClientVerification/APPLICATION_ID_123',
			'guzzle_options' => Array ()
		), $request_options);
	}

	public function testApplicationDecision()
	{

		$expect_path = '/ApplicationDecision/' . static::APPLICATION_ID;
		$mock_result = $this->_buildServiceClient()->applicationDecision(static::APPLICATION_ID, static::PARTNER_SCRIPT_ID);
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'GET', $expect_path);

		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:UyydJgGYk5VmwSp0x961joMKhBI=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => null,
			'query' => array(
				'partner_script_id' => static::PARTNER_SCRIPT_ID
			),
			'method' => 'GET',
			'path' => '/ApplicationDecision/APPLICATION_ID_123',
			'guzzle_options' => Array ()
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