<?php
declare(strict_types=1);
namespace VendisQr\Responses;
use VendisQr\Enums\QrStatus;
/**
 * QR status data returned by Vendis.
 *
 * @version 1.0.0
 */
final class QrStatusResult
{
    /**
     * @var QrStatus|null Known QR status or null for unknown future values.
     * @since 1.0.0
     */
    private ?QrStatus $status;
    /**
     * @var string Raw QR status value.
     * @since 1.0.0
     */
    private string $rawStatus;
    /**
     * @var Payment[] Payments returned by Vendis.
     * @since 1.0.0
     */
    private array $payments;
    /**
     * Creates QR status data.
     *
     * @param string $rawStatus Raw QR status value.
     * @param Payment[] $payments Payments returned by Vendis.
     * @since 1.0.0
     */
    public function __construct(string $rawStatus, array $payments)
    {
        $this->rawStatus = $rawStatus;
        $this->status = QrStatus::tryFrom($rawStatus);
        $this->payments = $payments;
    }
    /**
     * Builds QR status data from an API payload.
     *
     * @param array<string,mixed> $data API response data.
     * @return self QR status data.
     * @since 1.0.0
     */
    public static function fromArray(array $data): self
    {
        $payments = [];
        foreach (($data['payments'] ?? []) as $payment) {
            if (is_array($payment)) {
                $payments[] = new Payment($payment);
            }
        }
        return new self((string) ($data['status'] ?? ''), $payments);
    }
    /**
     * Returns the known QR status.
     *
     * @return QrStatus|null Known QR status or null.
     * @since 1.0.0
     */
    public function status(): ?QrStatus
    {
        return $this->status;
    }
    /**
     * Returns the raw QR status.
     *
     * @return string Raw QR status.
     * @since 1.0.0
     */
    public function rawStatus(): string
    {
        return $this->rawStatus;
    }
    /**
     * Returns payments attached to the QR.
     *
     * @return Payment[] Payments attached to the QR.
     * @since 1.0.0
     */
    public function payments(): array
    {
        return $this->payments;
    }
}
