<?php

namespace Lenddo\clients\exceptions;

/**
 * Class NotFoundException
 * @package Lenddo\clients\guzzle_handlers\exceptions
 */
class NotFoundException extends HttpException {
	protected $message = 'NOT_FOUND';
}