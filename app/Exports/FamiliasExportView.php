<?php

namespace App\Exports;
use App\Qlib\Qlib;
use App\Http\Controllers\FamiliaController;
use App\Models\Familia;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class FamiliasExportView implements FromView
{
    public function view(): View
    {
        $title = 'Famílias Cadastradas';
        $titulo = $title;
        $user = Auth::user();
        $dados = new FamiliaController($user);
        $queryFamilias = $dados->queryFamilias($_GET);
        $queryFamilias['config']['exibe'] = 'excel';
        return view('familias.export.tabela',[
            'familias'=>$queryFamilias['familia'],
            'title'=>$title,
            'titulo'=>$titulo,
            'campos_tabela'=>$queryFamilias['campos'],
            'familia_totais'=>$queryFamilias['familia_totais'],
            'titulo_tabela'=>$queryFamilias['tituloTabela'],
            'arr_titulo'=>$queryFamilias['arr_titulo'],
            'config'=>$queryFamilias['config'],
            'i'=>0,
        ]);
        //return view('exports.invoices', [
            //'invoices' => Invoice::all()
        //]);
    }
}
