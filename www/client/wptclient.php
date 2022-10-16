<?php
/**
 * @package     wptclient
 *
 * @author      Pieter-Jan de Vries/Obix webtechniek <pieter@obix.nl>
 * @copyright   Copyright (C) 2022+ Obix webtechniek. All rights reserved.
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.obix.nl
 */

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

// https://wpt.obixhosting.nl
//  /runtest.php
//  ?url=https://doevetest-i.obixconcept.nl
//	&label=API%20test%204
//	&runs=3
//	&fvonly=0
//	&login=gtmetrix
//	&password=my%20Empire%20Excretes%20the%20Sign
//	&authType=0
//	&video=1
//	&lighthouse=1
//	&private=0
//	&f=json

$baseUri = 'https://wpt.obixhosting.nl';
$wptParams = [
	'url' => 'https://doevetest-i.obixconcept.nl',
	'label' => urlencode('API cli client test'),
	'runs' => '3',            // The number of test runs (1-10 on the public instance).
	'fvonly' => '0',          // 1: skip the Repeat View test; 0: run a test against both the first view and the repeat view for a given test.
	'login' => 'gtmetrix',
	'password' => urlencode('my Empire Excretes the Sign'),
	'authType' => '0',        // 0: Basic Authentication; 1: SNS.
	'video' => '1',           // 0: to not capture video; 1 to capture video. Video is required for calculating Speed Index as well as providing the filmstrip view.
	'lighthouse' => '1',      // 0: no Lighthous test; 1:  have a lighthouse test performed (Chrome-only, wptagent agents only)
	'private' => '0',         // 0: make the test visible in the public history log; 1: make the test private.
	'f' => 'json',          // The format to return: "xml" or "json"; default is a redirect.
	'pingback' => 'https://wpt.obixhosting.nl/client/wptpingback.php'     // URL to ping when the test is complete. The test ID will be passed as an "id" parameter.
];
$reqParams = [
	'auth' => ['poortwachter', 'A Taco extrudes the Pullover']
];

$client = new Client([
	// Base URI is used with relative requests
	'base_uri' => $baseUri,
	'timeout'  => 2.0,
]);

$requestUri = 'runtest.php?' . implode('&',
		array_map(fn(string $key, string $value) => $key . '=' . $value, array_keys($wptParams), array_values($wptParams)));
$request = new Request('GET', $requestUri);
$response = $client->send($request, $reqParams);

$responseStatusCode = $response->getStatusCode();

if ($responseStatusCode >= 300)
{
	printf("%d: %s\n", $responseStatusCode, $response->getReasonPhrase());

	exit(1);
}

$responseData = json_decode($response->getBody(), true);
$testStatusCode = $responseData['statusCode'];
$testStatusText = $responseData['statusText'];

if ($testStatusCode >= 300)
{
	printf("%d: %s\n", $testStatusCode, $testStatusText);

	exit(2);
}

$testId = $responseData['data']['testId'];
$requestUri = 'testStatus.php?test=' . $testId;
$request = new Request('GET', $requestUri);
$response = $client->send($request);

$responseStatusCode = $response->getStatusCode();

if ($responseStatusCode >= 300)
{
	printf("%d: %s\n", $responseStatusCode, $response->getReasonPhrase());

	exit(3);
}

$responseData = json_decode($response->getBody(), true);
$testStatusStatusCode = $responseData['data']['statusCode'];
$testStatusStatusText = $responseData['data']['statusText'];
$testId = $responseData['data']['id'];

match (true) {
	$testStatusStatusCode >= 100 && $testStatusStatusCode < 200 => printf("%s: Busy.\n", $testId),
	$testStatusStatusCode >= 200 && $testStatusStatusCode < 300 => printf("%s: Finished.\n", $testId),
	default => printf("%d: %s\n", $testStatusStatusCode, $testStatusStatusText)
};