<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoItem extends Model
{
    use HasFactory;

    protected $table = 'pedido_itens';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'produto_id',
        'nome',
        'quantidade',
        'preco',
        'subtotal',
    ];

    protected $casts = [
        'quantidade' => 'integer',
        'preco' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
