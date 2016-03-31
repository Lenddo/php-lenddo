<?php

namespace Lenddo\clients\exceptions;

/**
 * Class BadRequestException
 *
 * This error will be thrown when there is an invalid request parameter made.
 * Please reference the response body via $exception->getBody();
 *
 * @package Lenddo\clients\exceptions
 */
class BadRequestException extends HttpException {
	protected $message = 'BAD_REQUEST_EXCEPTION';
}