# Vendis QR SDK Usage
This package implements the Vendis dynamic QR payment API from the official Spanish documentation included in `.agents/skills/vendis-qr-expert/references/doc.md`.
## Client Setup
```php
<?php
use VendisQr\Configuration;
use VendisQr\VendisQrClient;
$client = new VendisQrClient(Configuration::fromEnvironment());
```
The default HTTP implementation uses Guzzle. For tests or advanced Laravel apps, inject any class implementing `VendisQr\Http\TransportInterface`.
## Login
```php
<?php
$token = $client->login();
```
Persist this value securely. The official documentation says the token is valid for one year, so production systems should refresh it on a schedule instead of logging in before every request.
## Generate QR
```php
<?php
use VendisQr\Requests\GenerateQrRequest;
$qr = $client->generateQr(new GenerateQrRequest(17, 100.00, true, true, new DateTimeImmutable('2026-04-15 23:59:00'), 'Pago QR CUSTOM'));
```
The response exposes `image()`, `url()`, and `id()`.
## Get QR Status
```php
<?php
$status = $client->getQrStatus(816269745);
foreach ($status->payments() as $payment) {
    $payment->amount();
}
```
Known statuses are represented by `VendisQr\Enums\QrStatus`: `Pending`, `Cancelled`, `Paid`, and `Failed`.
## Webhooks
Vendis sends a callback after payment. The callback Authorization header is optional in the official documentation. When Vendis sends it, validate the bearer value against the same yearly access token used to create and inspect QR codes.
```php
<?php
use VendisQr\Webhooks\CallbackPayload;
use VendisQr\Webhooks\CallbackResponse;
use VendisQr\Webhooks\CallbackValidator;
$configuration = Configuration::fromEnvironment();
if (!CallbackValidator::isValid($_SERVER['HTTP_AUTHORIZATION'] ?? null, $configuration->accessToken())) {
    return CallbackResponse::error('Unauthorized');
}
$payload = new CallbackPayload($request->all());
return CallbackResponse::success();
```
## Environment Variables
Use `VENDIR_QR_*` for all package configuration:
1. `VENDIR_QR_BASE_URL`
2. `VENDIR_QR_EMAIL`
3. `VENDIR_QR_PASSWORD`
4. `VENDIR_QR_TOKEN_NAME`
5. `VENDIR_QR_ACCESS_TOKEN`
6. `VENDIR_QR_TIMEOUT`
