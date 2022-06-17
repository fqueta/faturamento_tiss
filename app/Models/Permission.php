<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
    ];
    protected $fillable = [
        'name',
        'description',
        'active',
        'id_menu',
    ];
}
