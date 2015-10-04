<?php

namespace Lenddo;

use GuzzleHttp;

class ServiceClient
{
    protected $_api_app_id = '';
    protected $_api_secret = '';
    protected $_api_endpoint = 'https://scoreservice.lenddo.com/';
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
                case 'api_endpoint':
                    $this->_api_endpoint = $option_value;
                    break;
                case 'guzzle_request_options':
                    $this->_guzzle_request_options = $option_value;
                    break;
            }
        }
        return $this;
    }

    /**
     * Calls the Lenddo Service with the provided client id to return a client verification result.
     *
     * @param string $client_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function clientVerification($client_id)
    {
        return $this->_get('ClientVerification/' . $client_id);
    }

    /**
     * Calls the Lenddo Service with the provided client id to return a client score result.
     *
     * @param string $client_id
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function clientScore($client_id)
    {
        return $this->_get('ClientScore/' . $client_id);
    }


    /**
     * @param $path
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function _get($path) {
        return $this->_request('GET', $path);
    }

    /**
     * Perform a Guzzle request. Currently only supports GET requests.
     *
     * @param $method
     * @param $path
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function _request($method, $path)
    {
        //region Initiate the variables for this request
        $method = strtoupper( $method );
        $path = '/' . $path;
        $date = date('D M j G:i:s T Y');
        $headers = array(
            "Authorization" => $this->_signRequest($method, null, $date, $path),
            "Content-Type" => "application/json",
            "Date" => $date,
            "Connection" => "close"
        );
        $client = new GuzzleHttp\Client( array(
            "base_uri" => $this->_api_endpoint
        ) );
        //endregion

        // Make the API request to Lenddo
        return $client->request( $method, $path, array_merge( $this->_guzzle_request_options, array(
            "headers" => $headers
        ) ) );
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
}