<?php

namespace Database\Seeders;

use App\Models\Estadocivil;
use Illuminate\Database\Seeder;

class estadocivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            'Solteiro(a)',
            'Casado(a)',
            'Divorciado(a)',
            'Separado(a)',
            'Separado(a) / Em processo de divórcio',
            'Separado(a) / não com processo de divórcio',
            'Viúvo(a)',
            'União Estável sem registro',
            'União Estável com registro',
            'Outro',
        ];
        foreach ($arr as $key => $value) {
            Estadocivil::create([
                'nome'=>$value,
                'token'=>uniqid(),
            ]);
        }
    }
}
