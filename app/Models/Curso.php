<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $casts = [
        'config' => 'array',
        'grade' => 'array',
    ];
    protected $fillable = [
        'nome',
        'titulo',
        'url',
        'valor',
        'matricula',
        'parcelas',
        'token',
        'ativo',
        'categoria_id',
        'ativo',
        'destaque',
        'autor',
        'descricao',
        'obs',
        'grade',
        'config',
        'situacao_profissional',
        'qtd_membros',
        'membros',
        'idoso',
        'crianca_adolescente',
        'bcp_bolsa_familia',
        'renda_familiar',
        'doc_imovel',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'autor',
        'obs',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
}
