<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use PHPUnit\Framework\TestCase;
use VendisQr\Enums\Endpoint;
use VendisQr\Exceptions\ApiException;
/**
 * Tests small enum and exception value behavior.
 *
 * @version 1.0.0
 */
final class EndpointAndExceptionTest extends TestCase
{
    /**
     * It builds endpoint paths.
     *
     * @since 1.0.0
     */
    public function testItBuildsEndpointPaths(): void
    {
        self::assertSame('api/v1/login', Endpoint::Login->path());
        self::assertSame('api/v1/devices/simple-qr/get/qr%2F1', Endpoint::GetQrStatus->path('qr/1'));
    }
    /**
     * It exposes API exception context.
     *
     * @since 1.0.0
     */
    public function testItExposesApiExceptionContext(): void
    {
        $exception = new ApiException('Failed', 422, ['message' => 'Failed']);
        self::assertSame(422, $exception->statusCode());
        self::assertSame(['message' => 'Failed'], $exception->payload());
    }
}
