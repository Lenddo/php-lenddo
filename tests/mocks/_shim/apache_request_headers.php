<?php

class ApacheRequestHeadersConfig {
	public static $return_data = null;
}

function apache_request_headers() {
	return ApacheRequestHeadersConfig::$return_data;
};