<?php

namespace Database\Seeders;

use App\Models\Operadora;
use Illuminate\Database\Seeder;

class OperadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            [
                'nome' => 'SABIN SINAI',
                'token' => uniqid(),
                'config' => '{"identidade":"38010265000143","versao_tiss":"3.02.00","tabela_cobranca":"00"}',
                'registro' => '414905',
                'ativo' => 's',
            ],
        ];
        foreach ($arr as $key => $value) {
            Operadora::create($value);
        }
    }
}
