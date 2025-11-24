<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\Usuario;

class DashboardController extends Controller
{
    public function index()
    {
        $totalVendas = Pedido::where('status', 'pago')->sum('valor_total');
        $totalPedidos = Pedido::count();
        $clientes = Usuario::where('is_admin', false)->count();

        $recentes = Pedido::orderByDesc('data_pedido')->limit(5)->get();
        $produtos = Produto::orderBy('id')->get();

        return view('admin.dashboard', compact('totalVendas', 'totalPedidos', 'clientes', 'recentes', 'produtos'));
    }
}
