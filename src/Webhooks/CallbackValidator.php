<?php
declare(strict_types=1);
namespace VendisQr\Webhooks;
use VendisQr\Exceptions\ConfigurationException;
use function hash_equals;
use function trim;
/**
 * Validates optional bearer authentication for Vendis callbacks.
 *
 * @version 1.0.0
 */
final class CallbackValidator
{
    /**
     * Validates a Vendis callback Authorization header when it is present.
     *
     * @param string|null $authorizationHeader Incoming Authorization header.
     * @param string|null $accessToken Yearly Vendis access token.
     * @return bool True when callback authentication is valid.
     * @throws ConfigurationException When the header is present but the access token is missing.
     * @since 1.0.0
     */
    public static function isValid(?string $authorizationHeader, ?string $accessToken): bool
    {
        if ($authorizationHeader === null || trim($authorizationHeader) === '') {
            return true;
        }
        if ($accessToken === null || trim($accessToken) === '') {
            throw new ConfigurationException('A Vendis QR access token is required to validate callback authorization.');
        }
        return hash_equals('Bearer ' . $accessToken, $authorizationHeader);
    }
}
