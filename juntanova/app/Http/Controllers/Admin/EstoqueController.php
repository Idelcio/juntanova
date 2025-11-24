<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;

class EstoqueController extends Controller
{
    public function index()
    {
        $produtos = Produto::orderBy('id')->get();

        return view('admin.estoque', compact('produtos'));
    }

    public function update(Request $request, int $id)
    {
        $produto = Produto::findOrFail($id);

        $data = $request->validate([
            'estoque' => ['required', 'integer', 'min:0'],
            'preco' => ['required', 'numeric', 'min:0'],
            'preco_promocional' => ['nullable', 'numeric', 'min:0'],
            'ativo' => ['nullable', 'boolean'],
        ]);

        $produto->update([
            'estoque' => $data['estoque'],
            'preco' => $data['preco'],
            'preco_promocional' => $data['preco_promocional'] ?? null,
            'ativo' => $request->boolean('ativo'),
        ]);

        return redirect()->route('admin.estoque')->with('success', 'Produto atualizado.');
    }
}
