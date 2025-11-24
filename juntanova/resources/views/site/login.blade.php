@php $isAdmin = request()->is('admin/*'); @endphp
@extends('layouts.site', ['title' => $isAdmin ? 'Login administrador' : 'Entrar'])

@section('content')
    <div class="max-w-md mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
        <p class="text-orange-500 text-xs uppercase tracking-[0.2em] mb-2">{{ $isAdmin ? 'Acesso restrito' : 'Área do cliente' }}</p>
        <h1 class="text-2xl font-bold mb-4">{{ $isAdmin ? 'Login do Administrador' : 'Entrar na sua conta' }}</h1>
        <form method="POST" action="{{ $isAdmin ? route('admin.login') : route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm text-slate-600">Email</label>
                <input type="email" name="email" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2" value="{{ old('email') }}">
            </div>
            <div>
                <label class="text-sm text-slate-600">Senha</label>
                <input type="password" name="password" required class="mt-1 w-full rounded-lg border border-slate-200 px-3 py-2">
            </div>
            <button type="submit" class="w-full rounded-lg bg-slate-900 text-white font-semibold py-3 hover:bg-slate-800">Entrar</button>
        </form>
        @unless($isAdmin)
            <p class="text-sm text-slate-600 mt-3">Ainda não tem conta? <a href="{{ route('cadastro') }}" class="text-orange-600 font-semibold">Criar conta</a></p>
        @endunless
    </div>
@endsection
