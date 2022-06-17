<?php

namespace Database\Seeders;

use App\Models\Etapa;
use Illuminate\Database\Seeder;

class etapaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'0. Selagem dos imóveis (topografia + equipe de campo)','ativo'=>'n'],
            ['nome'=>'1. Cadastros (assis social e equipe de campo)','ativo'=>'s'],
            ['nome'=>'2. Elaboração do relatório social (assist social)','ativo'=>'s'],
            ['nome'=>'3. Levantamentos topográficos individualizados','ativo'=>'s'],
            ['nome'=>'4. Montagem processo jurídico','ativo'=>'s'],
            ['nome'=>'5. Atendimentos','ativo'=>'s'],
            ['nome'=>'6. Finalização do processo de CRF','ativo'=>'s'],
            ['nome'=>'7. Entrega das CRFs à Contratante (SMMAGU/PM CMD)','ativo'=>'s'],
        ];
        foreach ($arr as $key => $value) {
            Etapa::create([
                'nome'=>$value['nome'],
                'ativo'=>$value['ativo'],
                'token'=>uniqid(),
            ]);
        }
    }
}
