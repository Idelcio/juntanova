@extends('layouts.site', ['title' => 'Criar conta'])

@section('content')
    <div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <p class="text-orange-500 text-xs uppercase tracking-[0.2em] mb-2">Cadastro</p>
        <h1 class="text-2xl font-bold mb-4">Crie sua conta para comprar</h1>
        <form method="POST" action="{{ route('cadastro') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="text-sm text-slate-600">Nome</label>
                    <input type="text" name="nome" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('nome') }}">
                </div>
                <div class="col-span-2">
                    <label class="text-sm text-slate-600">Email</label>
                    <input type="email" name="email" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('email') }}">
                </div>
                <div>
                    <label class="text-sm text-slate-600">CPF</label>
                    <input type="text" name="cpf" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('cpf') }}">
                </div>
                <div>
                    <label class="text-sm text-slate-600">Telefone</label>
                    <input type="text" name="telefone" class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('telefone') }}">
                </div>
                <div>
                    <label class="text-sm text-slate-600">Senha</label>
                    <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                </div>
                <div>
                    <label class="text-sm text-slate-600">Confirmar senha</label>
                    <input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
                </div>
                <div class="col-span-2 pt-2 border-t border-dashed border-slate-200">
                    <p class="text-sm text-slate-600 mb-2">Endereço (opcional, pode preencher no checkout)</p>
                </div>
                <div>
                    <input type="text" name="cep" placeholder="CEP" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('cep') }}">
                </div>
                <div>
                    <input type="text" name="estado" placeholder="UF" maxlength="2" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('estado') }}">
                </div>
                <div class="col-span-2">
                    <input type="text" name="rua" placeholder="Rua" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('rua') }}">
                </div>
                <div>
                    <input type="text" name="numero" placeholder="Número" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('numero') }}">
                </div>
                <div>
                    <input type="text" name="complemento" placeholder="Complemento" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('complemento') }}">
                </div>
                <div>
                    <input type="text" name="bairro" placeholder="Bairro" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('bairro') }}">
                </div>
                <div>
                    <input type="text" name="cidade" placeholder="Cidade" class="w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('cidade') }}">
                </div>
            </div>
            <button type="submit" class="w-full rounded-lg bg-orange-500 text-white font-semibold py-3 hover:bg-orange-600">Criar conta</button>
        </form>
    </div>
@endsection
