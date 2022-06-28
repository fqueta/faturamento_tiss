<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Faturamento extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
    ];
    protected $fillable = [
        'token',
        'nome',
        'id_operadora',
        'mes',
        'ano',
        'autor',
        'type',
        'obs',
        'ativo',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
