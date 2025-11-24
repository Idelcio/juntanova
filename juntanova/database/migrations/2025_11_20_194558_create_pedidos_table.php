<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido', 20)->unique();
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->string('cep', 10)->nullable();
            $table->string('rua')->nullable();
            $table->string('numero', 20)->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('pais', 100)->default('Brasil');
            $table->decimal('valor_produtos', 10, 2);
            $table->decimal('valor_frete', 10, 2);
            $table->decimal('valor_total', 10, 2);
            $table->enum('status', ['pendente', 'pago', 'processando', 'enviado', 'entregue', 'cancelado'])->default('pendente');
            $table->string('metodo_pagamento', 50)->nullable();
            $table->string('status_pagamento', 50)->nullable();
            $table->string('mercadopago_id')->nullable();
            $table->string('mercadopago_status', 50)->nullable();
            $table->timestamp('data_aprovacao')->nullable();
            $table->timestamp('data_pedido')->useCurrent();
            $table->timestamp('data_atualizacao')->useCurrent()->useCurrentOnUpdate();

            // Índices
            $table->index('usuario_id');
            $table->index('status');
            $table->index('data_pedido');
            $table->index('numero_pedido');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
