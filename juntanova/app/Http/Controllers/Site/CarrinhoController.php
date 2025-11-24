<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarrinhoController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);

        return view('site.carrinho', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'produto_id' => ['required', 'exists:produtos,id'],
            'quantidade' => ['required', 'integer', 'min:1'],
        ]);

        $produto = Produto::findOrFail($data['produto_id']);
        $preco = $produto->preco_promocional ?? $produto->preco;

        $cart = Session::get('cart', []);
        $currentQuantity = $cart[$produto->id]['quantidade'] ?? 0;
        $novaQuantidade = $currentQuantity + $data['quantidade'];

        if ($novaQuantidade > $produto->estoque) {
            return response()->json(['message' => 'Estoque insuficiente'], 422);
        }

        $cart[$produto->id] = [
            'produto_id' => $produto->id,
            'nome' => $produto->nome,
            'quantidade' => $novaQuantidade,
            'preco' => $preco,
            'subtotal' => $preco * $novaQuantidade,
            'capsulas' => $produto->capsulas,
        ];

        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Produto adicionado ao carrinho.',
            'cart' => $cart,
        ]);
    }

    public function remove(int $id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return response()->json([
            'message' => 'Item removido.',
            'cart' => $cart,
        ]);
    }
}
