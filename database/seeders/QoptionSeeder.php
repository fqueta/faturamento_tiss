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
                'nome'=>'Cadastro do Município',
                'url'=>'cad_municipio',
                'valor'=>'{
                    "municipio":{
                        "razao":"Município de Conceição do Mato Dentro/MG",
                        "tipo":"pessoa Jurídica de Direito Público Interno",
                        "cnpj":"18.303.156/0001-07",
                        "endereco":"Rua Daniel de Carvalho",
                        "numero":"161",
                        "bairro":"Centro",
                        "cidade":"Conceição do Mato Dentro",
                        "cep":"35.680-000"
                    },
                    "representante":{
                        "cargo":"prefeito",
                        "nome":"José Fernando Aparecido de Oliveira",
                        "nacionalidade":"brasileiro",
                        "estado_civil":"casado",
                        "rg":"M-3.618.630",
                        "cpf":"032.412.426-09",
                        "endereco":"Rua Raul Soares",
                        "numero":"253",
                        "bairro":"Centro",
                        "cidade":"Conceição do Mato Dentro",
                        "cep":"35.680-000"
                    }
                }',
            ],
            [
                'nome'=>'Declarações adicionais sobre a posse',
                'url'=>'opc_declara_posse',
                'valor'=>'[
                    {"label":"compra e venda particular"},
                    {"label":"Permuta"},
                    {"label":"doação particular sem contrato"},
                    {"label":"herança de inventário pendente de abertura"},
                    {"label":"herança de inventário concluído e não registrado"},
                    {"label":"escritura pública de cessão de direitos hereditários"},
                    {"label":"Invasão"}
                ]',
            ]
        ]);
    }
}
