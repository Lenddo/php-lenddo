<?php

namespace Lenddo\clients\guzzle_handlers;

use GuzzleHttp\Client as GuzzleClient;
use Lenddo\clients\guzzle_handlers\response\V5Response as Response;

class GuzzleV5Handler implements HandlerInterface {
	protected $_base_uri;

	public function __construct($base_uri)
	{
		$this->_base_uri = $base_uri;
	}

	public function request($method, $path, $query, $headers, $body, $guzzle_options) {
		$guzzle_client = new GuzzleClient(array(
			'base_url' => $this->_base_uri
		));

		$request = $guzzle_client->createRequest($method, $path, array_merge($guzzle_options, array(
			'query' => $query,
			'headers' => $headers,
			'body' => $body
		)));

		return new Response($request, $guzzle_client->send($request));
	}
}