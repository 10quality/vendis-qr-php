<?php
declare(strict_types=1);
require __DIR__ . '/../../vendor/autoload.php';
use VendisQr\Configuration;
use VendisQr\Requests\GenerateQrRequest;
use VendisQr\VendisQrClient;
$client = new VendisQrClient(Configuration::fromEnvironment());
$qr = $client->generateQr(new GenerateQrRequest(17, 250.00, true, true, new DateTimeImmutable('+1 day'), 'Pago QR CUSTOM'));
echo 'QR ID: ' . $qr->id() . PHP_EOL;
echo 'QR URL: ' . $qr->url() . PHP_EOL;
