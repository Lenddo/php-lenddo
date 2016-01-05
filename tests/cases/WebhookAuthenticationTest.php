<?php

namespace Lenddo\tests\cases;

use Lenddo\tests\mocks\WebhookAuthenticationMock;

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
		$client = new WebhookAuthenticationMock('aaa', 'bbb');
		$this->assertInstanceOf('Dflydev\Hawk\Server\Server', $client->mockGetHawkServer());
	}

	public function testConfigOnInstantiation() {
		$host = 'foo.bar';
		$port = 80;
		$request_path = '/path/to/endpoint?we_are=here';
		$client = new WebhookAuthenticationMock('aaa', 'bbb', array(
			'Host' => $host,
			'Port' => $port,
			'RequestPath' => $request_path
		));

		$this->assertEquals($host, $client->getHost());
		$this->assertEquals($port, $client->getPort());
		$this->assertEquals($request_path, $client->getRequestPath());
	}

	/**
	 * @expectedException \Lenddo\exceptions\ReferenceException
	 * @expectedExceptionMessage Could not find Authorization Header!
	 */
	public function testGetAuthorizationHeaderExpectException() {
		$client = new WebhookAuthenticationMock('aaa', 'bbb');

		$client->mockGetAuthorizationHeader();
	}

	public function testReturnDataFromApacheRequestHeaders() {
		$authorization_header = 'Test Authorization Header';
		\ApacheRequestHeadersConfig::$return_data = array(
			'Authorization' => $authorization_header
		);

		$client = new WebhookAuthenticationMock('aaa', 'bbb');

		$this->assertEquals($authorization_header, $client->mockGetAuthorizationHeader());
	}

	/**
	 * @expectedException \Lenddo\exceptions\ReferenceException
	 * @expectedExceptionMessage Could not find Authorization Header!
	 */
	public function testApacheHeadersNoData() {
		$client = new WebhookAuthenticationMock('aaa', 'bbb');
		$client->mockGetAuthorizationHeader();
	}

	public function testReturnDataFromServerSuperGlobal() {
		$_SERVER['HTTP_AUTHORIZATION'] = $authorization_header = 'Test Server Superglobal Header';

		$client = new WebhookAuthenticationMock('aaa', 'bbb');

		$this->assertEquals($authorization_header, $client->mockGetAuthorizationHeader());
	}

	public function testAuthenticateRequestNoArgs() {
		$client = new WebhookAuthenticationMock('aaa', 'bbb', array(
			'MockOverrideHawkServer' => true,
			'Host' => $host = 'foo.bar.1',
			'Port' => $port = 12345,
			'RequestPath' => $request_path = '/foo/bar?baz=1'
		));
		$_SERVER['HTTP_AUTHORIZATION'] = $authorization_header = 'Test Superglobal Header No Args';

		$client->authenticateRequest();

		$this->assertEquals(
			array(
				'POST', $host, $port, $request_path, 'application/x-www-form-urlencoded', null, $authorization_header
			),
			$client->mockGetHawkServer()->last_authenticate_args
		);
	}

	/**
	 * @expectedException \Lenddo\Exceptions\AuthorizationException
	 * @expectedExceptionMessage Exception: Something bad happened!
	 */
	public function testAuthenticateRequestWithError() {
		$client = new WebhookAuthenticationMock('aaa', 'bbb', array(
			'MockOverrideHawkServer' => true
		) );
		$_SERVER['HTTP_AUTHORIZATION'] = $authorization_header = 'Test Superglobal Header No Args';


		$client->mockGetHawkServer()->throw_exception = true;
		$client->authenticateRequest();
	}

	public function testAccepted() {
		ob_start();
		$client = new WebhookAuthenticationMock('aaa', 'bbb');
		$client->webhookAccepted();
		$output = ob_get_clean();

		$this->assertEquals('PHPSDK: webhook accepted', $output);
	}

	protected function tearDown()
	{
		\ApacheRequestHeadersConfig::$return_data = null;
		$_SERVER = array();
	}




}