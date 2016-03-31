<?php

namespace Lenddo\clients\exceptions;

/**
 * Class InternalErrorException
 * @package Lenddo\clients\guzzle_handlers\exceptions
 */
class InternalErrorException extends HttpException {
	protected $message = 'INTERNAL_ERROR';
}