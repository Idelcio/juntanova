<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Produto;
use App\Services\PedidoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class CheckoutController extends Controller
{
    public function __construct(private PedidoService $pedidoService)
    {
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'É necessário estar logado para finalizar.']);
        }

        $address = $request->validate([
            'cep' => ['required', 'string', 'max:10'],
            'rua' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:255'],
            'bairro' => ['required', 'string', 'max:255'],
            'cidade' => ['required', 'string', 'max:255'],
            'estado' => ['required', 'string', 'max:2'],
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return back()->withErrors(['cart' => 'Seu carrinho está vazio.']);
        }

        $produtos = Produto::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $linha) {
            $produto = $produtos[$linha['produto_id']] ?? null;
            if (! $produto || $linha['quantidade'] > $produto->estoque) {
                return back()->withErrors(['cart' => 'Estoque insuficiente para '.$linha['nome']]);
            }
        }

        $valorProdutos = collect($cart)->sum(fn ($item) => $item['subtotal']);
        $valorFrete = $this->pedidoService->calcularFrete($address['cep'], $cart);

        $pedido = DB::transaction(function () use ($cart, $user, $address, $valorProdutos, $valorFrete) {
            $pedido = Pedido::create([
                'numero_pedido' => $this->pedidoService->gerarNumeroPedido(),
                'usuario_id' => $user->id,
                'cep' => $address['cep'],
                'rua' => $address['rua'],
                'numero' => $address['numero'],
                'complemento' => $address['complemento'] ?? null,
                'bairro' => $address['bairro'],
                'cidade' => $address['cidade'],
                'estado' => strtoupper($address['estado']),
                'pais' => 'Brasil',
                'valor_produtos' => $valorProdutos,
                'valor_frete' => $valorFrete,
                'valor_total' => $valorProdutos + $valorFrete,
                'status' => 'pendente',
                'status_pagamento' => 'pending',
                'metodo_pagamento' => 'mercadopago',
            ]);

            foreach ($cart as $item) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'produto_id' => $item['produto_id'],
                    'nome' => $item['nome'],
                    'quantidade' => $item['quantidade'],
                    'preco' => $item['preco'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $user->update([
                'cep' => $address['cep'],
                'rua' => $address['rua'],
                'numero' => $address['numero'],
                'complemento' => $address['complemento'] ?? null,
                'bairro' => $address['bairro'],
                'cidade' => $address['cidade'],
                'estado' => strtoupper($address['estado']),
            ]);

            return $pedido;
        });

        try {
            $accessToken = config('services.mercadopago.access_token');
            if (! $accessToken) {
                throw new \RuntimeException('Token de acesso do Mercado Pago não configurado.');
            }

            MercadoPagoConfig::setAccessToken($accessToken);
            $notificationUrl = config('services.mercadopago.webhook_url') ?: url('/api/webhook/mercadopago');
            $preference = (new PreferenceClient())->create([
                'items' => $this->mapearItens($cart),
                'payer' => [
                    'name' => $user->nome,
                    'email' => $user->email,
                    'identification' => [
                        'type' => 'CPF',
                        'number' => $user->cpf,
                    ],
                ],
                'external_reference' => (string) $pedido->id,
                'back_urls' => [
                    'success' => route('pagamento.sucesso'),
                    'failure' => route('pagamento.falha'),
                    'pending' => route('pagamento.pendente'),
                ],
                'auto_return' => 'approved',
                'notification_url' => $notificationUrl,
            ]);

            $pedido->update([
                'mercadopago_id' => $preference->id ?? null,
                'status_pagamento' => 'pending',
            ]);

            Session::forget('cart');

            return redirect($preference->init_point ?? $preference->sandbox_init_point ?? route('pagamento.pendente'));
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('pagamento.falha')->withErrors(['mp' => 'Erro ao criar pagamento: '.$e->getMessage()]);
        }
    }

    private function mapearItens(array $cart): array
    {
        return collect($cart)->map(function ($item) {
            return [
                'title' => $item['nome'],
                'quantity' => $item['quantidade'],
                'unit_price' => (float) $item['preco'],
                'currency_id' => 'BRL',
            ];
        })->values()->all();
    }
}
