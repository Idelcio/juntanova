<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $table = 'usuarios';

    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = null;

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'telefone',
        'cpf',
        'cep',
        'rua',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'is_admin',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
