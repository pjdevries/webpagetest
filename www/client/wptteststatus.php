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

if ($argc < 2)
{
	printf("Missing test id\n");

	exit(1);
}

$baseUri = 'https://wpt.obixhosting.nl';
$reqParams = [
	'auth' => ['poortwachter', 'A Taco extrudes the Pullover']
];

$client = new Client([
	// Base URI is used with relative requests
	'base_uri' => $baseUri,
	'timeout'  => 2.0,
]);

$testId = $argv[1];
$requestUri = 'testStatus.php?test=' . $testId;
$request = new Request('GET', $requestUri);
$response = $client->send($request, $reqParams);

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