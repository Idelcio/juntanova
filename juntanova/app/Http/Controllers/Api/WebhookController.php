<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class WebhookController extends Controller
{
    public function __construct(private PedidoService $pedidoService)
    {
    }

    public function mercadopago(Request $request)
    {
        $secret = config('services.mercadopago.webhook_secret');
        $providedSecret = $request->header('x-webhook-secret') ?? $request->input('secret');

        if ($secret && $secret !== $providedSecret) {
            return response()->json(['message' => 'Assinatura inválida'], 403);
        }

        $paymentId = $request->input('data.id') ?? $request->input('id');
        if (! $paymentId) {
            return response()->json(['message' => 'Payload sem pagamento'], 200);
        }

        try {
            $accessToken = config('services.mercadopago.access_token');
            if (! $accessToken) {
                throw new \RuntimeException('Token de acesso do Mercado Pago não configurado.');
            }

            MercadoPagoConfig::setAccessToken($accessToken);
            $client = new PaymentClient();
            $payment = $client->get($paymentId);

            $pedidoId = $payment->external_reference ?? null;
            if (! $pedidoId) {
                return response()->json(['message' => 'Pedido não encontrado'], 404);
            }

            $pedido = Pedido::with('itens.produto')->find($pedidoId);
            if (! $pedido) {
                return response()->json(['message' => 'Pedido não encontrado'], 404);
            }

            if (($payment->status ?? '') === 'approved') {
                $this->pedidoService->confirmarPagamento($pedido, [
                    'mercadopago_id' => $paymentId,
                    'mercadopago_status' => $payment->status ?? null,
                    'status_pagamento' => $payment->status ?? null,
                    'metodo_pagamento' => $payment->payment_method_id ?? 'mercadopago',
                ]);
            }

            return response()->json(['message' => 'ok']);
        } catch (\Throwable $e) {
            report($e);

            return response()->json(['message' => 'Erro ao processar webhook'], 500);
        }
    }
}
