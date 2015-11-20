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
	protected $_valid_token_providers = array('Facebook', 'LinkedIn', 'Yahoo', 'WindowsLive');

	protected $_hosts = array(
		'network_service' => 'https://networkservice.lenddo.com/'
	);

	/**
	 * @param $provider
	 * @param $oauth_key
	 * @param $oauth_secret
	 * @param array $token_data
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function PartnerToken($provider, $oauth_key, $oauth_secret, $token_data = array()) {
		if(!in_array($provider, $this->_valid_token_providers)) {
			$valid_token_providers = join(', ', $this->_valid_token_providers);
			throw new \InvalidArgumentException('$provider must be one of the following: ' . $valid_token_providers);
		}

		return $this->_postJSON($this->_hosts['network_service'], '/PartnerToken', array(
			'token_data' => array_merge( array(
				'key' => $oauth_key,
				'secret' => $oauth_secret
			), $token_data ),
			'provider' => $provider
		));
	}

	public function CommitPartnerJob() {

	}
}