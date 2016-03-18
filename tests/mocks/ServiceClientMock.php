<?php

namespace Lenddo\tests\mocks;

use Lenddo\ServiceClient;

class ServiceClientMock extends ServiceClient
{
	public function __construct($api_app_id, $api_secret, array $options)
	{
		parent::__construct($api_app_id, $api_secret, $options);
		$this->setMockHttpClient();
	}

	/**
	 * override the classes which might rely on external dependencies.
	 * @var array
	 * @return string - Returns a copy of the mock class for the ability to compare results.
	 */
	public function setMockHttpClient() {
		return $this->_classes['http_client'] = 'Lenddo\tests\mocks\GuzzleClientMock';
	}

	/**
	 * Override the get date timestamp method so that we provide a constant time for expected testing.
	 * @return string
	 */
	protected function _getDateTimestamp()
	{
		return 'Sun Oct 4 21:45:10 CEST 2015';
	}

	/**
	 * Method used to expose the timestamp
	 * @return mixed
	 */
	public function mockExposeGetTimestamp() {
		return parent::_getDateTimestamp();
	}
}