<?php

namespace Lenddo;

use Dflydev\Hawk\Credentials\Credentials as HawkCredentials;
use Dflydev\Hawk\Server\ServerBuilder as HawkServerBuilder;
use Lenddo\exceptions\AuthorizationException;
use Lenddo\exceptions\ReferenceException;

class WebhookAuthentication {
	const HTTP_REQUEST_METHOD = 'POST';
	const CONTENT_TYPE = 'application/x-www-form-urlencoded';
	const HASH_METHOD = 'sha256';

	protected $_hawk_server;
	protected $_host;
	protected $_port = 443;
	protected $_request_path;

	public function __construct($webhook_key, $partner_script_id, $options = array() )
	{
		// Configure this class.
		$this->_config($options, TRUE);
		// Get the credentials provider, callable function
		$credentialsProvider = static::_credentialsProvider($webhook_key, $partner_script_id);
		// Build the Hawk Server
		$this->_hawk_server = HawkServerBuilder::create($credentialsProvider)->build();
	}

	protected static function _credentialsProvider( $webhook_key, $partner_id ) {
		$hash_method = static::HASH_METHOD;
		return function() use ( $webhook_key, $partner_id, $hash_method ) {
			return new HawkCredentials( $webhook_key, $hash_method, $partner_id );
		};
	}

	//region Instantiation Configuration, Getters, Setters.
	/**
	 * Utility method for taking in configuration values, if any and merging them with existing configuration values.
	 *
	 * If `$write` is set to TRUE - instantiated config values will be permanently updated for future calls.
	 *
	 * Utilizes the getter and setter methods to define configurations
	 *
	 * @param $options
	 * @param bool $write
	 * @return array
	 */
	protected function _config($options = array(), $write = FALSE) {
		$config_option_keys = array('Host', 'Port', 'RequestPath');
		$config = array();

		foreach($config_option_keys as $config_option_key) {
			// Retrieve the current value to the config array.
			$config[$config_option_key] = $this->{"get" . $config_option_key}();

			if (empty($options[$config_option_key]) || !is_scalar($options[$config_option_key])) {
				// These configuration options do not include this key.
				continue;
			}

			// Assign this config value to the output config. Essentially merging configurations if applicable.
			$config[$config_option_key] = $options[$config_option_key];

			if( $write ) {
				// we should update the configured value.
				$this->{"set" . $config_option_key}($options[$config_option_key]);
			}
		}

		return $config;
	}

	//region Getters
	/**
	 * The current host (domain name) for the request including subdomains.
	 *
	 * If not configured it will attempt to inspect the existing request via `$_SERVER['HTTP_HOST']`
	 *
	 * Example: "authorize.lenddo.com"
	 * 			"www.lenddo.com"
	 * @return string
	 */
	public function getHost()
	{
		$host = $this->_host;

		if( !$host ) {
			$host = strtok($_SERVER['HTTP_HOST'], ':');
		}

		return $host;
	}

	/**
	 * Does *NOT* try to inspect the current request via `$_SERVER['SERVER_PORT']`.
	 * Documentation for this feature specifies that it is not safe to rely on for security-dependent contexts.
	 *
	 * Default value for `$this->_port` is 80.
	 *
	 * @see http://php.net/manual/en/reserved.variables.server.php
	 * @return int
	 */
	public function getPort()
	{
		return $this->_port;
	}

	/**
	 * The current url path including the query string.
	 *
	 * If nothing has been configured, will try to inspect the current request via `$_SERVER['REQUEST_URI']`.
	 *
	 * Example: "/path/to/webhook_receiver?foo=bar"
	 *
	 * @return string
	 */
	public function getRequestPath()
	{
		$request_path = $this->_request_path;

		if( !$request_path ) {
			$request_path = $_SERVER['REQUEST_URI'];
		}

		return $request_path;
	}
	//endregion

	//region Setters
	/**
	 * @param mixed $host
	 * @return WebhookAuthentication
	 */
	public function setHost($host)
	{
		$this->_host = $host;
		return $this;
	}

	/**
	 * @param int $port
	 * @return WebhookAuthentication
	 */
	public function setPort($port)
	{
		$this->_port = $port;
		return $this;
	}

	/**
	 * @param mixed $request_path
	 * @return WebhookAuthentication
	 */
	public function setRequestPath($request_path)
	{
		$this->_request_path = $request_path;
		return $this;
	}
	//endregion
	//endregion

	protected function _get_authorization_header() {
		// Attempt to retrieve the header via apache_request_headers
		if(function_exists('apache_request_headers')) {
			$headers = apache_request_headers();

			if(!empty($headers['Authorization'])) {
				// Header found, no need to continue.
				return $headers['Authorization'];
			}
		}

		// No header found or `apache_request_headers` doesn't exist.
		$headers = array();
		foreach($_SERVER as $key => $value) {
			if (substr($key, 0, 5) <> 'HTTP_') {
				continue;
			}
			$header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
			$headers[$header] = $value;
		}

		if(empty($headers['Authorization'])) {
			throw new ReferenceException('Could not find Authorization Header!');
		}

		return $headers['Authorization'];
	}

	/**
	 * @param null $authorization_header
	 * @param array $options
	 * @return bool
	 * @throws AuthorizationException
	 * @throws ReferenceException
	 * @return array - The request payload parsed out should be available here.
	 */
	public function authenticateRequest($authorization_header = null, $options = array()) {
		$config = $this->_config($options);

		if( !$authorization_header ) {
			$authorization_header = $this->_get_authorization_header();
		}

		try {
			$this->_hawk_server->authenticate(
				static::HTTP_REQUEST_METHOD,
				$config['Host'],
				$config['Port'],
				$config['RequestPath'],
				static::CONTENT_TYPE,
				null, // @todo: payload validation is currently disabled.
				$authorization_header
			);
		} catch(\Exception $e) {
			throw new AuthorizationException($e);
		}

		// Authentication went well! Parse the body and return an array.
		return TRUE;
	}

	/**
	 * After authenticating a request and acting on the data passed from Lenddo to the webhook endpoint
	 * 	call this method.
	 */
	public function webhookAccepted() {
		echo 'PHPSDK: webhook accepted';
	}
}