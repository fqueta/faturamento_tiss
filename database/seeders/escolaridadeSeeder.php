<?php

namespace Database\Seeders;

use App\Models\Escolaridade;
use Illuminate\Database\Seeder;

class escolaridadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            'Educação infantil',
            'Nunca Estudou/Não Assina',
            'Fundamental Incompleto',
            'Fundamental Completo',
            'Médio Incompleto',
            'Médio Completo',
            'Superior Incompleto',
            'Superior',
            'Pós-graduação',
            'Mestrado',
            'Doutorado',
        ];
        foreach ($arr as $key => $value) {
            Escolaridade::create([
                'nome'=>$value,
                'token'=>uniqid(),
            ]);
        }
    }
}
