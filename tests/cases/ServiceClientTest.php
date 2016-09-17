<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\mocks\ServiceClientMock;

class ServiceClientTest extends \Lenddo\tests\cases\BaseClientTest
{
	const API_USER = 'foo';
	const API_SECRET = 'bar';
	const PARTNER_SCRIPT_ID = '01234567890123456789abcd';
	const APPLICATION_ID = 'APPLICATION_ID_123';

	protected $_base_uri = '';
	protected $_urls = array(
		'score' => 'https://scoreservice.lenddo.com/',
		'network' => 'https://networkservice.lenddo.com/'
	);

	protected function _buildServiceClient($options = array())
	{
		$this->_setExpectedBaseUri("score");
		return new ServiceClientMock(static::API_USER, static::API_SECRET, $options);
	}

	public function testClientInstantiation()
	{
		$this->_setExpectedBaseUri("score");
		$client = $this->_buildServiceClient();
		$hosts = $client->getHosts();

		// Ensure the proper "default" host is being defined here.
		$this->assertEquals($this->_getExpectedBaseUri(), $hosts['score_service']);
	}

	public function testClientScore()
	{
		$this->_setExpectedBaseUri("score");
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
		$this->_setExpectedBaseUri("score");
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
		$this->_setExpectedBaseUri("score");
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

	public function testExtraApplicationData()
	{
		$expect_path = '/ExtraApplicationData';
		$mock_result = $this->_buildServiceClient()->extraApplicationData(static::APPLICATION_ID, static::PARTNER_SCRIPT_ID, array(
			'foo' => 'bar',
			'baz' => array(
				'qux' => true
			)
		));

		$this->_setExpectedBaseUri("network");
		$request_options = $this->_testResultGetRequestOptions($mock_result, 'POST', $expect_path);

		$this->assertEquals(array(
			'headers' => array(
				'Authorization' => 'LENDDO foo:ASXEcd55nTYu++R44E7qchbCv1g=',
				'Content-Type' => 'application/json',
				'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
				'Connection' => 'close'
			),
			'body' => json_encode(array(
				'application_id' => static::APPLICATION_ID,
				'partner_script_id' => static::PARTNER_SCRIPT_ID,
				'extra_data' => array(
					'foo' => 'bar',
					'baz' => array(
						'qux' => true
					)
				)
			)),
			'query' => array(),
			'method' => 'POST',
			'path' => '/ExtraApplicationData',
			'guzzle_options' => Array ()
		), $request_options);
	}

	/**
	 * @param "score"|"network" $base
	 */
	protected function _setExpectedBaseUri($base) {
		$this->_base_uri = $this->_urls[$base];
	}

	/**
	 * @return String
	 */
	protected function _getExpectedBaseUri()
	{
		return $this->_base_uri;
	}
}