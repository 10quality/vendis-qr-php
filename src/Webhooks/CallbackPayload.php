<?php
declare(strict_types=1);
namespace VendisQr\Webhooks;
use VendisQr\Responses\Payment;
/**
 * Typed representation of a Vendis QR callback payload.
 *
 * @version 1.0.0
 */
final class CallbackPayload
{
    /**
     * @var Payment Payment data sent by Vendis.
     * @since 1.0.0
     */
    private Payment $payment;
    /**
     * Creates a callback payload.
     *
     * @param array<string,mixed> $payload Raw Vendis callback payload.
     * @since 1.0.0
     */
    public function __construct(array $payload)
    {
        $this->payment = new Payment($payload);
    }
    /**
     * Returns the payment sent by Vendis.
     *
     * @return Payment Payment sent by Vendis.
     * @since 1.0.0
     */
    public function payment(): Payment
    {
        return $this->payment;
    }
}
