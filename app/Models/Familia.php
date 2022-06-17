<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Familia extends Model
{
    use HasFactory,Notifiable;
    protected $casts = [
        'config' => 'array',
        'loteamento' => 'array',
        'tags' => 'array',
    ];
    protected $fillable = [
        'token',
        'area_alvo',
        'tipo_residencia',
        'etapa',
        'loteamento',
        'complemento_lote',
        'id_beneficiario',
        'id_conjuge',
        'matricula',
        'quadra',
        'lote',
        'nome_completo',
        'cpf',
        'nome_conjuge',
        'cpf_conjuge',
        'telefone',
        'escolaridade',
        'estado_civil',
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
        'tags',
        'config',
        'excluido',
        'reg_excluido',
        'deletado',
        'reg_deletado',
    ];
    public function etapa()
    {
        return $this->hasOne('App\Models\Etapa');
    }
}
