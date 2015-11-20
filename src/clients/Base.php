<?php

namespace Lenddo\clients;

/**
 * Class Base
 *
 * This class provides the base functionality for Lenddo clients to perform authentication and talk with
 *   Lenddo services.
 *
 * @package Lenddo\clients
 */
class Base
{
	protected $_api_app_id = '';
	protected $_api_secret = '';
	protected $_hosts = array();

	protected $_classes = array(
		'http_client' => '\GuzzleHttp\Client'
	);
	protected $_guzzle_request_options = array();

	public function __construct($api_app_id, $api_secret, $options = array())
	{
		$this->_api_app_id = $api_app_id;
		$this->_api_secret = $api_secret;

		if ($options) {
			$this->configure($options);
		}
	}

	/**
	 * @see {http://docs.guzzlephp.org/en/latest/request-options.html} for information on how to use guzzle options
	 * @param $options
	 *  api_endpoint: Lenddo's service endpoint to make the request against. This usually does not need to be changed.
	 *  guzzle_request_options: Passed to the request call for Guzzle. Please see the request-options link above.
	 * @return $this
	 */
	public function configure($options)
	{
		foreach ($options as $option_key => $option_value) {
			switch ($option_key) {
				case 'hosts':
					$this->_hosts = $option_value;
					break;
				case 'guzzle_request_options':
					$this->_guzzle_request_options = $option_value;
					break;
				case 'classes':
					$this->_classes = array_merge($this->_classes, $option_value);
					break;
			}
		}
		return $this;
	}

	/**
	 * @param $host String
	 * @param $path String
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function _get($host, $path)
	{
		return $this->_request('GET', $host, $path);
	}

	/**
	 * @param $host
	 * @param $path
	 * @param $body
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function _postJSON($host, $path, $body) {
		return $this->_request('POST', $host, $path, json_encode($body));
	}

	/**
	 * Returns the currently configured 'http_client' class. This is broken out so that it's
	 *  type can be clearly communicated to the IDE.
	 * @param array $config
	 * @return \GuzzleHttp\Client
	 */
	protected function _getHttpClient($config = array())
	{
		return new $this->_classes['http_client']($config);
	}

	/**
	 * Perform a Guzzle request. Currently only supports GET requests.
	 *
	 * @param $method
	 * @param $host
	 * @param $path
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function _request($method, $host, $path, $body = array())
	{
		//region Initiate the variables for this request
		$method = strtoupper($method);
		$path = '/' . $path;
		$headers = $this->getHeaders($method, $body, $path);
		$client = $this->_getHttpClient(array(
			"base_uri" => $host
		));
		//endregion

		// Make the API request to Lenddo
		return $client->request($method, $path, array_merge($this->_guzzle_request_options, array(
			"headers" => $headers
		)));
	}

	/**
	 * Method split out for static testing.
	 * @return string
	 */
	protected function _getDateTimestamp()
	{
		return $date = date('D M j G:i:s T Y');
	}

	/**
	 * Constructs a string used for authenticating with Lenddo services.
	 *
	 * @param $method
	 * @param $body
	 * @param $date
	 * @param $path
	 * @return string
	 */
	protected function _signRequest($method, $body, $date, $path)
	{
		$contentMd5 = $body ? md5($body) : NULL;
		$stringToSign = "{$method}\n{$contentMd5}\n{$date}\n{$path}";
		$string = 'LENDDO ' . $this->_api_app_id . ':';
		$string .= base64_encode(hash_hmac('sha1', $stringToSign, $this->_api_secret, TRUE));

		return $string;
	}

	/**
	 * @param $method
	 * @param $body
	 * @param $path
	 * @return array
	 */
	protected function getHeaders($method, $body, $path)
	{
		$date = $this->_getDateTimestamp();

		$headers = array(
			"Authorization" => $this->_signRequest($method, $body, $date, $path),
			"Content-Type" => "application/json",
			"Date" => $date,
			"Connection" => "close"
		);
		return $headers;
	}

	/**
	 * @return array
	 */
	public function getGuzzleRequestOptions()
	{
		return $this->_guzzle_request_options;
	}

	/**
	 * @return string
	 */
	public function getApiAppId()
	{
		return $this->_api_app_id;
	}

	/**
	 * @return string
	 */
	public function getApiSecret()
	{
		return $this->_api_secret;
	}

	/**
	 * @return array $this->_hosts
	 */
	public function getHosts()
	{
		return $this->_hosts;
	}
}