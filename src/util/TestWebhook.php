<?php

namespace Lenddo\util;

use Dflydev\Hawk\Client\ClientBuilder as HawkClientBuilder;
use Dflydev\Hawk\Credentials\Credentials as HawkCredentials;
use Exception;
use Lenddo\clients\guzzle_handlers\HandlerResolver;

class TestWebhook {
	protected $_payload_type;

	protected $_hash_key;
	protected $_partner_script_id;

	public function __construct($payload_type, $hash_key, $partner_script_id)
	{
		$this->_payload_type = $payload_type;
		//endregion

		$this->_hash_key = $hash_key;
		$this->_partner_script_id = $partner_script_id;
	}

	public function buildRequest($uri) {
		$headers = array(
			'Content-type' => 'application/x-www-form-urlencoded'
		);

		// get the payload / json encode it
		$payload = http_build_query($this->_getPayload());
		$hawk = $this->_configureHawk($payload, $uri);
		// attach the authorization header for hawk
		$headers[ $hawk->header()->fieldName() ] = $hawk->header()->fieldValue();

		return array(
			'uri' => $uri,
			'headers' => $headers,
			'payload' => $payload
		);
	}

	/**
	 * @param $request_options
	 * @return bool|string - true for success, string/body for failure
	 */
	public function request($request_options) {
		$guzzle_class = HandlerResolver::resolve();

		$guzzle = new $guzzle_class( $request_options['uri'] );
		
		$payload = $request_options['payload'];
		$headers = $request_options['headers'];

		$raw_body = $guzzle->request( 'POST', '', '', $headers, $payload, array())->getBody(false);

		if (preg_match('/webhook accepted/', $raw_body)) {
			return true;
		}
		return $raw_body;
	}

	protected function _getPayload() {
		$payloads = array(
			'scoring_complete_no_flags' => array(
				'_event' => 'scoring_complete',
				'score' => 687,
				'flags' => array()
			),
			'scoring_complete_flags' => array(
				'_event' => 'scoring_complete',
				'score' => 300,
				'flags' => array( 'NM01', 'UN01', 'EM01' )
			),
			'verification_complete' => array(
				'_event' => 'verification_complete',
				'partner_script_id' => $this->_partner_script_id,
				'updated' => time(),
				'created' => time(),
				'facebook_photo_url' => "https://graph.facebook.com/{{FB_ID}}/picture?type=large",
				'verified_by_facebook' => true,
				'flags' => array(
					'EM02',
					'NM01'
				),
				'verifications' => array(
					'name' => true,
					'university' => false,
					'employer' => true,
					'facebook_verified' => true,
					'birthday' => true,
					'top_employer' => true,
					'phone' => true
				),
				'probes' => array(
					'name' => array(
						'first', 'middle', 'last'
					),
					'university' => array(
						'university' => 'un'
					),
					'employer' => array(
						'employer' => 'emp'
					),
					'facebook_verified' => array( 'fbid' ),
					'birthday' => array(
						1988, 5, 4
					),
					'top_employer' => null,
					'phone' => 'upn'
				)
			),
			'application_decision_complete_approved' => array(
				'_event' => 'application_decision_complete',
				'decision' => 'APPROVE',
				'flags' => array()
			),
			'application_decision_complete_denied' => array(
				'_event' => 'application_decision_complete',
				'decision' => 'DENY',
				'flags' => array()
			),
			'application_decision_complete_no_decision' => array(
				'_event' => 'application_decision_complete',
				'decision' => 'NO_DECISION',
				'flags' => array(
					'INSUFFICIENT_ACTIVITY',
					'MISSING_REQUIRED_DATA'
				)
			)
		);

		if (empty($payloads[$this->_payload_type])) {
			throw new Exception('no payload for type: ' . $this->_payload_type);
		}

		$payload = $payloads[$this->_payload_type];
		$event = $payload['_event'];
		unset($payload['_event']);

		return array(
			'client_id' => '123',
			'event' => $event,
			'result' => $payload
		);
	}

	/**
	 * @return \Dflydev\Hawk\Client\Request
	 */
	protected function _configureHawk($payload, $uri) {
		$credentials = new HawkCredentials( $this->_hash_key, 'sha256', $this->_partner_script_id );
		$client = HawkClientBuilder::create()->build();

		return $client->createRequest($credentials, $uri, 'POST', array(
			'payload' => $payload,
			'content_type' => 'text/json'
		));
	}
}