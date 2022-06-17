<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class _upload extends Model
{
    use HasFactory;
    protected $fillable = [
        'token_produto',
        'pasta',
        'nome',
        'ordem',
        'obs',
        'config',
    ];
}
