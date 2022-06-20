<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Guia extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
        'meta' => 'array',
    ];
    protected $fillable = [
        'token',
        'nome',
        'ordem',
        'ativo',
        'type',
        'autor',
        'obs',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
