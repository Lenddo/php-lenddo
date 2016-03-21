<?php
namespace Lenddo\clients\guzzle_handlers\response;

interface ResponseInterface {
	public function __construct($guzzle_request, $guzzle_response);
	public function guzzleRequest();
	public function guzzleResponse();
	public function getStatusCode();
	public function getBody($parsed = true);
}