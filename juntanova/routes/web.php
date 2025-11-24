<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepoimentosController as AdminDepoimentosController;
use App\Http\Controllers\Admin\EstoqueController;
use App\Http\Controllers\Admin\PedidosController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Site\CarrinhoController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PagamentoController;
use Illuminate\Support\Facades\Route;

Route::get('/up', fn () => ['status' => 'ok'])->name('health-check');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

    Route::get('/cadastro', [AuthController::class, 'showRegister'])->name('cadastro');
    Route::post('/cadastro', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/carrinho', [CarrinhoController::class, 'index'])->name('carrinho');

Route::prefix('api')->middleware('throttle:100,15')->group(function () {
    Route::post('/carrinho', [CarrinhoController::class, 'add'])->name('api.carrinho.add');
    Route::delete('/carrinho/{id}', [CarrinhoController::class, 'remove'])->name('api.carrinho.remove');
});

Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
});

Route::get('/pagamento/sucesso', [PagamentoController::class, 'sucesso'])->name('pagamento.sucesso');
Route::get('/pagamento/pendente', [PagamentoController::class, 'pendente'])->name('pagamento.pendente');
Route::get('/pagamento/falha', [PagamentoController::class, 'falha'])->name('pagamento.falha');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'loginAdmin'])->middleware('throttle:admin-login');

    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/estoque', [EstoqueController::class, 'index'])->name('admin.estoque');
        Route::post('/estoque/{id}', [EstoqueController::class, 'update'])->name('admin.estoque.update');

        Route::get('/pedidos', [PedidosController::class, 'index'])->name('admin.pedidos');
        Route::get('/pedidos/{id}', [PedidosController::class, 'show'])->name('admin.pedidos.show');

        Route::get('/depoimentos', [AdminDepoimentosController::class, 'index'])->name('admin.depoimentos');
        Route::post('/depoimentos/{id}', [AdminDepoimentosController::class, 'update'])->name('admin.depoimentos.update');
    });
});
