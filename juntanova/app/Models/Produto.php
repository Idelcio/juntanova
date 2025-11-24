<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produtos';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'preco_promocional',
        'estoque',
        'peso',
        'capsulas',
        'dosagem',
        'tipo',
        'ativo',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'preco_promocional' => 'decimal:2',
        'estoque' => 'integer',
        'capsulas' => 'integer',
        'ativo' => 'boolean',
    ];

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
