<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class tagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = [
            ['nome'=>'Tags situação dos cadastros','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.','ordem'=>1],
            ['nome'=>'Tags Tipo do imóvel','obs'=>'Informar os cadastros que estão faltando alguma informação ou documentos.','ordem'=>2],
            [
                'nome'=>'Cadastros Pendentes',
                'pai'=>1,
                'ordem'=>2,
                'obs'=>'São aqueles cadastros socioeconômicos que apresentam pendências como falta de documentos, falta de informações etc. Sem essas pendências resolvidas, o cadastro não se completa e ele não fará parte do processo jurídico via relatório social. É uma das maiores causas de atraso.',
                'config'=>['color'=>'danger','icon'=>'fa fa-times']
            ],
            [
                'nome'=>'Imóveis com registros',
                'pai'=>1,
                'ordem'=>3,
                'obs'=>'São imóveis que são cadastrados, mas que não comporão o processo jurídico por já apresentarem registro imobiliário. Devem ser considerados como cadastros completos.',
                'config'=>['color'=>'info','icon'=>'fas fa-calendar-check']
            ],
            [
                'nome'=>'Recusas',
                'pai'=>1,
                'ordem'=>4,
                'obs'=>'Quando o beneficiário se recusa a fornecer informações ou documentos e, com isso, impede a geração de um novo cadastro socioeconômico. Beneficiários que recusam o cadastramento não participam do projeto, mas devem ser registrados para fins de segurança jurídica futura bem como para informar ao contratante quem foi o beneficiário que recusou a possibilidade. Devem integrar os cadastros completos.',
                'config'=>['color'=>'warning','icon'=>'fas fa-search-minus']
            ],
            [
                'nome'=>'Proprietários não localizados',
                'pai'=>1,
                'ordem'=>5,
                'obs'=>'Pode ser um simples lote sem construção ou até uma casa fechada. Também não compõem o cadastro, mas deve ficar registrada a situação. Ou seja, integram os cadastros completos.',
                'config'=>['color'=>'warning','icon'=>'fa fa-times']
            ],
            ['nome'=>'Residencial','pai'=>2,'ordem'=>1,'obs'=>''],
            ['nome'=>'Comercial','pai'=>2,'ordem'=>2,'obs'=>''],
            ['nome'=>'Lote vago','pai'=>2,'ordem'=>3,'obs'=>''],
            [
                'nome'=>'Cadastros Completos',
                'pai'=>1,
                'ordem'=>1,
                'obs'=>'São cadastros socioeconômicos preenchidos e com toda a documentação necessária para serem encaminhados à Assistência Social para a elaboração do Relatório Social, documento que irá compor o processo jurídico de regularização fundiária. Sem o cadastro completo não há condições de avançar no processo.',
                'config'=>['color'=>'success','icon'=>'fa fa-check']
            ],
        ];

        foreach ($arr as $key => $value) {
            $d = $value;
            $d['token']=uniqid();
            Tag::create($d);
        }
    }
}
