<?php
declare(strict_types=1);
namespace VendisQr\Http;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use VendisQr\Enums\HttpMethod;
use VendisQr\Exceptions\TransportException;
/**
 * Guzzle HTTP transport used by the SDK in production.
 *
 * @version 1.0.0
 */
final class GuzzleTransport implements TransportInterface
{
    /**
     * @var ClientInterface Guzzle client instance.
     * @since 1.0.0
     */
    private ClientInterface $client;
    /**
     * Creates a Guzzle transport.
     *
     * @param ClientInterface|null $client Optional preconfigured Guzzle client.
     * @since 1.0.0
     */
    public function __construct(?ClientInterface $client = null)
    {
        $this->client = $client ?? new Client();
    }
    /**
     * Sends an HTTP request through Guzzle.
     *
     * @param HttpMethod $method HTTP method.
     * @param string $url Absolute URL.
     * @param array<string,string> $headers Request headers.
     * @param array<string,mixed>|null $payload JSON request payload.
     * @param int $timeout Request timeout in seconds.
     * @return Response HTTP response.
     * @throws TransportException When Guzzle cannot complete the request.
     * @since 1.0.0
     */
    public function send(HttpMethod $method, string $url, array $headers = [], ?array $payload = null, int $timeout = 30): Response
    {
        try {
            $response = $this->client->request($method->value, $url, ['headers' => $headers, 'json' => $payload, 'timeout' => $timeout, 'http_errors' => false]);
            return new Response($response->getStatusCode(), (string) $response->getBody());
        } catch (GuzzleException $exception) {
            throw new TransportException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
