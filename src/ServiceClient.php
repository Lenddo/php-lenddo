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
		'score_service' => 'https://scoreservice.lenddo.com/',
		'network_service' => 'https://networkservice.lenddo.com/'
	);

	/**
	 * @deprecated - Naming has been updated to reflect actual purpose of the endpoint.
	 * 				please use applicationScore($application_id);
	 * @param $client_id
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function clientScore($client_id) {
		return $this->applicationScore($client_id, '');
	}

	/**
	 * @deprecated - Naming has been updated to reflect the actual purpose of the endpoint.
	 * 				please use applicationVerification($application_id);
	 * @param $client_id
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function clientVerification($client_id) {
		return $this->applicationVerification($client_id, '');
	}

	/**
	 * Calls the Lenddo Service with the provided client id to return a client verification result.
	 *
	 * @param string $application_id
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
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
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function applicationScore($application_id, $partner_script_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ClientScore/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}

	/**
	 * Calls the Lenddo Service with the provided client id to return a application multiple score result.
	 * - Used when a model is assigned to a partner script which outputs multiple scores.
	 *
	 * @param string $application_id
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function applicationMultipleScores($application_id, $partner_script_id)
	{
		return $this->_get($this->_hosts['score_service'], 'ApplicationMultipleScores/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}

	/**
	 * @param $application_id - This is your unique application id.
	 * @param $partner_script_id - This is the partner script ID that you created this application with.
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function applicationDecision($application_id, $partner_script_id) {
		return $this->_get($this->_hosts['score_service'], 'ApplicationDecision/' . $application_id, array(
			'partner_script_id' => $partner_script_id
		));
	}

	/**
	 * Submit additional data about an application to Lenddo.
	 *
	 * @param string $application_id
	 * @param string $partner_script_id
	 * @param array $extra_data
	 * @return \Lenddo\clients\guzzle_handlers\response\ResponseInterface
	 */
	public function extraApplicationData($application_id, $partner_script_id, array $extra_data) {
		return $this->_postJSON($this->_hosts['network_service'], 'ExtraApplicationData', array(
			"application_id" => $application_id,
			"partner_script_id" => $partner_script_id,
			"extra_data" => $extra_data
		));
	}
}