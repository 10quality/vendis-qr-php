<?php
declare(strict_types=1);
namespace VendisQr\Webhooks;
/**
 * Helper for callback responses expected by Vendis.
 *
 * @version 1.0.0
 */
final class CallbackResponse
{
    /**
     * Builds a successful callback response payload.
     *
     * @param string $message Response message.
     * @return array<string,mixed> Response payload.
     * @since 1.0.0
     */
    public static function success(string $message = 'Ok'): array
    {
        return ['success' => true, 'message' => $message];
    }
    /**
     * Builds a failed callback response payload.
     *
     * @param string $message Response message.
     * @return array<string,mixed> Response payload.
     * @since 1.0.0
     */
    public static function error(string $message): array
    {
        return ['success' => false, 'message' => $message];
    }
}
