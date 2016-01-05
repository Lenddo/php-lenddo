<?php

namespace Lenddo\Exceptions;

use Exception;

/**
 * Class AuthenticationFailure
 *
 * This is thrown as a result of the Webhook request failure.
 *
 * This exception should be treated as one of two scenarios:
 * 	1. The configuration has not been setup properly.
 * 	2. The request is fraudulent and should be ignored.
 *
 * Please contact Lenddo should there be doubt when receiving this Exception.
 *
 * @package Lenddo\exceptions
 */
class AuthorizationException extends \Exception {
	/**
	 * AuthenticationFailure constructor.
	 *
	 * Receives a hawk exception as a constructor argument and reconfigures this exception.
	 *
	 * @param \Dflydev\Hawk\Server\UnauthorizedException $hawkException
	 */
	public function __construct($hawkException)
	{
		$exception_class = end(explode('\\', get_class($hawkException)));
		$message = $exception_class . ': ' . $hawkException->getMessage();
		$code = $hawkException->getCode();

		parent::__construct($message, $code);
	}

}