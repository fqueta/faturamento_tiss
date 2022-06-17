<?php

namespace App\Http\Requests;

use App\Rules\familyRules;
use App\Rules\FullName;
use App\Rules\RightCpf;
use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'bairro'=>['required'],
            //'etapa'=>['required',new familyRules],
            'tipo_residencia'=>['required',new familyRules],
            //'area_alvo'=>['required'],
            'loteamento'=>['required'],
            //'matricula'=>['required'],
            'quadra'=>['required'],
            //'lote'=>['required'],
            //'nome_completo'=>[new FullName],
            //'escolaridade'=>['required'],
            //'estado_civil'=>'required',
            //'cpf'=>[new RightCpf],
        ];
    }
    public function messages()
    {
        return [
            'etapa.required' => 'A seleção da Etapa é obrigatória',
            'bairro.required' => 'A seleção da Área é obrigatório',
            'quadra.required' => 'A seleção da Quadra é obrigatória',
            'tel.required' => 'O Telefone é obrigatório',
            'cpf.unique' => 'Este CPF já está sendo utilizado',
            'cpf.required' => 'O CPF é obrigatório',
            'loteamento.required' => 'Informações do lote são necessárias',
        ];
    }
}
