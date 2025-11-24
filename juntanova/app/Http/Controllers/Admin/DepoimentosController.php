<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depoimento;
use Illuminate\Http\Request;

class DepoimentosController extends Controller
{
    public function index()
    {
        $depoimentos = Depoimento::orderByDesc('criado_em')->paginate(20);

        return view('admin.depoimentos', compact('depoimentos'));
    }

    public function update(Request $request, int $id)
    {
        $depoimento = Depoimento::findOrFail($id);

        $data = $request->validate([
            'aprovado' => ['required', 'boolean'],
        ]);

        $depoimento->update(['aprovado' => $data['aprovado']]);

        return redirect()->route('admin.depoimentos')->with('success', 'Depoimento atualizado.');
    }
}
