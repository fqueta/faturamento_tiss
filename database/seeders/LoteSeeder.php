<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lotes')->insert([
            [
                'nome'=>rand(1,200),
                'quadra'=>rand(1,4),
                'bairro'=>'1',
                'endereco' => $this->faker->address(),
            ]
        ]);
    }
}
