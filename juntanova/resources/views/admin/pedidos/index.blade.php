@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-2xl font-bold mb-1">Pedidos</h1>
            <p class="text-slate-600">Visualize todos os pedidos recebidos.</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <select name="status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <option value="">Todos</option>
                @foreach(['pendente','pago','processando','enviado','entregue','cancelado'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button class="px-3 py-2 rounded-lg bg-slate-900 text-white text-sm">Filtrar</button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-100 text-left">
                    <th class="px-4 py-3">Pedido</th>
                    <th class="px-4 py-3">Cliente</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Total</th>
                    <th class="px-4 py-3">Data</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3 font-semibold">#{{ $pedido->numero_pedido }}</td>
                        <td class="px-4 py-3">{{ $pedido->usuario->nome ?? '-' }}</td>
                        <td class="px-4 py-3 uppercase text-xs">{{ $pedido->status }}</td>
                        <td class="px-4 py-3 font-semibold">R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($pedido->data_pedido)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="text-orange-600 font-semibold text-sm">Detalhes</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $pedidos->withQueryString()->links() }}
        </div>
    </div>
@endsection
