<?php
declare(strict_types=1);
namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use VendisQr\Configuration;
use VendisQr\Webhooks\CallbackPayload;
use VendisQr\Webhooks\CallbackResponse;
use VendisQr\Webhooks\CallbackValidator;
final class VendisQrWebhookController
{
    /**
     * Handles a Vendis payment callback.
     *
     * @param Request $request Incoming Laravel request.
     * @return JsonResponse JSON response expected by Vendis.
     */
    public function __invoke(Request $request): JsonResponse
    {
        if (!CallbackValidator::isValid($request->header('Authorization'), Configuration::fromEnvironment()->accessToken())) {
            return response()->json(CallbackResponse::error('Unauthorized'), 401);
        }
        $payload = new CallbackPayload($request->all());
        $payment = $payload->payment();
        return response()->json(CallbackResponse::success());
    }
}
