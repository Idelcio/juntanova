<?php

namespace App\Services;

use App\Mail\PedidoConfirmadoMail;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PedidoService
{
    public function gerarNumeroPedido(): string
    {
        return 'JN'.now()->format('Ymd').strtoupper(Str::random(4));
    }

    public function calcularFrete(?string $cepDestino, array $cart = []): float
    {
        $cepDestino = preg_replace('/\D/', '', $cepDestino ?? '');

        // Conta total de frascos no carrinho
        $totalFrascos = collect($cart)->sum('quantidade');

        // Se tiver 3 ou mais frascos, frete grátis para todas as regiões
        if ($totalFrascos >= 3) {
            return 0.00;
        }

        // Identifica a região pelo CEP
        $regiao = $this->identificarRegiaoPorCep($cepDestino);

        // Sul e Sudeste: frete grátis
        if (in_array($regiao, ['sul', 'sudeste'])) {
            return 0.00;
        }

        // Outras regiões (Norte, Nordeste, Centro-Oeste): R$ 30,00 para 1 frasco
        return (float) env('FRETE_OUTRAS_REGIOES', 30.00);
    }

    private function identificarRegiaoPorCep(string $cep): string
    {
        $cep = (int) substr(str_pad($cep, 8, '0'), 0, 5);

        // Faixas de CEP por região (aproximadas)
        // Sul: RS, SC, PR
        if (($cep >= 80000 && $cep <= 87999) || // PR
            ($cep >= 88000 && $cep <= 89999) || // SC
            ($cep >= 90000 && $cep <= 99999)) { // RS
            return 'sul';
        }

        // Sudeste: SP, RJ, MG, ES
        if (($cep >= 1000 && $cep <= 19999) ||  // SP
            ($cep >= 20000 && $cep <= 28999) || // RJ
            ($cep >= 29000 && $cep <= 29999) || // ES
            ($cep >= 30000 && $cep <= 39999)) { // MG
            return 'sudeste';
        }

        // Nordeste: BA, SE, PE, AL, PB, RN, CE, PI, MA
        if ($cep >= 40000 && $cep <= 65999) {
            return 'nordeste';
        }

        // Norte: AM, RR, AP, PA, TO, RO, AC
        if (($cep >= 69000 && $cep <= 69299) || // AM
            ($cep >= 69300 && $cep <= 69399) || // RR
            ($cep >= 68900 && $cep <= 68999) || // AP
            ($cep >= 66000 && $cep <= 68899) || // PA
            ($cep >= 77000 && $cep <= 77999) || // TO
            ($cep >= 76800 && $cep <= 76999) || // RO
            ($cep >= 69900 && $cep <= 69999)) { // AC
            return 'norte';
        }

        // Centro-Oeste: DF, GO, MT, MS
        if ($cep >= 70000 && $cep <= 76799) {
            return 'centro-oeste';
        }

        // Padrão: outras regiões
        return 'outras';
    }

    public function confirmarPagamento(Pedido $pedido, array $dadosPagamento = []): Pedido
    {
        if ($pedido->status === 'pago') {
            return $pedido;
        }

        DB::transaction(function () use (&$pedido, $dadosPagamento) {
            $pedido->status = 'pago';
            $pedido->status_pagamento = $dadosPagamento['status_pagamento'] ?? 'approved';
            $pedido->metodo_pagamento = $dadosPagamento['metodo_pagamento'] ?? $pedido->metodo_pagamento;
            $pedido->mercadopago_id = $dadosPagamento['mercadopago_id'] ?? $pedido->mercadopago_id;
            $pedido->mercadopago_status = $dadosPagamento['mercadopago_status'] ?? $pedido->mercadopago_status;
            $pedido->data_aprovacao = $pedido->data_aprovacao ?? now();
            $pedido->save();

            $pedido->loadMissing('itens.produto');
            foreach ($pedido->itens as $item) {
                if ($item->produto) {
                    $item->produto->decrement('estoque', $item->quantidade);
                }
            }
        });

        $this->enviarEmailConfirmacao($pedido);

        return $pedido;
    }

    public function enviarEmailConfirmacao(Pedido $pedido): void
    {
        $pedido = $pedido->fresh('usuario', 'itens');

        // Envia para o cliente
        if ($pedido->usuario && $pedido->usuario->email) {
            Mail::to($pedido->usuario->email)->send(new PedidoConfirmadoMail($pedido, 'cliente'));
        }

        // Envia para a Junta Nova
        $destinatariosAdmin = array_values(array_filter(array_unique([
            env('ADMIN_EMAIL', 'juntanova2025@gmail.com'),
        ])));

        if (! empty($destinatariosAdmin)) {
            Mail::to($destinatariosAdmin)->send(new PedidoConfirmadoMail($pedido, 'admin'));
        }
    }
}
