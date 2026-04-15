<?php
declare(strict_types=1);
namespace VendisQr\Tests;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VendisQr\Configuration;
use VendisQr\Enums\HttpMethod;
use VendisQr\Enums\QrStatus;
use VendisQr\Exceptions\ApiException;
use VendisQr\Exceptions\ConfigurationException;
use VendisQr\Exceptions\TransportException;
use VendisQr\Http\Response;
use VendisQr\Requests\GenerateQrRequest;
use VendisQr\VendisQrClient;
/**
 * Tests Vendis QR client endpoint behavior.
 *
 * @version 1.0.0
 */
final class VendisQrClientTest extends TestCase
{
    /**
     * It requests a yearly access token.
     *
     * @since 1.0.0
     */
    public function testItLogsIn(): void
    {
        $transport = new FakeTransport([new Response(200, '{"access_token":"123|abc"}')]);
        $client = new VendisQrClient(new Configuration('https://vendis.test', 'api@example.test', 'secret', 'Tests'), $transport);
        self::assertSame('123|abc', $client->login()->value());
        self::assertSame(HttpMethod::Post, $transport->requests[0]['method']);
        self::assertSame('https://vendis.test/api/v1/login', $transport->requests[0]['url']);
        self::assertSame('api@example.test', $transport->requests[0]['payload']['email']);
    }
    /**
     * It requires credentials before login.
     *
     * @since 1.0.0
     */
    public function testItRequiresCredentialsForLogin(): void
    {
        $this->expectException(ConfigurationException::class);
        (new VendisQrClient(new Configuration('https://vendis.test')))->login();
    }
    /**
     * It rejects login responses without tokens.
     *
     * @since 1.0.0
     */
    public function testItRejectsLoginWithoutToken(): void
    {
        $this->expectException(ApiException::class);
        $client = new VendisQrClient(new Configuration('https://vendis.test', 'api@example.test', 'secret'), new FakeTransport([new Response(200, '{"message":"ok"}')]));
        $client->login();
    }
    /**
     * It generates a QR code.
     *
     * @since 1.0.0
     */
    public function testItGeneratesQr(): void
    {
        $transport = new FakeTransport([new Response(200, '{"success":true,"data":{"qr_image":"base64","qr_url":"https://qr.test/image.jpg","qr_id":816269745}}')]);
        $client = new VendisQrClient(new Configuration('https://vendis.test', accessToken: 'yearly'), $transport);
        $qr = $client->generateQr(new GenerateQrRequest(17, 25.5, true, false, new DateTimeImmutable('2026-04-15 23:59:00'), 'Pago QR'));
        self::assertSame('base64', $qr->image());
        self::assertSame('https://qr.test/image.jpg', $qr->url());
        self::assertSame(816269745, $qr->id());
        self::assertSame('Bearer yearly', $transport->requests[0]['headers']['Authorization']);
        self::assertSame('2026-04-15 23:59:00', $transport->requests[0]['payload']['qr_expiration']);
    }
    /**
     * It gets QR status and payments.
     *
     * @since 1.0.0
     */
    public function testItGetsQrStatus(): void
    {
        $transport = new FakeTransport([new Response(200, '{"success":true,"data":{"status":"Pagado","payments":[{"payment_date":"2023-11-10 18:32:34","payment_amount":"250.00","qr_id":"6561998","payment_name":"PINTO WILFREDO","payment_bank":"BEC"}]}}')]);
        $client = new VendisQrClient(new Configuration('https://vendis.test', accessToken: 'yearly'), $transport);
        $status = $client->getQrStatus(6561998);
        self::assertSame(QrStatus::Paid, $status->status());
        self::assertSame('Pagado', $status->rawStatus());
        self::assertTrue(QrStatus::Paid->isTerminal());
        self::assertFalse(QrStatus::Pending->isTerminal());
        self::assertSame('250.00', $status->payments()[0]->amount());
        self::assertSame('PINTO WILFREDO', $status->payments()[0]->name());
        self::assertSame('BEC', $status->payments()[0]->bank());
        self::assertSame('6561998', $status->payments()[0]->qrId());
        self::assertSame('2023-11-10 18:32:34', $status->payments()[0]->date());
        self::assertSame('https://vendis.test/api/v1/devices/simple-qr/get/6561998', $transport->requests[0]['url']);
    }
    /**
     * It requires access tokens for protected endpoints.
     *
     * @since 1.0.0
     */
    public function testItRequiresAccessToken(): void
    {
        $this->expectException(ConfigurationException::class);
        (new VendisQrClient(new Configuration('https://vendis.test')))->getQrStatus(1);
    }
    /**
     * It converts Vendis errors to API exceptions.
     *
     * @since 1.0.0
     */
    public function testItThrowsApiExceptionForUnsuccessfulResponses(): void
    {
        $this->expectException(ApiException::class);
        $client = new VendisQrClient(new Configuration('https://vendis.test', accessToken: 'yearly'), new FakeTransport([new Response(404, '{"success":false,"message":"QR no encontrado"}')]));
        $client->getQrStatus(1);
    }
    /**
     * It wraps invalid JSON in transport exceptions.
     *
     * @since 1.0.0
     */
    public function testItThrowsTransportExceptionForInvalidJson(): void
    {
        $this->expectException(TransportException::class);
        $client = new VendisQrClient(new Configuration('https://vendis.test', accessToken: 'yearly'), new FakeTransport([new Response(200, 'not-json')]));
        $client->getQrStatus(1);
    }
}
