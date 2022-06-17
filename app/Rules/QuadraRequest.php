<?php

namespace App\Rules;

use App\Qlib\Qlib;
use Illuminate\Contracts\Validation\Rule;

class QuadraRequest implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $dadosForm;
    public function __construct()
    {
        $this->dadosForm = $_POST;
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
        $dados = $this->dadosForm;
        $ret = true;
        //echo $attribute.' '. $value;
        if(isset($dados['bairro'])){
            //$sql = "SELECT * FROM lotes WHERE $attribute ='$value' AND quadra='".$dados['quadra']."'";
            $totalReg = Qlib::buscaValorDb([
                'tab'=>'quadras',
                'campo_bus'=>$attribute,
                'valor'=>$value,
                'select'=>'nome',
                'compleSql'=>" AND bairro='".$dados['bairro']."' AND ".Qlib::compleDelete(),
                'debug'=>false,
            ]);
            if(!empty($totalReg)){
                $ret = false;
            }
        }
        return $ret;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $d = $this->dadosForm;
        $nomeBairro = Qlib::buscaValorDb([
            'tab'=>'bairros',
            'campo_bus'=>'id',
            'valor'=>$d['bairro'],
            'select'=>'nome',
            'compleSql'=>'',
            'debug'=>false,
        ]);
        $bairro = $nomeBairro?$nomeBairro:$d['bairro'];
        return 'A <b>quadra '.$d['nome'].'</b> no Bairro <b>'.$bairro.'</b> já foi cadastrada!';
    }
}
