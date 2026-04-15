<?php
declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';
use VendisQr\Configuration;
use VendisQr\VendisQrClient;
$client = new VendisQrClient(Configuration::fromEnvironment());
$status = $client->getQrStatus($argv[1] ?? '');
echo 'Status: ' . $status->rawStatus() . PHP_EOL;
foreach ($status->payments() as $payment) {
    echo $payment->date() . ' ' . $payment->amount() . ' ' . $payment->name() . PHP_EOL;
}
