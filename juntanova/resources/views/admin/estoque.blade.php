@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Estoque dos produtos</h1>
        <p class="text-slate-600">Atualize preço, promoção e saldo dos kits.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-4">
        @foreach($produtos as $produto)
            <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
                <h2 class="text-lg font-semibold mb-1">{{ $produto->nome }}</h2>
                <p class="text-xs text-slate-500 mb-3">ID #{{ $produto->id }}</p>
                <form method="POST" action="{{ route('admin.estoque.update', $produto->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs text-slate-600">Estoque</label>
                        <input type="number" name="estoque" min="0" value="{{ $produto->estoque }}" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">Preço (R$)</label>
                        <input type="number" step="0.01" name="preco" value="{{ $produto->preco }}" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">Preço promocional (R$)</label>
                        <input type="number" step="0.01" name="preco_promocional" value="{{ $produto->preco_promocional }}" class="w-full rounded-lg border border-slate-200 px-3 py-2">
                    </div>
                    <label class="inline-flex items-center gap-2 text-sm">
                        <input type="checkbox" name="ativo" value="1" {{ $produto->ativo ? 'checked' : '' }}>
                        Ativo
                    </label>
                    <button class="w-full rounded-lg bg-orange-500 text-white font-semibold py-2 hover:bg-orange-600" type="submit">Salvar</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
