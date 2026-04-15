<?php
declare(strict_types=1);
namespace VendisQr\Enums;
/**
 * Known QR status values returned by Vendis.
 *
 * @version 1.0.0
 */
enum QrStatus: string
{
    case Pending = 'Pendiente';
    case Cancelled = 'Anulado';
    case Paid = 'Pagado';
    case Failed = 'Fallido';
    /**
     * Determines whether a status value is final.
     *
     * @return bool True when the status does not require more polling.
     * @since 1.0.0
     */
    public function isTerminal(): bool
    {
        return $this !== self::Pending;
    }
}
