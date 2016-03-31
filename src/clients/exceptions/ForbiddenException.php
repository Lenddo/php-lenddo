<?php

namespace Lenddo\clients\exceptions;

/**
 * Class ForbiddenException
 *
 * This exception will occur when the credentials are not correct. Please refer to both the
 * documentation and the partners dashboard @ https://partners.lenddo.com to get your credentials.
 *
 * If you continue to have problems please contact your account manager.
 *
 * @package Lenddo\clients\guzzle_handlers\exceptions
 */
class ForbiddenException extends HttpException {
	protected $message = 'FORBIDDEN_EXCEPTION';
}