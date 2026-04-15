<?php
declare(strict_types=1);
namespace VendisQr\Enums;
/**
 * Supported Vendis API environments.
 *
 * @version 1.0.0
 */
enum Environment: string
{
    case Sandbox = 'sandbox';
    case Production = 'production';
    /**
     * Builds an environment enum from a string value.
     *
     * @param string $value Environment value.
     * @return self Supported environment.
     * @since 1.0.0
     */
    public static function fromString(string $value): self
    {
        return self::tryFrom(strtolower($value)) ?? self::Sandbox;
    }
}
