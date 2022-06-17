<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            [
                'categoria'=>'',
                'description'=>'Painel',
                'icon'=>'fa fa-tachometer-alt',
                'actived'=>true,
                'url'=>'painel',
                'route'=>'home',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Pacientes',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'pacientes',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Agenda',
                'icon'=>'fa fa-calendar',
                'actived'=>true,
                'url'=>'agenda',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Guias',
                'icon'=>'far fa-file-alt',
                'actived'=>true,
                'url'=>'guias',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Cadastro',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'beneficiarios',
                'route'=>'beneficiarios.index',
                'pai'=>'pacientes'
            ],
            [
                'categoria'=>'',
                'description'=>'Guia de SP/SADT',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'spsadt',
                'route'=>'guias.index',
                'pai'=>'guias'
            ],
            [
                'categoria'=>'',
                'description'=>'Guia de Consulta',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'consulta',
                'route'=>'guias.index',
                'pai'=>'guias'
            ],
            [
                'categoria'=>'',
                'description'=>'Guia de Honorários Individuais',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'honorarios',
                'route'=>'guias.index',
                'pai'=>'guias'
            ],
            [
                'categoria'=>'',
                'description'=>'Guias Resumo de Internação',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'internacao',
                'route'=>'guias.index',
                'pai'=>'guias'
            ],
            [
                'categoria'=>'',
                'description'=>'Guias GTO',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'gto',
                'route'=>'guias.index',
                'pai'=>'guias'
            ],
            [
                'categoria'=>'',
                'description'=>'Faturamento',
                'icon'=>'fas fa-calculator',
                'actived'=>true,
                'url'=>'faturamento',
                'route'=>'',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Fechar Lote',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'fechar_lote',
                'route'=>'faturamento.fechar',
                'pai'=>'faturamento'
            ],
            [
                'categoria'=>'',
                'description'=>'Gerenciar Lote',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'gerenciar_lote',
                'route'=>'faturamento.gerenciar',
                'pai'=>'faturamento'
            ],
            [
                'categoria'=>'',
                'description'=>'Relatórios',
                'icon'=>'fas fa-chart-line',
                'actived'=>true,
                'url'=>'relatorios',
                'route'=>'relatorios.index',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Guias',
                'icon'=>'fas fa-file',
                'actived'=>true,
                'url'=>'rel_guias',
                'route'=>'relatorios.guias',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'',
                'description'=>'Lotes',
                'icon'=>'fas fa-clipboard-list',
                'actived'=>true,
                'url'=>'rel_lotes',
                'route'=>'relatorios.lotes',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'',
                'description'=>'Valores Cobrados',
                'icon'=>'fas fa-cash-register',
                'actived'=>true,
                'url'=>'rel_valores',
                'route'=>'relatorios.valores',
                'pai'=>'relatorios'
            ],
            [
                'categoria'=>'SISTEMA',
                'description'=>'Configurações',
                'icon'=>'fas fa-cogs',
                'actived'=>true,
                'url'=>'config',
                'route'=>'sistema.config',
                'pai'=>''
            ],
            [
                'categoria'=>'',
                'description'=>'Documentos',
                'icon'=>'fas fa-file-word',
                'actived'=>true,
                'url'=>'documentos',
                'route'=>'documentos.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Perfil',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'sistema',
                'route'=>'sistema.perfil',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Usuários',
                'icon'=>'fas fa-users',
                'actived'=>true,
                'url'=>'users',
                'route'=>'users.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Permissões',
                'icon'=>'far fa-list-alt ',
                'actived'=>true,
                'url'=>'permissions',
                'route'=>'permissions.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Cadastro de Operadoras',
                'icon'=>'fas fa-heartbeat',
                'actived'=>true,
                'url'=>'operadoras',
                'route'=>'operadoras.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Escolaridade',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'escolaridades',
                'route'=>'escolaridades.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Estado civil',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'estado-civil',
                'route'=>'estado-civil.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Listas do sistema (Tags)',
                'icon'=>'fas fa-list',
                'actived'=>true,
                'url'=>'tags',
                'route'=>'tags.index',
                'pai'=>'config'
            ],
            [
                'categoria'=>'',
                'description'=>'Avançado (Dev)',
                'icon'=>'fas fa-user',
                'actived'=>true,
                'url'=>'qoptions',
                'route'=>'qoptions.index',
                'pai'=>'config'
            ],
        ]);
    }
}
