<?php

namespace App\Http\Requests;
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
            'nome_completo'=>['required',new FullName],
            'telefone'=>'required',
            'cpf'=>['required','unique:familias',new RightCpf],
        ];
    }
    public function messages()
    {
        return [
            'nome_completo.required' => 'O Nome é obrigatório',
            'tel.required' => 'O Telefone é obrigatório',
            'cpf.unique' => 'Este CPF já está sendo utilizado',
            'cpf.required' => 'O CPF é obrigatório',
        ];
    }
}
