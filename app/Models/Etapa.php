<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Etapa extends Model
{
    use HasFactory,Notifiable;
    protected $fillable = [
        'token',
        'nome',
        'ordem',
        'ativo',
        'autor',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado'
    ];
    public function familias()
    {
        return $this->belongsTo('App\Models\Familia');
    }
}
