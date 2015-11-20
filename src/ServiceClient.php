<?php

namespace Lenddo;

use Lenddo\clients\Base;

/**
 * Class ServiceClient
 *
 * This class is used for retrieving scores and verifications from Lenddo.
 *
 * @package Lenddo
 */
class ServiceClient extends Base
{
	protected $_hosts = array(
		'score_service' => 'https://scoreservice.lenddo.com/'
	);

	/**
	 * Calls the Lenddo Service with the provided client id to return a client verification result.
	 *
	 * @param string $client_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function clientVerification($client_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ClientVerification/' . $client_id);
	}

	/**
	 * Calls the Lenddo Service with the provided client id to return a client score result.
	 *
	 * @param string $client_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function clientScore($client_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ClientScore/' . $client_id);
	}
}