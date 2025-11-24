@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Dashboard</h1>
        <p class="text-slate-600">Resumo rápido das vendas e pedidos.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-1">Vendas aprovadas</p>
            <p class="text-2xl font-bold">R$ {{ number_format($totalVendas, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-1">Pedidos</p>
            <p class="text-2xl font-bold">{{ $totalPedidos }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <p class="text-xs uppercase tracking-[0.2em] text-slate-500 mb-1">Clientes</p>
            <p class="text-2xl font-bold">{{ $clientes }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="font-semibold">Pedidos recentes</h2>
                <a href="{{ route('admin.pedidos') }}" class="text-sm text-orange-600">Ver todos</a>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($recentes as $pedido)
                    <div class="py-2 flex items-center justify-between">
                        <div>
                            <p class="font-semibold text-sm">#{{ $pedido->numero_pedido }}</p>
                            <p class="text-xs text-slate-500">{{ $pedido->usuario->nome ?? 'Cliente' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
                            <p class="text-xs uppercase text-slate-500">{{ $pedido->status }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-4">
            <h2 class="font-semibold mb-3">Estoque</h2>
            <div class="space-y-2">
                @foreach($produtos as $produto)
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ $produto->nome }}</span>
                        <span class="font-semibold">{{ $produto->estoque }} un</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
