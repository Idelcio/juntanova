@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <p class="text-xs text-slate-500 uppercase tracking-[0.2em]">Pedido</p>
            <h1 class="text-2xl font-bold">#{{ $pedido->numero_pedido }}</h1>
        </div>
        <a href="{{ route('admin.pedidos') }}" class="text-sm text-slate-600 hover:text-orange-600">Voltar</a>
    </div>

    <div class="grid md:grid-cols-3 gap-4 mb-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 md:col-span-2">
            <h3 class="font-semibold mb-3">Itens</h3>
            <div class="divide-y divide-slate-100">
                @foreach($pedido->itens as $item)
                    <div class="py-2 flex items-center justify-between">
                        <div>
                            <p class="font-semibold">{{ $item->nome }}</p>
                            <p class="text-xs text-slate-500">Qtd: {{ $item->quantidade }}</p>
                        </div>
                        <p class="font-semibold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 space-y-2">
            <h3 class="font-semibold">Resumo</h3>
            <p class="text-sm text-slate-600"><strong>Status:</strong> {{ $pedido->status }}</p>
            <p class="text-sm text-slate-600"><strong>Total:</strong> R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
            <p class="text-sm text-slate-600"><strong>Frete:</strong> R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</p>
            <p class="text-sm text-slate-600"><strong>Cliente:</strong> {{ $pedido->usuario->nome ?? '-' }} ({{ $pedido->usuario->email ?? '-' }})</p>
            <p class="text-sm text-slate-600"><strong>Endereço:</strong> {{ $pedido->rua }}, {{ $pedido->numero }} {{ $pedido->bairro ? '- '.$pedido->bairro : '' }} - {{ $pedido->cidade }}/{{ $pedido->estado }}</p>
        </div>
    </div>
@endsection
