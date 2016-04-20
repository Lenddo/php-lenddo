<?php

require(__DIR__ . '/../vendor/autoload.php');

use Lenddo\util\TestWebhook;

// Parse all command parameters to the $_GET array
parse_str(implode('&', array_slice($argv, 1)), $_GET);

//region Setup / Input Checking
$missing_keys = array();

foreach (array('uri', 'type', 'hash_key', 'partner_script_id') as $required_key) {
	if (empty($_GET[$required_key])) {
		$missing_keys[] = $required_key;
	}
}

if ($missing_keys) {
	throw new Exception('Missing required keys: ' . implode(', ', $missing_keys));
}

$request = new TestWebhook($_GET['type'], $_GET['hash_key'], $_GET['partner_script_id']);
$request_options = $request->buildRequest($_GET['uri']);

$response_body = $request->request($request_options);
if ($response_body === true) {
	echo "Webhook successfully ran!";
	exit;
}

echo "Webhook failed with the following body:\r\n";
echo $response_body;