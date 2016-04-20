<?php

namespace Lenddo\clients\guzzle_handlers;

use Lenddo\clients\guzzle_handlers\response\ResponseInterface;

interface HandlerInterface {
	public function __construct($base_uri);

	/**
	 * @param string $method
	 * @param string $path
	 * @param array $query
	 * @param array $headers
	 * @param string $body
	 * @param array $guzzle_options
	 * @return ResponseInterface
	 */
	public function request($method, $path, $query, $headers, $body, $guzzle_options);
}