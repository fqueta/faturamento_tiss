<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Qoption extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'token',
        'nome',
        'url',
        'obs',
        'valor',
        'painel',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
}
