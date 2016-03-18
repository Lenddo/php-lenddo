<?php

namespace Lenddo\clients\guzzle_handlers;

use Guzzle\Http\Client as GuzzleClient;
use Lenddo\clients\guzzle_handlers\response\V3Response as Response;

class GuzzleV3Handler implements HandlerInterface {
	protected $_base_uri;

	public function __construct($base_uri)
	{
		$this->_base_uri = $base_uri;
	}

	public function request($method, $path, $query, $headers, $body, $guzzle_options) {
		$guzzle_client = new GuzzleClient($this->_base_uri);

		if ($query) {
			$path .= '?' . http_build_query($query);
		}

		$request = $guzzle_client->createRequest($method, $path, $headers, $body, $guzzle_options);

		return new Response($request, $request->send());
	}
}