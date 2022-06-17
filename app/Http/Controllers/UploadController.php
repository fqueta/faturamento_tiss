<?php

namespace App\Http\Controllers;

use App\Models\_upload;
use App\Qlib\Qlib;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ret['exec'] = false;
        if($request->has('token_produto')){
            $arquivos = _upload::where('token_produto','=',$request->get('token_produto'))->get();
            if($arquivos){
                $ret['exec'] = true;
                $ret['arquivos'] = $arquivos;
            }
        }
        return response()->json($ret);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    public function store(Request $request)
    {
        $file = $request->file('file');
        $filenameWithExt = $file->getClientOriginalName();
        // Get just filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just ext
        $extension = $file->getClientOriginalExtension();
        // Filename to store
        $typeN = isset($request->typeN) ? $request->typeN : 1;
        if($typeN==1){
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
        }else{
            $fileNameToStore= $filename.'.'.$extension;
        }
        $arquivos = isset($request->arquivos) ? $request->arquivos : 'jpg,jpeg,png,zip,pdf,PDF';
        if($arquivos){
            $arr_extension = explode(',',$arquivos);
        }
        //if($extension=='jpg' || $extension=='jpeg' || $extension=='png' || $extension=='zip' || $extension=='pdf' || $extension=='PDF'){
        if(in_array($extension,$arr_extension)){
            $dados = $request->all();
            $token_produto = $dados['token_produto'];
            $ultimoValor = _upload::where('token_produto','=',$token_produto)->max('ordem');
            $ordem = $ultimoValor ? $ultimoValor : 0;
            $ordem++;
            $pasta = $dados['pasta'];
            $nomeArquivoSavo = $file->storeAs($pasta,$fileNameToStore);
            $exec = false;
            $salvar = false;
            if($nomeArquivoSavo){
                $exec = true;
                $salvar = _upload::create([
                    'token_produto'=>$token_produto,
                    'pasta'=>$nomeArquivoSavo,
                    'ordem'=>$ordem,
                    'nome'=>$filenameWithExt,
                    'config'=>json_encode(['extenssao'=>$extension])
                ]);
            }
            //$lista = _upload::where('token_produto','=',$token_produto)->get();
            if($salvar){
                return response()->json(['Arquivo enviado com sucesso'=>200]);
            }
        }else{
            return response()->json('O Formato .'.$extension.' não é permitido', 400);
        }
    }

    public function storeVarios(Request $request)
    {
        $dados = $request;
        //$dados->file('arquivo')->store('teste');
        //$d = $dados->all();
        //dd($d);
        $token_produto = '123teste';
        $pasta = 'familias';
        $nomeArquivoSavo = [];
        //Qlib::lib_print($dados->allFiles());
        //die();
        $salvar = false;
        for ($i=0; $i < count($dados->allFiles()['arquivo']); $i++) {
            $file = $dados->allFiles()['arquivo'][$i];
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            // Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore= $filename.'_'.time().'.'.$extension;
            // Upload Image

            $nomeArquivoSavo['nome'][$i] = $file->storeAs($pasta,$fileNameToStore);
            $salvar[$i] = _upload::create([
                'token_produto'=>$token_produto,
                'pasta'=>$nomeArquivoSavo['nome'][$i],
                'ordem'=>$i
            ]);

        }
        if($salvar){
            return response()->json(['success'=>'Enviados com sucesso']);
        }else{

        }
        //Qlib::lib_print($salvar);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dados = _upload::find($id);
        $dele_file = false;
        if (Storage::exists($dados->pasta))
            $dele_file = Storage::delete($dados->pasta);

        $delete = _upload::where('id',$id)->delete();
        return response()->json(['exec'=>$delete,'dele_file'=>$dele_file]);
    }
}
