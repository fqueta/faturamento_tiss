<?php

namespace App\Http\Requests;

use App\Rules\QuadraRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuadraRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nome' => ['required',new QuadraRequest()],
            'bairro' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nome.required' => 'O Número da quadra é obrigatório',
        ];
    }
}
