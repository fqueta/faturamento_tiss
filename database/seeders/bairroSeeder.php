<?php

namespace Database\Seeders;

use App\Models\Bairro;
use Illuminate\Database\Seeder;

class bairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'Matozinhos','matricula'=>'1262'],
            //['nome'=>'Matozinhos sem matrícula','matricula'=>''],
            ['nome'=>'Novo Matozinhos','matricula'=>'1262'],
            //['nome'=>'Novo Matozinhos sem matricula','matricula'=>''],
            ['nome'=>'Córrego Pereira','matricula'=>'657'],
            //['nome'=>'Córrego Pereira sem matricula','matricula'=>''],
        ];
        foreach ($arr as $key => $value) {
            Bairro::create([
                'nome'=>$value['nome'],
                'matricula'=>$value['matricula'],
                'token'=>uniqid(),
            ]);
        }
    }
}
