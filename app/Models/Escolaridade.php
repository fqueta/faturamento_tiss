<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Escolaridade extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'token',
        'nome',
        'ativo',
        'autor',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
