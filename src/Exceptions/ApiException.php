<?php
declare(strict_types=1);
namespace VendisQr\Exceptions;
/**
 * Raised when Vendis returns an unsuccessful API response.
 *
 * @version 1.0.0
 */
class ApiException extends VendisQrException
{
    /**
     * @var int HTTP response status code.
     * @since 1.0.0
     */
    private int $statusCode;
    /**
     * @var array<string,mixed> Decoded response payload.
     * @since 1.0.0
     */
    private array $payload;
    /**
     * Creates an API exception.
     *
     * @param string $message Failure message.
     * @param int $statusCode HTTP response status code.
     * @param array<string,mixed> $payload Decoded response payload.
     * @since 1.0.0
     */
    public function __construct(string $message, int $statusCode, array $payload = [])
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
        $this->payload = $payload;
    }
    /**
     * Returns the HTTP response status code.
     *
     * @return int HTTP response status code.
     * @since 1.0.0
     */
    public function statusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * Returns the decoded response payload.
     *
     * @return array<string,mixed> Decoded response payload.
     * @since 1.0.0
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
