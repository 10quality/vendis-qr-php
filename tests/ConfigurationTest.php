<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use PHPUnit\Framework\TestCase;
use VendisQr\Configuration;
use VendisQr\Exceptions\ConfigurationException;
use function putenv;
/**
 * Tests SDK configuration behavior.
 *
 * @version 1.0.0
 */
final class ConfigurationTest extends TestCase
{
    /**
     * It normalizes explicit configuration values.
     *
     * @since 1.0.0
     */
    public function testItNormalizesConfiguration(): void
    {
        $configuration = new Configuration('https://api.example.test/', 'user@example.test', 'secret', 'Device', 'token', 10);
        self::assertSame('https://api.example.test', $configuration->baseUrl());
        self::assertSame('user@example.test', $configuration->email());
        self::assertSame('secret', $configuration->password());
        self::assertSame('Device', $configuration->tokenName());
        self::assertSame('token', $configuration->accessToken());
        self::assertSame(10, $configuration->timeout());
        self::assertSame('new-token', $configuration->withAccessToken('new-token')->accessToken());
    }
    /**
     * It loads VENDIR_QR prefixed environment values.
     *
     * @since 1.0.0
     */
    public function testItLoadsEnvironmentVariables(): void
    {
        putenv('VENDIR_QR_BASE_URL=https://vendis.example.test');
        putenv('VENDIR_QR_EMAIL=vendis@example.test');
        putenv('VENDIR_QR_PASSWORD=password');
        putenv('VENDIR_QR_TOKEN_NAME=Laravel');
        putenv('VENDIR_QR_ACCESS_TOKEN=yearly');
        putenv('VENDIR_QR_TIMEOUT=9');
        $configuration = Configuration::fromEnvironment();
        self::assertSame('https://vendis.example.test', $configuration->baseUrl());
        self::assertSame('Laravel', $configuration->tokenName());
        self::assertSame(9, $configuration->timeout());
        putenv('VENDIR_QR_BASE_URL');
        putenv('VENDIR_QR_EMAIL');
        putenv('VENDIR_QR_PASSWORD');
        putenv('VENDIR_QR_TOKEN_NAME');
        putenv('VENDIR_QR_ACCESS_TOKEN');
        putenv('VENDIR_QR_TIMEOUT');
    }
    /**
     * It rejects invalid configuration values.
     *
     * @since 1.0.0
     */
    public function testItRejectsMissingBaseUrl(): void
    {
        $this->expectException(ConfigurationException::class);
        new Configuration('');
    }
    /**
     * It rejects invalid timeout values.
     *
     * @since 1.0.0
     */
    public function testItRejectsInvalidTimeout(): void
    {
        $this->expectException(ConfigurationException::class);
        new Configuration('https://vendis.test', timeout: 0);
    }
}
