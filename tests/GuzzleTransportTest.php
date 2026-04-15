<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PHPUnit\Framework\TestCase;
use VendisQr\Enums\HttpMethod;
use VendisQr\Exceptions\TransportException;
use VendisQr\Http\GuzzleTransport;
/**
 * Tests Guzzle transport behavior.
 *
 * @version 1.0.0
 */
final class GuzzleTransportTest extends TestCase
{
    /**
     * It sends requests through Guzzle.
     *
     * @since 1.0.0
     */
    public function testItSendsRequestsThroughGuzzle(): void
    {
        $transport = new GuzzleTransport(new Client(['handler' => HandlerStack::create(new MockHandler([new GuzzleResponse(201, [], '{"ok":true}')]))]));
        $response = $transport->send(HttpMethod::Post, 'https://vendis.test/api/v1/login', ['Accept' => 'application/json'], ['email' => 'a'], 5);
        self::assertSame(201, $response->statusCode());
        self::assertSame('{"ok":true}', $response->body());
    }
    /**
     * It wraps Guzzle failures.
     *
     * @since 1.0.0
     */
    public function testItWrapsGuzzleFailures(): void
    {
        $this->expectException(TransportException::class);
        $transport = new GuzzleTransport(new Client(['handler' => HandlerStack::create(new MockHandler([new RequestException('Failed', new Request('GET', 'https://vendis.test'))]))]));
        $transport->send(HttpMethod::Get, 'https://vendis.test/api/v1/login');
    }
}
