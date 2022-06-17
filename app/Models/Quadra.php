<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Quadra extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
    ];

    protected $fillable = [
        'token',
        'nome',
        'ativo',
        'bairro',
        'autor',
        'obs',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
