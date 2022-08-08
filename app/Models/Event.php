<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Event extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
    ];
    protected $fillable = [
        'token',
        'user_id',
        'action',
        'tab',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];

}
