@extends('layouts.site', ['title' => 'Pagamento pendente'])

@section('content')
    <div class="max-w-xl mx-auto bg-white border border-amber-200 rounded-xl shadow-sm p-6 text-center">
        <div class="mx-auto h-14 w-14 rounded-full bg-amber-400 text-white flex items-center justify-center text-2xl mb-3">!</div>
        <h1 class="text-2xl font-bold mb-2">Pagamento pendente</h1>
        <p class="text-slate-600 mb-4">Seu pagamento ainda não foi confirmado pelo Mercado Pago. Assim que for aprovado, seu pedido será liberado automaticamente.</p>
        @if($pedido)
            <p class="font-semibold">Pedido #{{ $pedido->numero_pedido }}</p>
            <p class="text-slate-600">Valor: R$ {{ number_format($pedido->valor_total, 2, ',', '.') }}</p>
        @endif
        <a href="{{ route('home') }}" class="mt-4 inline-flex justify-center px-4 py-3 rounded-lg bg-slate-900 text-white font-semibold">Voltar para a loja</a>
    </div>
@endsection
