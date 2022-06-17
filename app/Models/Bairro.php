<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Bairro extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'token',
        'nome',
        'cidade',
        'area',
        'area',
        'matricula',
        'total_quadras',
        'total_lotes',
        'obs',
        'ativo',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
}
