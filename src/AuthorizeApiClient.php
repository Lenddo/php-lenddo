<?php

namespace Lenddo;

use Lenddo\clients\Base;

/**
 * Class AuthorizeApiClient
 *
 * Used for communicating with the Authorize API. These communications usually have an impact on the product output or
 *   user experience.
 *
 * @package Lenddo
 */
class AuthorizeApiClient extends Base
{	protected $_hosts = array(
		'authorize_api' => 'https://authorize-api.partner-service.link/'
	);

	/**
	 * When enabled on a partner script a user will not be processed until priority data comes in. This data may come in
	 *   any time before or after a user enters the Authorize Onboarding process.
	 *
	 * @param $partner_script_id
	 * @param $application_id
	 * @param $extra_data
	 * @param Verification $verification
	 */
	public function priorityData($partner_script_id, $application_id, $extra_data, Verification $verification = null) {
		if (!is_null($extra_data) && !is_array($extra_data)) {
			throw new \InvalidArgumentException('$extra_data must either be null or an array');;
		}

		return $this->_postJSON($this->_hosts['authorize_api'], 'onboarding/prioritydata', array(
			'partner_script_id' => $partner_script_id,
			'application_id' => $application_id,
			'data' => array_filter(array(
				'verification_data' => $verification ? $verification->export() : array(),
				'partner_data' => $extra_data
			))
		));
	}
}