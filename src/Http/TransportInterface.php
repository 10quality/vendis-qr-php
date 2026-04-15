<?php
declare(strict_types=1);
namespace VendisQr\Http;
use VendisQr\Enums\HttpMethod;
/**
 * Contract implemented by HTTP transports.
 *
 * @version 1.0.0
 */
interface TransportInterface
{
    /**
     * Sends an HTTP request.
     *
     * @param HttpMethod $method HTTP method.
     * @param string $url Absolute URL.
     * @param array<string,string> $headers Request headers.
     * @param array<string,mixed>|null $payload JSON request payload.
     * @param int $timeout Request timeout in seconds.
     * @return Response HTTP response.
     * @since 1.0.0
     */
    public function send(HttpMethod $method, string $url, array $headers = [], ?array $payload = null, int $timeout = 30): Response;
}
