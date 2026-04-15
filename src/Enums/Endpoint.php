<?php
declare(strict_types=1);
namespace VendisQr\Enums;
/**
 * Official Vendis QR REST API endpoint paths.
 *
 * @version 1.0.0
 */
enum Endpoint: string
{
    case Login = 'api/v1/login';
    case GenerateQr = 'api/v1/devices/simple-qr/generate';
    case GetQrStatus = 'api/v1/devices/simple-qr/get';
    /**
     * Returns an endpoint path with an optional resource identifier.
     *
     * @param int|string|null $identifier Optional resource identifier.
     * @return string Endpoint path.
     * @since 1.0.0
     */
    public function path(int|string|null $identifier = null): string
    {
        return $identifier === null ? $this->value : $this->value . '/' . rawurlencode((string) $identifier);
    }
}
