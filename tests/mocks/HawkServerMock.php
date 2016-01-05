<?php

namespace Lenddo\tests\mocks;

class HawkServerMock {
	public $throw_exception = false;
	public $last_authenticate_args = array();

	function authenticate() {
		if($this->throw_exception) {
			throw new \Exception('Something bad happened!');
		}
		$this->last_authenticate_args = func_get_args();
	}
}