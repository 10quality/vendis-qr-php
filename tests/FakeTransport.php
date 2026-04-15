<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use VendisQr\Enums\HttpMethod;
use VendisQr\Http\Response;
use VendisQr\Http\TransportInterface;
use function array_shift;
/**
 * In-memory transport used by SDK unit tests.
 *
 * @version 1.0.0
 */
final class FakeTransport implements TransportInterface
{
    /**
     * @var Response[] Queued responses.
     * @since 1.0.0
     */
    private array $responses;
    /**
     * @var array<int,array<string,mixed>> Captured requests.
     * @since 1.0.0
     */
    public array $requests = [];
    /**
     * Creates the fake transport.
     *
     * @param Response[] $responses Queued responses.
     * @since 1.0.0
     */
    public function __construct(array $responses)
    {
        $this->responses = $responses;
    }
    /**
     * Captures a request and returns the next queued response.
     *
     * @param HttpMethod $method HTTP method.
     * @param string $url Absolute URL.
     * @param array<string,string> $headers Request headers.
     * @param array<string,mixed>|null $payload JSON request payload.
     * @param int $timeout Request timeout in seconds.
     * @since 1.0.0
     */
    public function send(HttpMethod $method, string $url, array $headers = [], ?array $payload = null, int $timeout = 30): Response
    {
        $this->requests[] = ['method' => $method, 'url' => $url, 'headers' => $headers, 'payload' => $payload, 'timeout' => $timeout];
        return array_shift($this->responses) ?? new Response(500, '{"message":"No response queued"}');
    }
}
