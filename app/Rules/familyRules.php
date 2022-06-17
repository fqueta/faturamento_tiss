<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class familyRules implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(!empty($value) && ($value=='cad' || $value=='ger'))
            return false;
        else
            return true;
    }

    public function message()
    {
        return 'Por favor selecione uma opção válida.';
    }
}
