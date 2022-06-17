<?php

namespace App\Http\Requests;

use App\Rules\LoteRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreLoteRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => ['required',new LoteRequest()],
            'quadra' => ['required'],
        ];
    }
    public function messages()
    {
        return [
            'nome.required' => 'O Número do lote é obrigatório',
        ];
    }
}
