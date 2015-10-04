<?php

namespace Lenddo;

class ServiceClient
{
    protected $_classes = array(
        'http_client' => '\GuzzleHttp\Client'
    );

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
                case 'classes':
                    $this->_classes = array_merge( $this->_classes, $option_value );
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
        $date = $this->_get_date_timestamp();

        $headers = array(
            "Authorization" => $this->_signRequest($method, null, $date, $path),
            "Content-Type" => "application/json",
            "Date" => $date,
            "Connection" => "close"
        );

        // Instantiate the class defined in the classes property above.
        $client = new $this->_classes['http_client']( array(
            "base_uri" => $this->_api_endpoint
        ) );
        //endregion

        // Make the API request to Lenddo
        return $client->request( $method, $path, array_merge( $this->_guzzle_request_options, array(
            "headers" => $headers
        ) ) );
    }

    /**
     * Method split out for static testing.
     * @return string
     */
    protected function _get_date_timestamp() {
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
     * @return array
     */
    public function getGuzzleRequestOptions()
    {
        return $this->_guzzle_request_options;
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->_api_endpoint;
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
}