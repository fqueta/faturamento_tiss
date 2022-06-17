<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BeneficiarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('beneficiarios')->insert([
            [
                'tipo'=>1,
                'nome'=>'Não Localizado',
                'ativo'=>'s',
            ]
        ]);
    }
}
