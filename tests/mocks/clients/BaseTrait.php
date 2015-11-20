<?php

namespace Lenddo\tests\mocks\clients;

/**
 * Class BaseTrait
 *
 * This trait is meant to provide a consistent Base class override for all classes which might derive the BaseClient.
 *
 * @package Lenddo\tests\mocks\clients
 */
trait BaseTrait {
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
	 * @param bool $return_original
	 * @return string
	 */
	protected function _getDateTimestamp($return_original = false)
	{
		if($return_original) {
			return parent::_getDateTimestamp();
		}
		return 'Sun Oct 4 21:45:10 CEST 2015';
	}
}