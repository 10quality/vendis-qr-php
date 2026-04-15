<?php
declare(strict_types=1);
namespace VendisQr\Requests;
use DateTimeInterface;
/**
 * Request payload for the official generate QR endpoint.
 *
 * @version 1.0.0
 */
final class GenerateQrRequest
{
    /**
     * @var int|string Vendis device identifier.
     * @since 1.0.0
     */
    private int|string $deviceId;
    /**
     * @var float QR payment amount.
     * @since 1.0.0
     */
    private float $amount;
    /**
     * @var bool Whether the payer may change the amount.
     * @since 1.0.0
     */
    private bool $modifyAmount;
    /**
     * @var bool Whether the QR can receive multiple payments.
     * @since 1.0.0
     */
    private bool $multiUse;
    /**
     * @var DateTimeInterface QR expiration date.
     * @since 1.0.0
     */
    private DateTimeInterface $expiration;
    /**
     * @var string QR description.
     * @since 1.0.0
     */
    private string $description;
    /**
     * Creates a generate QR request.
     *
     * @param int|string $deviceId Vendis device identifier.
     * @param float $amount QR payment amount.
     * @param bool $modifyAmount Whether the payer may change the amount.
     * @param bool $multiUse Whether the QR can receive multiple payments.
     * @param DateTimeInterface $expiration QR expiration date.
     * @param string $description QR description.
     * @since 1.0.0
     */
    public function __construct(int|string $deviceId, float $amount, bool $modifyAmount, bool $multiUse, DateTimeInterface $expiration, string $description)
    {
        $this->deviceId = $deviceId;
        $this->amount = $amount;
        $this->modifyAmount = $modifyAmount;
        $this->multiUse = $multiUse;
        $this->expiration = $expiration;
        $this->description = $description;
    }
    /**
     * Converts the request to the official Vendis payload.
     *
     * @return array<string,mixed> Official Vendis payload.
     * @since 1.0.0
     */
    public function toArray(): array
    {
        return ['device_id' => $this->deviceId, 'amount' => $this->amount, 'modify_amount' => $this->modifyAmount, 'is_multi_use' => $this->multiUse, 'qr_expiration' => $this->expiration->format('Y-m-d H:i:s'), 'description' => $this->description];
    }
}
