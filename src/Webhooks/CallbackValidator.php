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
     * Validates an Authorization header against an expected token.
     *
     * @param string|null $authorizationHeader Incoming Authorization header.
     * @param string|null $expectedToken Expected bearer token.
     * @return bool True when callback authentication is valid.
     * @throws ConfigurationException When an expected token is required but missing.
     * @since 1.0.0
     */
    public static function isValid(?string $authorizationHeader, ?string $expectedToken): bool
    {
        if ($expectedToken === null || trim($expectedToken) === '') {
            throw new ConfigurationException('A Vendis QR webhook token is required to validate callback authorization.');
        }
        return hash_equals('Bearer ' . $expectedToken, (string) $authorizationHeader);
    }
}
