<?php
declare(strict_types=1);
namespace VendisQr;
use JsonException;
use VendisQr\Enums\Endpoint;
use VendisQr\Enums\HttpMethod;
use VendisQr\Exceptions\ApiException;
use VendisQr\Exceptions\ConfigurationException;
use VendisQr\Exceptions\TransportException;
use VendisQr\Http\GuzzleTransport;
use VendisQr\Http\TransportInterface;
use VendisQr\Requests\GenerateQrRequest;
use VendisQr\Responses\AccessToken;
use VendisQr\Responses\GeneratedQr;
use VendisQr\Responses\QrStatusResult;
use function is_array;
use function json_decode;
use const JSON_THROW_ON_ERROR;
/**
 * Main SDK client for the official Vendis QR REST API.
 *
 * @version 1.0.0
 */
final class VendisQrClient
{
    /**
     * @var Configuration SDK configuration.
     * @since 1.0.0
     */
    private Configuration $configuration;
    /**
     * @var TransportInterface HTTP transport.
     * @since 1.0.0
     */
    private TransportInterface $transport;
    /**
     * Creates the Vendis QR API client.
     *
     * @param Configuration $configuration SDK configuration.
     * @param TransportInterface|null $transport Optional transport, useful for tests.
     * @since 1.0.0
     */
    public function __construct(Configuration $configuration, ?TransportInterface $transport = null)
    {
        $this->configuration = $configuration;
        $this->transport = $transport ?? new GuzzleTransport();
    }
    /**
     * Requests the yearly Vendis access token from the login endpoint.
     *
     * @return AccessToken Yearly access token.
     * @throws ApiException When Vendis returns an error.
     * @throws ConfigurationException When credentials are missing.
     * @throws TransportException When the HTTP transport fails.
     * @since 1.0.0
     */
    public function login(): AccessToken
    {
        if ($this->configuration->email() === null || $this->configuration->password() === null) {
            throw new ConfigurationException('Vendis QR email and password are required to request an access token.');
        }
        $payload = $this->request(HttpMethod::Post, Endpoint::Login, ['email' => $this->configuration->email(), 'password' => $this->configuration->password(), 'token_name' => $this->configuration->tokenName()]);
        if (!isset($payload['access_token'])) {
            throw new ApiException('Vendis login response did not include an access token.', 200, $payload);
        }
        return new AccessToken((string) $payload['access_token']);
    }
    /**
     * Generates a dynamic QR code using the official Vendis endpoint.
     *
     * @param GenerateQrRequest $request Generate QR request.
     * @return GeneratedQr Generated QR data.
     * @throws ApiException When Vendis returns an error.
     * @throws ConfigurationException When access token is missing.
     * @throws TransportException When the HTTP transport fails.
     * @since 1.0.0
     */
    public function generateQr(GenerateQrRequest $request): GeneratedQr
    {
        $payload = $this->request(HttpMethod::Post, Endpoint::GenerateQr, $request->toArray(), $this->bearerHeaders());
        return GeneratedQr::fromArray(is_array($payload['data'] ?? null) ? $payload['data'] : []);
    }
    /**
     * Retrieves the latest QR status and attached payment records.
     *
     * @param int|string $qrId Vendis QR identifier.
     * @return QrStatusResult QR status result.
     * @throws ApiException When Vendis returns an error.
     * @throws ConfigurationException When access token is missing.
     * @throws TransportException When the HTTP transport fails.
     * @since 1.0.0
     */
    public function getQrStatus(int|string $qrId): QrStatusResult
    {
        $payload = $this->request(HttpMethod::Get, Endpoint::GetQrStatus, null, $this->bearerHeaders(), $qrId);
        return QrStatusResult::fromArray(is_array($payload['data'] ?? null) ? $payload['data'] : []);
    }
    /**
     * Sends a typed request to Vendis and decodes the JSON payload.
     *
     * @param HttpMethod $method HTTP method.
     * @param Endpoint $endpoint Vendis endpoint.
     * @param array<string,mixed>|null $payload Optional JSON payload.
     * @param array<string,string> $headers Optional request headers.
     * @param int|string|null $identifier Optional endpoint identifier.
     * @return array<string,mixed> Decoded response payload.
     * @throws ApiException When the API response is unsuccessful.
     * @throws TransportException When JSON cannot be decoded.
     * @since 1.0.0
     */
    private function request(HttpMethod $method, Endpoint $endpoint, ?array $payload = null, array $headers = [], int|string|null $identifier = null): array
    {
        $response = $this->transport->send($method, $this->configuration->baseUrl() . '/' . $endpoint->path($identifier), ['Accept' => 'application/json', 'Content-Type' => 'application/json'] + $headers, $payload, $this->configuration->timeout());
        try {
            $decoded = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new TransportException('Vendis returned invalid JSON.', 0, $exception);
        }
        $decoded = is_array($decoded) ? $decoded : [];
        if ($response->statusCode() < 200 || $response->statusCode() >= 300 || ($decoded['success'] ?? true) === false) {
            throw new ApiException((string) ($decoded['message'] ?? 'Vendis API request failed.'), $response->statusCode(), $decoded);
        }
        return $decoded;
    }
    /**
     * Builds authorization headers for token-protected endpoints.
     *
     * @return array<string,string> Bearer authorization headers.
     * @throws ConfigurationException When access token is missing.
     * @since 1.0.0
     */
    private function bearerHeaders(): array
    {
        if ($this->configuration->accessToken() === null) {
            throw new ConfigurationException('Vendis QR access token is required for this endpoint.');
        }
        return ['Authorization' => 'Bearer ' . $this->configuration->accessToken()];
    }
}
