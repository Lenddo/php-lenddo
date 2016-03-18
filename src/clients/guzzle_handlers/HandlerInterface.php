<?php

namespace Lenddo\clients\guzzle_handlers;

interface HandlerInterface {
	public function __construct($base_uri);
	public function request($method, $path, $query, $headers, $body, $guzzle_options);
}