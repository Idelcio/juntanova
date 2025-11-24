<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with('usuario')->orderByDesc('data_pedido');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        $pedidos = $query->paginate(20);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function show(int $id)
    {
        $pedido = Pedido::with('usuario', 'itens.produto')->findOrFail($id);

        return view('admin.pedidos.show', compact('pedido'));
    }
}
