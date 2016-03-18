<?php

namespace Lenddo\clients\guzzle_handlers;

use GuzzleHttp\Client as GuzzleClient;
// V6 is compatible with how we treat responses in v4
use Lenddo\clients\guzzle_handlers\response\V4Response as Response;

class GuzzleV6Handler implements HandlerInterface {
	protected $_base_uri;

	public function __construct($base_uri)
	{
		$this->_base_uri = $base_uri;
	}

	public function request($method, $path, $query, $headers, $body, $guzzle_options) {
		$guzzle_client = new GuzzleClient(array(
			'base_uri' => $this->_base_uri
		));

		$request = $guzzle_client->requestAsync($method, $path, array_merge($guzzle_options, array(
			'query' => $query,
			'headers' => $headers,
			'body' => $body
		)));


		return new Response($request, $request->wait());
	}
}