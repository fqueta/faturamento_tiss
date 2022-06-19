<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Profissional extends Model
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
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
