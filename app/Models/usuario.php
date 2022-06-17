<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'obs',
        'ativo',
        'data_nasci',
        'data_batismo',
        'tel',
        'endereco'
    ];
}
