<?php
declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';
use VendisQr\Configuration;
use VendisQr\VendisQrClient;
$client = new VendisQrClient(Configuration::fromEnvironment());
$token = $client->login();
echo $token->value() . PHP_EOL;
