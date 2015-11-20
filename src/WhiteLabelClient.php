<?php

namespace Lenddo;

use Lenddo\clients\Base;

/**
 * Class ServiceClient
 *
 * This class is used for providing necessary data for scoring a member without the user seeing "Lenddo" anywhere.
 *
 * @package Lenddo
 */
class WhiteLabelClient extends Base
{
	/**
	 * @var array Only these network names may be specified when passing "$provider" to the PartnerToken method.
	 */
	protected $_valid_token_providers = array('Facebook', 'LinkedIn', 'Yahoo', 'WindowsLive', 'Google');

	protected $_hosts = array(
		'network_service' => 'https://networkservice.lenddo.com/'
	);

	/**
	 * Posting network tokens, if successful, returns a "Profile ID" which is used when submitting a client for scoring.
	 *
	 * @param $provider
	 * @param $oauth_key
	 * @param $oauth_secret
	 * @param array $token_data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function partnerToken($provider, $oauth_key, $oauth_secret, $token_data = array()) {
		if(!in_array($provider, $this->_valid_token_providers)) {
			$valid_token_providers = join(', ', $this->_valid_token_providers);
			throw new \InvalidArgumentException('$provider must be one of the following: ' . $valid_token_providers);
		}

		return $this->_postJSON($this->_hosts['network_service'], 'PartnerToken', array(
			'token_data' => array_merge( array(
				'key' => $oauth_key,
				'secret' => $oauth_secret
			), $token_data ),
			'provider' => $provider
		));
	}

	/**
	 * Submit an application with profile ids for scoring to Lenddo.
	 *
	 * To perform this step you must have an array of at least one profile id obtained from the PartnerToken call.
	 *
	 * @param $partner_script_id - The partner script ID is defined in your partner's dashboard. This is necessary for
	 *   defining how data is displayed in the dashboard as well as returning webhooks and /or e-mail notifications.
	 * @param $client_id - This is a single use ID which acts as a transaction ID. It is used for later referencing the
	 *   scoring results.
	 * @param $profile_ids - This is an array of ID's which were obtained from the PartnerToken service call from the
	 *   WhiteLabelClient class.
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function commitPartnerJob($partner_script_id, $client_id, $profile_ids) {
		if(count($profile_ids) === 0) {
			throw new \InvalidArgumentException('$profile_ids must contain at least one entry.');
		}

		return $this->_postJSON($this->_hosts['network_service'], 'CommitPartnerJob', array(
			'client_id' => $client_id,
			'profile_ids' => $profile_ids,
			'partner_script_id' => $partner_script_id
		));
	}
}