<?php

namespace Lenddo\clients\exceptions;

use Lenddo\clients\guzzle_handlers\response\ResponseInterface;

class ExceptionRouter {
	public static function routeException(\Exception $original, ResponseInterface $response) {
		$status_code = (int) $response->getStatusCode();

		switch($status_code) {
			case 400:
				return new BadRequestException($original, $response);
			case 403:
				return new ForbiddenException($original, $response);
			case 404:
				return new NotFoundException($original, $response);
			case 500:
				return new InternalErrorException($original, $response);
			default:
				return new UnknownException($original, $response);
		}
	}
}