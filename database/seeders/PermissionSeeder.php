<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPermiss = [
            "master"=>'{"ler":{"painel":"s","pacientes":"s","beneficiarios":"s","agenda":"s","guias":"s","internacao":"s","faturamento":"s","fechar_lote":"s","gerenciar_lote":"s","relatorios":"s","rel_guias":"s","rel_lotes":"s","rel_valores":"s","config":"s","solicitantes":"s","executantes":"s","tabelas":"s","documentos":"s","sistema":"s","users":"s","permissions":"s","operadoras":"s","escolaridades":"s","estado-civil":"s","tags":"s","qoptions":"s"},"create":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","rel_valores":"s","solicitantes":"s","executantes":"s","tabelas":"s","documentos":"s","sistema":"s","users":"s","permissions":"s","operadoras":"s","escolaridades":"s","estado-civil":"s","tags":"s","qoptions":"s"},"update":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","rel_valores":"s","solicitantes":"s","executantes":"s","tabelas":"s","documentos":"s","sistema":"s","users":"s","permissions":"s","operadoras":"s","escolaridades":"s","estado-civil":"s","tags":"s","qoptions":"s"},"delete":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","rel_valores":"s","solicitantes":"s","executantes":"s","tabelas":"s","documentos":"s","sistema":"s","users":"s","permissions":"s","operadoras":"s","escolaridades":"s","estado-civil":"s","tags":"s","qoptions":"s"},"ler_arquivos":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","rel_valores":"s","solicitantes":"s","executantes":"s","tabelas":"s","documentos":"s","sistema":"s","users":"s","permissions":"s","operadoras":"s","escolaridades":"s","estado-civil":"s","tags":"s","qoptions":"s"}}',
            "admin"=>'{"ler":{"painel":"s","pacientes":"s","beneficiarios":"s","guias":"s","internacao":"s","faturamento":"s","fechar_lote":"s","gerenciar_lote":"s","relatorios":"s","rel_guias":"s","rel_lotes":"s","config":"s","solicitantes":"s","executantes":"s","tabelas":"s","users":"s","operadoras":"s"},"create":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","solicitantes":"s","executantes":"s","tabelas":"s","users":"s","operadoras":"s"},"update":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","solicitantes":"s","executantes":"s","tabelas":"s","users":"s","permissions":"s","operadoras":"s"},"delete":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","solicitantes":"s","executantes":"s","tabelas":"s","users":"s","permissions":"s","operadoras":"s"},"ler_arquivos":{"beneficiarios":"s","internacao":"s","fechar_lote":"s","gerenciar_lote":"s","rel_guias":"s","rel_lotes":"s","solicitantes":"s","executantes":"s","tabelas":"s","users":"s","permissions":"s","operadoras":"s"}}',
        ];
        DB::table('permissions')->insert([
            [
                'name'=>'Master',
                'description'=>'Desenvolvedores',
                'redirect_login'=>'/home',
                'active'=>'s',
                'id_menu'=>$arrPermiss['master'],
            ],
            [
                'name'=>'Administrador',
                'description'=>'Administradores do sistema',
                'redirect_login'=>'/home',
                'active'=>'s',
                'id_menu'=>$arrPermiss['admin'],
            ],
            [
                'name'=>'Gerente',
                'description'=>'Gerente do sistema menos que administrador secundário',
                'redirect_login'=>'/home',
                'active'=>'s',
                'id_menu'=>json_encode([]),
            ],
            [
                'name'=>'Escritório',
                'description'=>'Pessoas do escritório',
                'redirect_login'=>'/home',
                'active'=>'s',
                'id_menu'=>json_encode([]),
            ],
            [
                'name'=>'Clientes',
                'description'=>'Somente clientes, Sem privilêgios de administração acesso a área restrita do site',
                'redirect_login'=>'/transperencia',
                'active'=>'s',
                'id_menu'=>json_encode([]),
            ],
        ]);
    }
}
