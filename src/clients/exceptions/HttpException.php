<?php

namespace Lenddo\clients\exceptions;

use Lenddo\clients\guzzle_handlers\response\ResponseInterface;

abstract class HttpException extends \Exception {
	/**
	 * @var ResponseInterface
	 */
	protected $_response;

	public function __construct(\Exception $original, ResponseInterface $response) {
		parent::__construct($this->message, $response->getStatusCode(), $original);
		$this->_response = $response;
	}

	/**
	 * @return ResponseInterface returns a copy of the response object
	 */
	public function getResponse() {
		return $this->_response;
	}

	/**
	 * @return integer This will return the HTTP Status code. (i.e. 404, 500)
	 */
	public function getStatusCode() {
		return $this->_response->getStatusCode();
	}

	/**
	 * @param bool $parsed - Parse the body?
	 * @return \stdClass|string This will return a string if $parse is set to false
	 *    on the request. Alternatively an object will be returned.
	 */
	public function getBody($parsed = true) {
		return $this->_response->getBody($parsed);
	}
}