<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Menu extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'categoria',
        'description',
        'url',
        'pai',
        'route',
        'icon',
        'actived',
    ];
}
