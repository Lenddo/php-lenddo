<?php

namespace Lenddo\clients\guzzle_handlers;

class HandlerResolver {
	/**
	 * This method will resolve the handler we need to use for the installed version of Guzzle.
	 *
	 * @return string|HandlerInterface
	 * @throws \Exception
	 */
	static public function resolve() {
		if (class_exists('\Guzzle\Http\Client')) {
			return 'Lenddo\clients\guzzle_handlers\GuzzleV3Handler';
		}

		if (class_exists('\GuzzleHttp\Client')) {
			return static::_resolveGuzzleV4_V6();
		}
	}

	/**
	 * Resolves based on the definition of the \GuzzleHttp\Client class
	 */
	static protected function _resolveGuzzleV4_V6() {
		if (method_exists('\GuzzleHttp\Client', 'createRequest')) {
			// Guzzle v4 and v5 use this method
			// Currently Lenddo's SDK does not need to differentiate between the functionality in
			//	- Guzzle v4 and v5
			return 'Lenddo\clients\guzzle_handlers\GuzzleV4Handler';
		} else if (method_exists('\GuzzleHttp\Client', 'request')) {
			// Guzzle v6 uses a 'request' method instead of a 'createRequest' method.
			return 'Lenddo\clients\guzzle_handlers\GuzzleV6Handler';
		}

		throw new \Exception('Could not determine Guzzle Version!');
	}
}