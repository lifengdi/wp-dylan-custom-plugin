// Code generated by protoc-gen-volcengine-sdk
// source: createVQScoreTask
// DO NOT EDIT!
<?php
require('../../vendor/autoload.php');

$client = Volc\Service\Live\Live::getInstance();
$client->setAccessKey('');
$client->setSecretKey('');

$request = new Volc\Service\Live\Models\Request\CreateVQScoreTaskRequest();
$request->setFrameInterval(0);
$request->setDuration(0);
$request->setMainAddr("");
$request->setContrastAddr("");
$request->setAlgorithm("");

$response = new Volc\Service\Live\Models\Response\CreateVQScoreTaskResponse();
try {
    $response = $client->createVQScoreTask($request);
} catch (Exception $e) {
    echo $e, "\n";
} catch (Throwable $e) {
    echo $e, "\n";
}
if ($response != null && $response->getResponseMetadata() != null && $response->getResponseMetadata()->getError() != null) {
    echo $response->getResponseMetadata()->getError()->serializeToJsonString(), "\n";
} else {
    echo $response->serializeToJsonString(), "\n";
}
