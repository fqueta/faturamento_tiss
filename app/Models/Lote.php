<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Lote extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
    ];

    protected $fillable = [
        'token',
        'nome',
        'quadra',
        'endereco',
        'numero',
        'cidade',
        'bairro',
        'ativo',
        'autor',
        'cep',
        'complemento',
        'config',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
