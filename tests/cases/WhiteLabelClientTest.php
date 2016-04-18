<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\mocks\WhiteLabelClientMock;

class WhiteLabelClientTest extends \Lenddo\tests\cases\BaseClientTest
{

	const API_USER = 'foo';
	const API_SECRET = 'bar';
	const CLIENT_ID = 'CLIENT_ID_123';

	protected $_client_id;
	protected $_provider;
	protected $_oauth_key;
	protected $_oauth_secret;
	protected $_token_data;
	protected $_profile_ids;
	protected $_partner_script_id;

	protected function _buildServiceClient($options = array())
	{
		return new WhiteLabelClientMock(static::API_USER, static::API_SECRET, $options);
	}

	protected function setUp()
	{
		$this->_client_id = '123';
		$this->_provider = 'Google';
		$this->_oauth_key = 'I am a key!';
		$this->_oauth_secret = 'Open Sesame.';
		$this->_token_data = array('extra_data' => array('foo' => 'bar'));
		$this->_profile_ids = array( '123FB', 'ABC@gmail.comEM' );
		$this->_partner_script_id = '012345678901234567891234';
	}

	public function testClientInstantiation()
	{
		$client = $this->_buildServiceClient();
		$hosts = $client->getHosts();

		// Ensure the proper "default" host is being defined here.
		$this->assertEquals($this->_getExpectedBaseUri(), $hosts['network_service']);
	}

	public function testPartnerToken()
	{
		$client = $this->_buildServiceClient();

		$result = $client->partnerToken($this->_client_id, $this->_provider, $this->_oauth_key, $this->_oauth_secret, $this->_token_data);
		$request_options = $this->_testResultGetRequestOptions($result, 'POST', '/PartnerToken');

		$this->assertEquals(array(
			'headers' =>
				array(
					'Authorization' => 'LENDDO foo:ihx73dLAIA03QhS3E+IuwqnpPCY=',
					'Content-Type' => 'application/json',
					'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
					'Connection' => 'close',
				),
			'body' => '{"token_data":{"key":"I am a key!","secret":"Open Sesame.","extra_data":{"foo":"bar"}},"provider":"Google","client_id":"123"}',
			'query' => array(),
			'method' => 'POST',
			'path' => '/PartnerToken',
			'guzzle_options' => Array ()
		), $request_options);
	}

	public function testPartnerTokenInvalidProvider()
	{
		$this->_provider = 'Invalid Provider!';
		$client = $this->_buildServiceClient();

		$this->setExpectedException(
				'InvalidArgumentException',
				'$provider must be one of the following: Facebook, LinkedIn, Yahoo, WindowsLive, Google'
		);

		$client->partnerToken($this->_client_id, $this->_provider, $this->_oauth_key, $this->_oauth_secret, $this->_token_data);
	}

	public function testCommitPartnerJob()
	{
		$client = $this->_buildServiceClient();

		$result = $client->commitPartnerJob($this->_partner_script_id, $this->_client_id, $this->_profile_ids);
		$request_options = $this->_testResultGetRequestOptions($result, 'POST', '/CommitPartnerJob');

		$this->assertEquals(array (
			'headers' =>
				array (
					'Authorization' => 'LENDDO foo:J/XZ2KELoFUNSkMZmO5gcaOyFy0=',
					'Content-Type' => 'application/json',
					'Date' => 'Sun Oct 4 21:45:10 CEST 2015',
					'Connection' => 'close',
				),
			'body' => '{"client_id":"123","profile_ids":["123FB","ABC@gmail.comEM"],"partner_script_id":"012345678901234567891234","verification_data":[]}',
			'query' => array(),
			'method' => 'POST',
			'path' => '/CommitPartnerJob',
			'guzzle_options' => Array ()
		), $request_options);
	}

	public function testCommitPartnerJobNoProfiles()
	{
		$this->_profile_ids = array();
		$client = $this->_buildServiceClient();

		$this->setExpectedException(
				'InvalidArgumentException',
				'$profile_ids must contain at least one entry.'
		);

		$client->commitPartnerJob($this->_partner_script_id, $this->_client_id, $this->_profile_ids);
	}

	/**
	 * @return String
	 */
	protected function _getExpectedBaseUri()
	{
		return 'https://networkservice.lenddo.com/';
	}
}