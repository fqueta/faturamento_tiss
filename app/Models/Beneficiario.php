<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    use HasFactory;
    protected $casts = [
        'config' => 'array',
    ];
    protected $fillable = [
        'tipo',
        'nome',
        'email',
        'sexo',
        'image',
        'ativo',
        'autor',
        'cpf',
        'conjuge',
        'config',
        'token',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
}
