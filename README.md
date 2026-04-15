# Vendis QR PHP
Typed PHP 8.2 SDK for the Vendis dynamic QR payment REST API.
## Installation
```bash
composer require 10quality/vendis-qr-php
```
## Configuration
The SDK reads `VENDIR_QR_*` environment variables, matching the requested project prefix.
```dotenv
VENDIR_QR_ENVIRONMENT=sandbox
VENDIR_QR_BASE_URL=https://your-vendis-base-url.example
VENDIR_QR_EMAIL=vendisqr@example.com
VENDIR_QR_PASSWORD=secret
VENDIR_QR_TOKEN_NAME="Laravel App"
VENDIR_QR_ACCESS_TOKEN="yearly-token-from-login"
VENDIR_QR_TIMEOUT=30
VENDIR_QR_WEBHOOK_TOKEN="optional-callback-token"
```
Vendis provides account-specific sandbox and production base URLs. Keep both URLs in framework configuration and select them with `VENDIR_QR_ENVIRONMENT`.
## Usage
```php
<?php
use VendisQr\Configuration;
use VendisQr\Requests\GenerateQrRequest;
use VendisQr\VendisQrClient;
$client = new VendisQrClient(Configuration::fromEnvironment());
$token = $client->login();
$client = new VendisQrClient(Configuration::fromEnvironment()->withAccessToken($token->value()));
$qr = $client->generateQr(new GenerateQrRequest(17, 250.00, true, false, new DateTimeImmutable('2026-04-15 23:59:00'), 'Pago QR CUSTOM'));
$status = $client->getQrStatus($qr->id());
```
## Yearly Access Token Best Practices
The Vendis token is valid for one year. In Laravel, store it encrypted in a durable store such as the database or secrets manager, cache it with an expiry slightly before the real expiration, and refresh it through a scheduled command before it expires. Do not request a new token for every QR operation.
Recommended Laravel flow:
1. Add `VENDIR_QR_EMAIL`, `VENDIR_QR_PASSWORD`, and environment-specific base URLs to `.env`.
2. Create an artisan command that calls `login()` and stores the returned token encrypted.
3. Schedule the command monthly or at least before the one-year expiration date.
4. Inject the token into `Configuration::fromEnvironment()->withAccessToken($storedToken)` for QR generation and status checks.
5. Alert on failed refresh attempts so production QR creation is not blocked by an expired token.
## API Coverage
The SDK covers every endpoint described in the official Vendis QR REST API documentation bundled with this project:
1. `POST api/v1/login`
2. `POST api/v1/devices/simple-qr/generate`
3. `GET api/v1/devices/simple-qr/get/<QR-ID>`
4. Incoming callback payload and expected callback response helpers
## Testing
```bash
composer install
composer test
composer test:coverage
```
GitHub Actions runs PHPUnit on PHP 8.2.
## Documentation
Full usage notes and samples are in [docs/usage.md](docs/usage.md) and [docs/samples](docs/samples).
