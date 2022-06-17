<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistencia extends Model
{
    use HasFactory;
    protected $fillable = [
        'mes',
        'ano',
        'num_reuniao',
        'qtd',
        'num_semana',
        'total',
        'media',
    ];
}
