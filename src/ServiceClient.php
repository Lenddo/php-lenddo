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
	 * @deprecated - Naming has been updated to reflect actual purpose of the endpoint.
	 * 				please use applicationScore($application_id);
	 * @param $client_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function clientScore($client_id) {
		return $this->applicationScore($client_id, '');
	}

	/**
	 * @deprecated - Naming has been updated to reflect the actual purpose of the endpoint.
	 * 				please use applicationVerification($application_id);
	 * @param $client_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function clientVerification($client_id) {
		return $this->applicationVerification($client_id, '');
	}

	/**
	 * Calls the Lenddo Service with the provided client id to return a client verification result.
	 *
	 * @param string $application_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function applicationVerification($application_id, $partner_script_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ClientVerification/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}

	/**
	 * Calls the Lenddo Service with the provided client id to return a client score result.
	 *
	 * @param string $application_id
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function applicationScore($application_id, $partner_script_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ClientScore/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}

	/**
	 * @param $application_id - This is your unique application id.
	 * @param $partner_script_id - This is the partner script ID that you created this application with.
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function applicationDecision($application_id, $partner_script_id) {
		return $this->_get($this->_hosts['score_service'], 'ApplicationDecision/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}
}