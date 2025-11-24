@extends('layouts.site', ['title' => 'Carrinho'])

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-orange-500 text-xs uppercase tracking-[0.2em]">Seu carrinho</p>
            <h1 class="text-2xl font-bold">Revisar itens e finalizar compra</h1>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-slate-600 hover:text-orange-600">Continuar comprando</a>
    </div>

    @php
        $subtotal = collect($cart)->sum('subtotal');
        $pedidoService = app(\App\Services\PedidoService::class);
        $freteEstimado = $pedidoService->calcularFrete(auth()->user()->cep ?? request('cep'));
        $total = $subtotal + $freteEstimado;
    @endphp

    @if(empty($cart))
        <div class="bg-white border border-slate-200 rounded-xl p-6 text-slate-700">
            Seu carrinho está vazio. Adicione um kit para continuar.
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white border border-slate-200 rounded-xl p-5">
                <div class="space-y-4">
                    @foreach($cart as $item)
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <div>
                                <p class="font-semibold">{{ $item['nome'] }}</p>
                                <p class="text-sm text-slate-500">Quantidade: {{ $item['quantidade'] }} | Cápsulas: {{ $item['capsulas'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</p>
                                <form method="POST" action="{{ url('/api/carrinho/'.$item['produto_id']) }}" data-remove-cart class="text-xs text-red-500 mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Remover</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-5 space-y-4">
                <div>
                    <h2 class="text-lg font-semibold mb-2">Resumo</h2>
                    <div class="flex justify-between text-sm text-slate-600 mb-1">
                        <span>Produtos</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600 mb-1">
                        <span>Frete estimado</span>
                        <span>R$ {{ number_format($freteEstimado, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg mt-2">
                        <span>Total</span>
                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                </div>

                @auth
                    <form action="{{ route('checkout') }}" method="POST" class="space-y-3">
                        @csrf
                        <h3 class="text-sm font-semibold text-slate-700">Endereço de entrega</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <input required name="cep" placeholder="CEP" value="{{ old('cep', auth()->user()->cep) }}" class="col-span-2 rounded-lg border border-slate-200 px-3 py-2">
                            <input required name="rua" placeholder="Rua" value="{{ old('rua', auth()->user()->rua) }}" class="col-span-2 rounded-lg border border-slate-200 px-3 py-2">
                            <input required name="numero" placeholder="Número" value="{{ old('numero', auth()->user()->numero) }}" class="rounded-lg border border-slate-200 px-3 py-2">
                            <input name="complemento" placeholder="Complemento" value="{{ old('complemento', auth()->user()->complemento) }}" class="rounded-lg border border-slate-200 px-3 py-2">
                            <input required name="bairro" placeholder="Bairro" value="{{ old('bairro', auth()->user()->bairro) }}" class="rounded-lg border border-slate-200 px-3 py-2">
                            <input required name="cidade" placeholder="Cidade" value="{{ old('cidade', auth()->user()->cidade) }}" class="rounded-lg border border-slate-200 px-3 py-2">
                            <input required name="estado" placeholder="UF" maxlength="2" value="{{ old('estado', auth()->user()->estado) }}" class="rounded-lg border border-slate-200 px-3 py-2">
                        </div>
                        <button type="submit" class="w-full rounded-lg bg-orange-500 text-white font-semibold py-3 hover:bg-orange-600">Pagar com Mercado Pago</button>
                    </form>
                @else
                    <p class="text-sm text-slate-600">Faça login para finalizar sua compra.</p>
                    <a href="{{ route('login') }}" class="w-full inline-flex justify-center rounded-lg bg-slate-900 text-white font-semibold py-3 hover:bg-slate-800">Entrar</a>
                @endauth
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            document.querySelectorAll('[data-remove-cart]').forEach((form) => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    try {
                        const response = await fetch(form.action, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                            },
                        });
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Não foi possível remover o item.');
                        }
                    } catch (error) {
                        alert('Erro: ' + error.message);
                    }
                });
            });
        });
    </script>
@endsection
