<?php
require('../../../vendor/autoload.php');
use Volc\Service\Visual;

$client = Visual::getInstance();
// call below method if you dont set ak and sk in ～/.volc/config

$client->setAccessKey($ak);
$client->setSecretKey($sk);

echo "\nDemo EnhancePhoto\n";
$response = $client->EnhancePhoto(['form_params' => ['image_base64' => '']]);
echo $response;