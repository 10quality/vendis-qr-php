<?php
declare(strict_types=1);
namespace VendisQr;
use VendisQr\Exceptions\ConfigurationException;
/**
 * Immutable SDK configuration loaded from explicit values or environment variables.
 *
 * @version 1.0.0
 */
final class Configuration
{
    /**
     * @var string Base URL for Vendis API requests.
     * @since 1.0.0
     */
    private string $baseUrl;
    /**
     * @var string|null API email used to request the yearly access token.
     * @since 1.0.0
     */
    private ?string $email;
    /**
     * @var string|null API password used to request the yearly access token.
     * @since 1.0.0
     */
    private ?string $password;
    /**
     * @var string Token name sent to the login endpoint.
     * @since 1.0.0
     */
    private string $tokenName;
    /**
     * @var string|null Yearly bearer token used for QR requests.
     * @since 1.0.0
     */
    private ?string $accessToken;
    /**
     * @var int HTTP timeout in seconds.
     * @since 1.0.0
     */
    private int $timeout;
    /**
     * Creates SDK configuration.
     *
     * @param string $baseUrl Base URL for Vendis API requests.
     * @param string|null $email API email used to request the yearly access token.
     * @param string|null $password API password used to request the yearly access token.
     * @param string $tokenName Token name sent to the login endpoint.
     * @param string|null $accessToken Yearly bearer token used for QR requests.
     * @param int $timeout HTTP timeout in seconds.
     * @throws ConfigurationException When configuration values are invalid.
     * @since 1.0.0
     */
    public function __construct(string $baseUrl, ?string $email = null, ?string $password = null, string $tokenName = 'Vendis QR PHP', ?string $accessToken = null, int $timeout = 30)
    {
        if (trim($baseUrl) === '') {
            throw new ConfigurationException('Vendis QR base URL is required.');
        }
        if ($timeout < 1) {
            throw new ConfigurationException('Vendis QR timeout must be greater than zero.');
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->email = self::emptyToNull($email);
        $this->password = self::emptyToNull($password);
        $this->tokenName = $tokenName;
        $this->accessToken = self::emptyToNull($accessToken);
        $this->timeout = $timeout;
    }
    /**
     * Creates configuration from VENDIS_QR_* environment variables.
     *
     * @return self Loaded configuration.
     * @throws ConfigurationException When required configuration is missing.
     * @since 1.0.0
     */
    public static function fromEnvironment(): self
    {
        return new self(self::env('VENDIS_QR_BASE_URL') ?? '', self::env('VENDIS_QR_EMAIL'), self::env('VENDIS_QR_PASSWORD'), self::env('VENDIS_QR_TOKEN_NAME', 'Vendis QR PHP') ?? 'Vendis QR PHP', self::env('VENDIS_QR_ACCESS_TOKEN'), (int) (self::env('VENDIS_QR_TIMEOUT', '30') ?? '30'));
    }
    /**
     * Returns the normalized API base URL.
     *
     * @return string Normalized API base URL.
     * @since 1.0.0
     */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }
    /**
     * Returns the configured API email.
     *
     * @return string|null Configured API email.
     * @since 1.0.0
     */
    public function email(): ?string
    {
        return $this->email;
    }
    /**
     * Returns the configured API password.
     *
     * @return string|null Configured API password.
     * @since 1.0.0
     */
    public function password(): ?string
    {
        return $this->password;
    }
    /**
     * Returns the configured token name.
     *
     * @return string Configured token name.
     * @since 1.0.0
     */
    public function tokenName(): string
    {
        return $this->tokenName;
    }
    /**
     * Returns the configured access token.
     *
     * @return string|null Configured access token.
     * @since 1.0.0
     */
    public function accessToken(): ?string
    {
        return $this->accessToken;
    }
    /**
     * Returns the configured HTTP timeout.
     *
     * @return int Configured HTTP timeout.
     * @since 1.0.0
     */
    public function timeout(): int
    {
        return $this->timeout;
    }
    /**
     * Creates a copy with a different access token.
     *
     * @param string $accessToken Yearly bearer token.
     * @return self Configuration copy.
     * @since 1.0.0
     */
    public function withAccessToken(string $accessToken): self
    {
        return new self($this->baseUrl, $this->email, $this->password, $this->tokenName, $accessToken, $this->timeout);
    }
    /**
     * Reads an environment variable without allocating fallback arrays.
     *
     * @param string $key Environment variable name.
     * @param string|null $default Default value.
     * @return string|null Environment value.
     * @since 1.0.0
     */
    private static function env(string $key, ?string $default = null): ?string
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
    /**
     * Normalizes empty strings to null.
     *
     * @param string|null $value Input value.
     * @return string|null Normalized value.
     * @since 1.0.0
     */
    private static function emptyToNull(?string $value): ?string
    {
        return $value === null || trim($value) === '' ? null : $value;
    }
}
