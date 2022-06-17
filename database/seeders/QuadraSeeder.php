<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuadraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quadras')->insert([
            [
                'nome'=>'14',
                'bairro'=>'1',
            ],
            [
                'nome'=>'20',
                'bairro'=>'1',
            ],
            [
                'nome'=>'21',
                'bairro'=>'1',
            ],
            [
                'nome'=>'21.1',
                'bairro'=>'1',
            ],
            [
                'nome'=>'22.2',
                'bairro'=>'1',
            ],
            [
                'nome'=>'22.3',
                'bairro'=>'1',
            ],
            [
                'nome'=>'22.4',
                'bairro'=>'1',
            ],
        ]);
    }
}
