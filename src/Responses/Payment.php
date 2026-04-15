<?php
declare(strict_types=1);
namespace VendisQr\Responses;
/**
 * Payment item returned by QR status and webhook payloads.
 *
 * @version 1.0.0
 */
final class Payment
{
    /**
     * @var array<string,mixed> Raw Vendis payment payload.
     * @since 1.0.0
     */
    private array $payload;
    /**
     * Creates a payment value object.
     *
     * @param array<string,mixed> $payload Raw Vendis payment payload.
     * @since 1.0.0
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
    /**
     * Returns the payment date in Y-m-d H:i:s format.
     *
     * @return string Payment date.
     * @since 1.0.0
     */
    public function date(): string
    {
        return (string) ($this->payload['payment_date'] ?? '');
    }
    /**
     * Returns the payment amount as sent by Vendis.
     *
     * @return string Payment amount.
     * @since 1.0.0
     */
    public function amount(): string
    {
        return (string) ($this->payload['payment_amount'] ?? '');
    }
    /**
     * Returns the QR identifier associated with the payment.
     *
     * @return int|string QR identifier.
     * @since 1.0.0
     */
    public function qrId(): int|string
    {
        return $this->payload['qr_id'] ?? '';
    }
    /**
     * Returns the payer name.
     *
     * @return string Payer name.
     * @since 1.0.0
     */
    public function name(): string
    {
        return (string) ($this->payload['payment_name'] ?? '');
    }
    /**
     * Returns the payer bank.
     *
     * @return string Payer bank.
     * @since 1.0.0
     */
    public function bank(): string
    {
        return (string) ($this->payload['payment_bank'] ?? '');
    }
    /**
     * Returns the raw payment payload.
     *
     * @return array<string,mixed> Raw payment payload.
     * @since 1.0.0
     */
    public function toArray(): array
    {
        return $this->payload;
    }
}
