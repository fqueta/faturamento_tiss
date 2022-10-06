<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QoptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('qoptions')->insert([
            [
                'nome'=>'Versão do App',
                'url'=>'version',
                'valor'=>'1.0.1',
            ],
        ]);
    }
}
