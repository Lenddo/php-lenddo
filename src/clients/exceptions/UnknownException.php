<?php

namespace Lenddo\clients\exceptions;

/**
 * Class UnknownException
 * @package Lenddo\clients\guzzle_handlers\exceptions
 */
class UnknownException extends HttpException {
	protected $message = 'UNKNOWN_EXCEPTION';
}