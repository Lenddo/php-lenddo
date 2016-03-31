<?php

namespace Lenddo\clients\guzzle_handlers;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use Lenddo\clients\exceptions\ExceptionRouter;
use Lenddo\clients\guzzle_handlers\response\V3Response as Response;

class GuzzleV3Handler implements HandlerInterface {
	protected $_base_uri;

	public function __construct($base_uri)
	{
		$this->_base_uri = $base_uri;
	}

	protected function __setCaRootBundleOnGuzzleOptions($guzzle_options) {
		if (isset($guzzle_options['verify'])) {
			// Something else has already defined the verify value.
			return $guzzle_options;
		}

		$guzzle_options['verify'] = __DIR__ . '/ca-bundle.crt';
		return $guzzle_options;
	}

	public function request($method, $path, $query, $headers, $body, $guzzle_options) {
		$guzzle_client = new GuzzleClient($this->_base_uri);

		if ($query) {
			$path .= '?' . http_build_query($query);
		}

		// Attach the bundle for machines without the appropriate understanding of SSL Certificates.
		$guzzle_options = $this->__setCaRootBundleOnGuzzleOptions($guzzle_options);

		$request = $guzzle_client->createRequest($method, $path, $headers, $body, $guzzle_options);

		try {
			// Send the request
			return new Response($request, $request->send());
		} catch (RequestException $e) {
			// Catch Request Exceptions and wrap them in friendlier exceptions
			$response = new Response($request, $e->getResponse());
			throw ExceptionRouter::routeException($e, $response);
		}
	}
}