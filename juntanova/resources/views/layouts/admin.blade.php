<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Painel - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-900">
    <header class="bg-slate-900 text-white">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-orange-300">Painel Administrativo</p>
                <a href="{{ route('admin.dashboard') }}" class="text-xl font-semibold">{{ config('app.name') }}</a>
            </div>
            <nav class="flex items-center gap-3 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-300 {{ request()->routeIs('admin.dashboard') ? 'text-orange-300' : '' }}">Dashboard</a>
                <a href="{{ route('admin.estoque') }}" class="hover:text-orange-300 {{ request()->routeIs('admin.estoque') ? 'text-orange-300' : '' }}">Estoque</a>
                <a href="{{ route('admin.pedidos') }}" class="hover:text-orange-300 {{ request()->routeIs('admin.pedidos*') ? 'text-orange-300' : '' }}">Pedidos</a>
                <a href="{{ route('admin.depoimentos') }}" class="hover:text-orange-300 {{ request()->routeIs('admin.depoimentos') ? 'text-orange-300' : '' }}">Depoimentos</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-3 py-2 rounded-md bg-orange-500 hover:bg-orange-400 text-white font-semibold">Sair</button>
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
