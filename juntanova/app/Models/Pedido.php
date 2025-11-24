<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    const CREATED_AT = 'data_pedido';
    const UPDATED_AT = 'data_atualizacao';

    protected $fillable = [
        'numero_pedido',
        'usuario_id',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'valor_produtos',
        'valor_frete',
        'valor_total',
        'status',
        'metodo_pagamento',
        'status_pagamento',
        'mercadopago_id',
        'mercadopago_status',
        'data_aprovacao',
    ];

    protected $casts = [
        'valor_produtos' => 'decimal:2',
        'valor_frete' => 'decimal:2',
        'valor_total' => 'decimal:2',
        'data_aprovacao' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
