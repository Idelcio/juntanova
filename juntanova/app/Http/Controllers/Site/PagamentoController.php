<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\Request;

class PagamentoController extends Controller
{
    public function __construct(private PedidoService $pedidoService)
    {
    }

    public function sucesso(Request $request)
    {
        $pedido = $this->buscarPedido($request);
        if ($pedido) {
            $this->pedidoService->confirmarPagamento($pedido, [
                'mercadopago_id' => $request->input('payment_id'),
                'mercadopago_status' => 'approved',
                'status_pagamento' => 'approved',
                'metodo_pagamento' => 'mercadopago',
            ]);
        }

        return view('site.pagamento-sucesso', compact('pedido'));
    }

    public function pendente(Request $request)
    {
        $pedido = $this->buscarPedido($request);
        if ($pedido) {
            $pedido->update([
                'status' => 'pendente',
                'status_pagamento' => 'pending',
            ]);
        }

        return view('site.pagamento-pendente', compact('pedido'));
    }

    public function falha(Request $request)
    {
        $pedido = $this->buscarPedido($request);
        if ($pedido) {
            $pedido->update([
                'status' => 'cancelado',
                'status_pagamento' => 'failure',
            ]);
        }

        return view('site.pagamento-falha', compact('pedido'));
    }

    private function buscarPedido(Request $request): ?Pedido
    {
        $pedidoId = $request->input('external_reference') ?? $request->input('pedido_id');
        if (! $pedidoId) {
            return null;
        }

        return Pedido::with('itens.produto', 'usuario')->find($pedidoId);
    }
}
