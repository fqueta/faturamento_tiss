<?php

namespace App\Http\Requests;

use App\Rules\familyRules;
use App\Rules\FullName;
use App\Rules\RightCpf;
use Illuminate\Foundation\Http\FormRequest;

class StoreBeneficiarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules()
    {
        return [
            'nome'=>['required',new FullName],
            'cpf'=>[new RightCpf],
            //'cpf'=>['unique:beneficiarios',new RightCpf],
        ];
    }
    public function messages()
    {
        return [
            'nome.required' => 'O Nome é obrigatório',
            'cpf.unique' => 'Este CPF já está sendo utilizado',
            //'cpf.required' => 'O CPF é obrigatório',
        ];
    }
}
