<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estadocivil extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'token'
    ];
}
