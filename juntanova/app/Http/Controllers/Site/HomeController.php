<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Depoimento;
use App\Models\Produto;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        $produtos = Produto::where('ativo', true)->orderBy('id')->get();
        $depoimentos = Depoimento::where('aprovado', true)
            ->orderByDesc('criado_em')
            ->limit(6)
            ->get();

        $cart = Session::get('cart', []);

        return view('site.index', compact('produtos', 'depoimentos', 'cart'));
    }
}
