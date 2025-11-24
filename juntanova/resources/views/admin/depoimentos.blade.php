@extends('layouts.admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold mb-1">Depoimentos</h1>
        <p class="text-slate-600">Aprove ou rejeite comentários enviados.</p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-slate-100 text-left">
                    <th class="px-4 py-3">Nome</th>
                    <th class="px-4 py-3">Mensagem</th>
                    <th class="px-4 py-3">Avaliação</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($depoimentos as $dep)
                    <tr class="border-t border-slate-100">
                        <td class="px-4 py-3">
                            <p class="font-semibold">{{ $dep->nome }}</p>
                            <p class="text-xs text-slate-500">{{ $dep->cidade }} {{ $dep->estado ? '- '.$dep->estado : '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-700">{{ $dep->mensagem }}</td>
                        <td class="px-4 py-3 text-orange-500">{{ str_repeat('★', (int) $dep->avaliacao) }}</td>
                        <td class="px-4 py-3 uppercase text-xs">{{ $dep->aprovado ? 'Aprovado' : 'Pendente' }}</td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.depoimentos.update', $dep->id) }}" class="inline">
                                @csrf
                                <input type="hidden" name="aprovado" value="{{ $dep->aprovado ? 0 : 1 }}">
                                <button class="px-3 py-2 rounded-lg {{ $dep->aprovado ? 'bg-slate-200 text-slate-800' : 'bg-orange-500 text-white' }}">
                                    {{ $dep->aprovado ? 'Reprovar' : 'Aprovar' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4">
            {{ $depoimentos->links() }}
        </div>
    </div>
@endsection
