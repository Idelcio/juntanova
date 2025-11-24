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
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->default('Junta Nova');
            $table->text('descricao')->nullable();
            $table->decimal('preco', 10, 2)->default(139.00);
            $table->decimal('preco_promocional', 10, 2)->nullable();
            $table->integer('estoque')->default(0);
            $table->string('peso', 20)->default('45g');
            $table->integer('capsulas')->default(30);
            $table->string('dosagem', 50)->default('500mg cada');
            $table->string('tipo', 100)->default('100% Natural');
            $table->boolean('ativo')->default(true);
            $table->timestamp('data_criacao')->useCurrent();

            // Índice
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
