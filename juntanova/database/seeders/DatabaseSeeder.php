<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Produto;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedAdmin();
        $this->seedProdutos();
    }

    private function seedAdmin(): void
    {
        if (Usuario::where('email', 'michel@juntanova.com.br')->exists()) {
            return;
        }

        Usuario::create([
            'nome' => 'Administrador',
            'email' => 'michel@juntanova.com.br',
            'senha' => Hash::make('juntanova@2025'),
            'telefone' => '',
            'cpf' => '',
            'is_admin' => true,
        ]);
    }

    private function seedProdutos(): void
    {
        $produtos = [
            [
                'id' => 1,
                'nome' => 'Kit 1 Mês',
                'descricao' => '30 cápsulas | Tratamento 1 mês',
                'preco' => 139.00,
                'preco_promocional' => null,
                'estoque' => 100,
                'capsulas' => 30,
            ],
            [
                'id' => 2,
                'nome' => 'Kit 3 Meses',
                'descricao' => '90 cápsulas | Tratamento 3 meses',
                'preco' => 375.00,
                'preco_promocional' => null,
                'estoque' => 100,
                'capsulas' => 90,
            ],
            [
                'id' => 3,
                'nome' => 'Kit 5 Meses',
                'descricao' => '150 cápsulas | Tratamento 5 meses',
                'preco' => 556.00,
                'preco_promocional' => null,
                'estoque' => 100,
                'capsulas' => 150,
            ],
        ];

        foreach ($produtos as $produto) {
            Produto::updateOrCreate(['id' => $produto['id']], $produto);
        }
    }
}
