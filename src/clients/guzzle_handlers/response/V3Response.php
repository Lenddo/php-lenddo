<?php

namespace Lenddo\clients\guzzle_handlers\response;

class V3Response implements ResponseInterface {
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