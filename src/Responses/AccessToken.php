<?php
declare(strict_types=1);
namespace VendisQr\Responses;
/**
 * Access token returned by the official Vendis login endpoint.
 *
 * @version 1.0.0
 */
final class AccessToken
{
    /**
     * @var string Yearly access token.
     * @since 1.0.0
     */
    private string $value;
    /**
     * Creates an access token value object.
     *
     * @param string $value Yearly access token.
     * @since 1.0.0
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    /**
     * Returns the token value.
     *
     * @return string Token value.
     * @since 1.0.0
     */
    public function value(): string
    {
        return $this->value;
    }
}
