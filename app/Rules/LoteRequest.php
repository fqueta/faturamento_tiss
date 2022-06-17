<?php

namespace App\Rules;

use App\Qlib\Qlib;
use Illuminate\Contracts\Validation\Rule;

class LoteRequest implements Rule
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
        if(isset($dados['quadra'])){
            //$sql = "SELECT * FROM lotes WHERE $attribute ='$value' AND quadra='".$dados['quadra']."'";
            $totalReg = Qlib::buscaValorDb([
                'tab'=>'lotes',
                'campo_bus'=>$attribute,
                'valor'=>$value,
                'select'=>'nome',
                'compleSql'=>" AND quadra='".$dados['quadra']."' AND ".Qlib::compleDelete(),
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
        $nomeQuadra = Qlib::buscaValorDb([
            'tab'=>'quadras',
            'campo_bus'=>'id',
            'valor'=>$d['quadra'],
            'select'=>'nome',
            'compleSql'=>'',
            'debug'=>false,
        ]);
        $nquadra = $nomeQuadra?$nomeQuadra:$d['quadra'];
        return 'O <b>Lote '.$d['nome'].'</b> na Quadra <b>'.$nquadra.'</b> já foi cadastrado!';
    }
}
