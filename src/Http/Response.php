<?php
declare(strict_types=1);
namespace VendisQr\Http;
/**
 * Small immutable HTTP response object for SDK transports.
 *
 * @version 1.0.0
 */
final class Response
{
    /**
     * @var int HTTP status code.
     * @since 1.0.0
     */
    private int $statusCode;
    /**
     * @var string Raw response body.
     * @since 1.0.0
     */
    private string $body;
    /**
     * Creates an HTTP response value.
     *
     * @param int $statusCode HTTP status code.
     * @param string $body Raw response body.
     * @since 1.0.0
     */
    public function __construct(int $statusCode, string $body)
    {
        $this->statusCode = $statusCode;
        $this->body = $body;
    }
    /**
     * Returns the HTTP status code.
     *
     * @return int HTTP status code.
     * @since 1.0.0
     */
    public function statusCode(): int
    {
        return $this->statusCode;
    }
    /**
     * Returns the raw response body.
     *
     * @return string Raw response body.
     * @since 1.0.0
     */
    public function body(): string
    {
        return $this->body;
    }
}
