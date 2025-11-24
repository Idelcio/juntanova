<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depoimento extends Model
{
    use HasFactory;

    protected $table = 'depoimentos';
    public $timestamps = false;
    const CREATED_AT = 'criado_em';

    protected $fillable = [
        'nome',
        'cidade',
        'estado',
        'mensagem',
        'avaliacao',
        'aprovado',
    ];

    protected $casts = [
        'avaliacao' => 'integer',
        'aprovado' => 'boolean',
    ];
}
