<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use PHPUnit\Framework\TestCase;
use VendisQr\Exceptions\ConfigurationException;
use VendisQr\Webhooks\CallbackPayload;
use VendisQr\Webhooks\CallbackResponse;
use VendisQr\Webhooks\CallbackValidator;
/**
 * Tests Vendis callback helper classes.
 *
 * @version 1.0.0
 */
final class WebhookTest extends TestCase
{
    /**
     * It wraps callback payment payloads.
     *
     * @since 1.0.0
     */
    public function testItWrapsCallbackPayloads(): void
    {
        $payload = new CallbackPayload(['payment_date' => '2023-02-03 09:09:02', 'payment_amount' => '34.00', 'qr_id' => 234234, 'payment_name' => 'Carlos Vargas', 'payment_bank' => 'Bisa']);
        self::assertSame(234234, $payload->payment()->qrId());
        self::assertSame(['payment_date' => '2023-02-03 09:09:02', 'payment_amount' => '34.00', 'qr_id' => 234234, 'payment_name' => 'Carlos Vargas', 'payment_bank' => 'Bisa'], $payload->payment()->toArray());
    }
    /**
     * It builds callback responses.
     *
     * @since 1.0.0
     */
    public function testItBuildsCallbackResponses(): void
    {
        self::assertSame(['success' => true, 'message' => 'Ok'], CallbackResponse::success());
        self::assertSame(['success' => false, 'message' => 'error message'], CallbackResponse::error('error message'));
    }
    /**
     * It validates optional bearer callback tokens.
     *
     * @since 1.0.0
     */
    public function testItValidatesCallbackToken(): void
    {
        self::assertTrue(CallbackValidator::isValid('Bearer secret', 'secret'));
        self::assertFalse(CallbackValidator::isValid('Bearer other', 'secret'));
    }
    /**
     * It requires an expected callback token.
     *
     * @since 1.0.0
     */
    public function testItRequiresExpectedCallbackToken(): void
    {
        $this->expectException(ConfigurationException::class);
        CallbackValidator::isValid('Bearer secret', null);
    }
}
