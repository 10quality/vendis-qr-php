<?php
declare(strict_types=1);
namespace VendisQr\Responses;
/**
 * Generated QR data returned by Vendis.
 *
 * @version 1.0.0
 */
final class GeneratedQr
{
    /**
     * @var string Base64 QR image.
     * @since 1.0.0
     */
    private string $image;
    /**
     * @var string Public QR image URL.
     * @since 1.0.0
     */
    private string $url;
    /**
     * @var int|string QR identifier.
     * @since 1.0.0
     */
    private int|string $id;
    /**
     * Creates generated QR data.
     *
     * @param string $image Base64 QR image.
     * @param string $url Public QR image URL.
     * @param int|string $id QR identifier.
     * @since 1.0.0
     */
    public function __construct(string $image, string $url, int|string $id)
    {
        $this->image = $image;
        $this->url = $url;
        $this->id = $id;
    }
    /**
     * Builds generated QR data from an API payload.
     *
     * @param array<string,mixed> $data API response data.
     * @return self Generated QR data.
     * @since 1.0.0
     */
    public static function fromArray(array $data): self
    {
        return new self((string) ($data['qr_image'] ?? ''), (string) ($data['qr_url'] ?? ''), $data['qr_id'] ?? '');
    }
    /**
     * Returns the base64 QR image.
     *
     * @return string Base64 QR image.
     * @since 1.0.0
     */
    public function image(): string
    {
        return $this->image;
    }
    /**
     * Returns the public QR image URL.
     *
     * @return string Public QR image URL.
     * @since 1.0.0
     */
    public function url(): string
    {
        return $this->url;
    }
    /**
     * Returns the QR identifier.
     *
     * @return int|string QR identifier.
     * @since 1.0.0
     */
    public function id(): int|string
    {
        return $this->id;
    }
}
