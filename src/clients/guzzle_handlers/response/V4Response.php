<?php

namespace Lenddo\clients\guzzle_handlers\response;
use GuzzleHttp\Psr7\Response;

/**
 * Class V4Response
 *
 * Guzzle v4 and v5 use the same request / response methods
 *
 * @package Lenddo\clients\guzzle_handlers\response
 */
class V4Response implements ResponseInterface {
	/**
	 * @var Response
	 */
	protected $_guzzle_response;
	protected $_guzzle_request;

	public function __construct($guzzle_request, $guzzle_response)
	{
		$this->_guzzle_request = $guzzle_request;
		$this->_guzzle_response = $guzzle_response;
	}

	public function getStatusCode()
	{
		return $this->_guzzle_response->getStatusCode();
	}

	public function getBody($parsed = true)
	{
		$body = (string)$this->_guzzle_response->getBody();
		return $parsed ? json_decode( $body ) : $body;
	}

	public function guzzleResponse()
	{
		return $this->_guzzle_response;
	}

	public function guzzleRequest()
	{
		return $this->_guzzle_request;
	}
}