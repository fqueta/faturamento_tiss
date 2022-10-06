<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Models\Operadora;
use App\Qlib\Qlib;
use DOMDocument;
use Illuminate\Http\Request;

class GeradorXmlController extends Controller
{
    public function __construct()
    {

    }
    public function guiaResumoInternacao( $idGuia = null,$id_lote=4){
        $ret['exec'] = false;
        $dadosGuia = false;
        $tipo = 'str';
        $guiasLote = (new FaturamentosController())->guiasLote($id_lote);
        if($guiasLote['exec'] && $guiasLote['guias']){
            $tipo = 'arr';
            $dadosGuia = $guiasLote['guias'];
        }else{
            if(is_array($idGuia)){
                $tipo = 'arr';
                foreach ($idGuia as $key => $value) {
                    if($value){
                        $dadosGuia[$key] = Guia::FindOrFail($value);
                        $dadosGuia[$key]['config'] = Qlib::lib_json_array(@$dadosGuia[$key]['config']);
                    }
                }
            }else{
                $dadosGuia = Guia::FindOrFail($idGuia);
            }
        }
        //dd($dadosGuia);
        $CalcHash=false;
        // if(isset($dadosGuia['config'])&&!empty($dadosGuia['config'])){
        //     $dadosGuia['config'] = Qlib::lib_json_array($dadosGuia['config']);

        // }else{
        //     return $ret;
        // }
        $versao_tiss = '3.05.00';
        $ver_referencia = '3.03.03';
        if(isset($dadosGuia[0]['config']['select_operadora']) && ($id_op=$dadosGuia[0]['config']['select_operadora'])){
            $dOp = Operadora::find($id_op);
            $dOp['config'] = Qlib::lib_json_array($dOp['config']);
            $versao_tiss = isset($dOp['config']['versao_tiss'])?$dOp['config']['versao_tiss']:false;
        }
        $doc_versao = str_replace('.','_',$versao_tiss);
        $num_versao = (double)$versao_tiss;
        $_XML['tipoTransacao']          = 'ENVIO_LOTE_GUIAS';
        $_XML['sequencialTransacao']    = $id_lote;
        $_XML['dataRegistroTransacao']  = date('Y-m-d');
        $_XML['horaRegistroTransacao']  = date('H:i:s');
        $_XML['padrao_tiss']            = $versao_tiss;
        $_XML['numeroLote']            = $id_lote;
        $arr_var = [
            'cnpj'=>'codigoNaOperadora',
            'registroANS'=>'registro_ans',
            'numeroGuiaPrestador'=>'numero_guia', //não está na variavel config
            'numeroGuiaOperadora'=>'numeroGuiaOperadora',
            'dataAutorizacao'=>'data_autorizacao',
            'senha'=>'senha',
            'dataValidadeSenha'=>'dataValidadeSenha',
            'numeroGuiaSolicitacaoInternacao'=>'numeroGuiaSolicitacaoInternacao',
            'numeroCarteira'=>'numeroCarteira',
            'atendimentoRN'=>'atendimentoRN',
            'nomeBeneficiario'=>'nome',
            'cnpjContratado'=>'codigoNaOperadora',
            'nomeContratado'=>'nomeContratado',
            'CNES'=>'codigoCNES',
            'caraterAtendimento'=>'caraterAtendimento',
            'tipoFaturamento'=>'tipoFaturamento',
            'dataInicioFaturamento'=>'dataInicioFaturamento',
            'horaInicioFaturamento'=>'horaInicioFaturamento',
            'dataFinalFaturamento'=>'dataFinalFaturamento',
            'horaFinalFaturamento'=>'horaFinalFaturamento',
            'tipoFaturamento'=>'tipoFaturamento',
            'tipoInternacao'=>'tipoInternacao',
            'regimeInternacao'=>'regimeInternacao',
            'diagnostico'=>'CID10Principal',
            'diagnostico2'=>'CID10_2',
            'diagnostico3'=>'CID10_3',
            'diagnostico4'=>'CID10_4',
            'indicadorAcidente'=>'indicadorAcidente',
            'motivoEncerramento'=>'motivoEncerramento',
            'valorProcedimentos'=>'valorProcedimentos',
            'valorTaxasAlugueis'=>'valorTaxasAlugueis',
            'valorDiarias'=>'valorDiarias',
            'valorMateriais'=>'valorMateriais',
            'valorMedicamentos'=>'valorMedicamentos',
            'valorOPME'=>'valorOPME',
            'valorGasesMedicinais'=>'valorGasesMedicinais',
            'valorTotalGeral'=>'valorTotalGeral',
            'procedimento'=>'procedimento',
            'despesas'=>'despesas',
        ];
        $i=0;
        if($tipo=='arr'){
            foreach ($dadosGuia as $k1 => $va1) {
                foreach ($arr_var as $key => $val) {
                    if($key=='numeroGuiaPrestador'){
                        $_XML['dados'][$k1][$key] = isset($va1[$val])?$va1[$val]:false;
                    }elseif($key=='nomeBeneficiario'){
                        $_XML['dados'][$k1][$key] = isset($va1[$val])?$va1[$val]:false;
                    }elseif($key=='procedimento'&&isset($va1['config'][$val])){
                        $_XML['dados'][$k1]['procedimentos'] = $va1['config'][$val];
                    }elseif($key=='despesas'&&isset($va1['config'][$val])){
                        $_XML['dados'][$k1]['despesas'] = $va1['config'][$val];
                    }elseif($key=='horaInicioFaturamento' || $key=='horaFinalFaturamento' || $key=='horaInicial' || $key=='horaFinal'){
                        $_XML['dados'][$k1][$key] = $va1['config'][$val].':00';
                    }else{
                        if(isset($va1['config'][$val])){
                            $_XML['dados'][$k1][$key] = trim($va1['config'][$val]);
                        }
                    }
                }
            }
        }
        $ret['dadosXml'] = Qlib::lib_array_json($_XML);
        $xml = new DOMDocument('1.0', 'UTF-8');
        //também poderia ser UTF-8

        #remove os espacos em branco
        $xml->preserveWhiteSpace = false;

        #Realizar a quebra dos blocos do XML por linha
        $xml->formatOutput = true;


        // Nó / Bloco Principal
        // ans:mensagemTISS
        $mensagemTISS = $xml->createElement("ans:mensagemTISS");
        $xml->appendChild($mensagemTISS);
            //Criação dos elementos do Namespace ans:mensagemTISS
            $xml->createAttributeNS( 'http://www.w3.org/2000/09/xmldsig#', 'ds:attr' );
            $xml->createAttributeNS( 'http://www.ans.gov.br/padroes/tiss/schemas', 'ans:attr' );
            $xml->createAttributeNS( 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:attr' );
            //$xml->createAttributeNS( 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_05_00.xsd', 'schemaLocation:attr' );
            //$xml->createAttribute('xsi:schemaLocation');
            //$xml->setAttribute('xsi:schemaLocation');
            $domAttribute = $xml->createAttribute('xsi:schemaLocation');
            $domAttribute->value = 'http://www.ans.gov.br/padroes/tiss/schemas tissV'.$doc_versao.'.xsd';
            $mensagemTISS->appendChild($domAttribute);
        // Value for the created attribute

            /* primeiro bloco */
            // ans:mensagemTISS / ans:cabecalho
            $cabecalho = $xml->createElement("ans:cabecalho");
            $mensagemTISS->appendChild($cabecalho);


                // ans:mensagemTISS / ans:cabecalho / ans:identificacaoTransacao
                $identificacaoTransacao = $xml->createElement("ans:identificacaoTransacao");
                $cabecalho->appendChild($identificacaoTransacao);

                        # ans:tipoTransacao
                        $tipoTransacao = $xml->createElement("ans:tipoTransacao", $_XML['tipoTransacao']);
                        $identificacaoTransacao->appendChild($tipoTransacao);
                        $CalcHash .= $_XML['tipoTransacao'];

                        #sequencialTransacao
                        $sequencialTransacao = $xml->createElement("ans:sequencialTransacao", $_XML['sequencialTransacao']);
                        $identificacaoTransacao->appendChild($sequencialTransacao);
                        $CalcHash .= $_XML['sequencialTransacao'];

                        #dataRegistroTransacao
                        $dataRegistroTransacao = $xml->createElement("ans:dataRegistroTransacao", $_XML['dataRegistroTransacao']);
                        $identificacaoTransacao->appendChild($dataRegistroTransacao);
                        $CalcHash .= $_XML['dataRegistroTransacao'];

                        #horaRegistroTransacao
                        $horaRegistroTransacao = $xml->createElement("ans:horaRegistroTransacao", $_XML['horaRegistroTransacao']);
                        $identificacaoTransacao->appendChild($horaRegistroTransacao);
                        $CalcHash .= $_XML['horaRegistroTransacao'];

                // ans:mensagemTISS / ans:cabecalho / ans:origem
                $origem = $xml->createElement("ans:origem");
                $cabecalho->appendChild($origem);

                        // ans:mensagemTISS / ans:cabecalho / ans:origem / identificacaoPrestador
                        $identificacaoPrestador = $xml->createElement("ans:identificacaoPrestador");
                        $origem->appendChild($identificacaoPrestador);

                        $CNPJ = $xml->createElement("ans:CNPJ", $_XML['dados'][0]['cnpj']);
                        $identificacaoPrestador->appendChild($CNPJ);
                        $CalcHash .= $_XML['dados'][0]['cnpj'];


                // ans:mensagemTISS / ans:cabecalho / ans:destino
                $destino = $xml->createElement("ans:destino");
                $cabecalho->appendChild($destino);

                        // ans:mensagemTISS / ans:cabecalho / ans:registroANS
                        $registroANS = $xml->createElement("ans:registroANS", $_XML['dados'][0]['registroANS']);
                        $destino->appendChild($registroANS);
                        $CalcHash .= $_XML['dados'][0]['registroANS'];

                // ans:mensagemTISS / ans:cabecalho / ans:Padrao
                $Padrao = $xml->createElement("ans:Padrao", $_XML['padrao_tiss']);
                $cabecalho->appendChild($Padrao);
                $CalcHash .= $_XML['padrao_tiss'];


            /* segundo bloco */
            // ans:mensagemTISS / ans:prestadorParaOperadora
            $prestadorParaOperadora = $xml->createElement("ans:prestadorParaOperadora");
            $mensagemTISS->appendChild($prestadorParaOperadora);

                // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias
                $loteGuias = $xml->createElement("ans:loteGuias");
                $prestadorParaOperadora->appendChild($loteGuias);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / numeroLote
                    $numeroLote = $xml->createElement("ans:numeroLote", $_XML['numeroLote']);
                    $loteGuias->appendChild($numeroLote);
                    $CalcHash .= $_XML['numeroLote'];

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiasTISS
                    $guiasTISS = $xml->createElement("ans:guiasTISS");
                    $loteGuias->appendChild($guiasTISS);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaResumoInternacao
                    if(!isset($_XML['dados'])){
                        return 'dados insuficientes';
                    }
                    //dd($_XML);
                    foreach ($_XML['dados'] as $key => $d_xml) {
                        $guiaResumoInternacao = $xml->createElement("ans:guiaResumoInternacao");

                            $guiasTISS->appendChild($guiaResumoInternacao);

                            // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / cabecalhoGuia
                            $cabecalhoGuia = $xml->createElement("ans:cabecalhoGuia");
                            $guiaResumoInternacao->appendChild($cabecalhoGuia);

                            $registroANS = $xml->createElement("ans:registroANS", $d_xml['registroANS']); //registroANS
                            $cabecalhoGuia->appendChild($registroANS);
                            $CalcHash .= $d_xml['registroANS'];


                            $numeroGuiaPrestador = $xml->createElement("ans:numeroGuiaPrestador", $d_xml['numeroGuiaPrestador']); //numeroGuiaPrestador
                            $cabecalhoGuia->appendChild($numeroGuiaPrestador);
                            $CalcHash .= $d_xml['numeroGuiaPrestador'];

                            // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaResumoInternacao / numeroGuiaSolicitacaoInternacao
                            $numeroGuiaSolicitacaoInternacao = $xml->createElement("ans:numeroGuiaSolicitacaoInternacao", $d_xml['numeroGuiaSolicitacaoInternacao']);
                            $guiaResumoInternacao->appendChild($numeroGuiaSolicitacaoInternacao);
                            $CalcHash .= $d_xml['numeroGuiaSolicitacaoInternacao'];
                            // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaResumoInternacao / dadosAutorizacao
                            $dadosAutorizacao = $xml->createElement("ans:dadosAutorizacao");
                            $guiaResumoInternacao->appendChild($dadosAutorizacao);

                            $numeroGuiaOperadora = $xml->createElement("ans:numeroGuiaOperadora", $d_xml['numeroGuiaOperadora']); //numeroGuiaOperadora
                            $dadosAutorizacao->appendChild($numeroGuiaOperadora);
                            $CalcHash .= $d_xml['numeroGuiaOperadora'];


                            $dataAutorizacao = $xml->createElement("ans:dataAutorizacao", $d_xml['dataAutorizacao']); //dataAutorizacao
                            $dadosAutorizacao->appendChild($dataAutorizacao);
                            $CalcHash .= $d_xml['dataAutorizacao'];



                            $senha = $xml->createElement("ans:senha", $d_xml['senha']); //senha
                            $dadosAutorizacao->appendChild($senha);
                            $CalcHash .= $d_xml['senha'];

                            $dataValidadeSenha = $xml->createElement("ans:dataValidadeSenha", $d_xml['dataValidadeSenha']); //dataValidadeSenha
                            $dadosAutorizacao->appendChild($dataValidadeSenha);
                            $CalcHash .= $d_xml['dataValidadeSenha'];


                            $dadosBeneficiario = $xml->createElement("ans:dadosBeneficiario"); //dadosBeneficiario
                            $guiaResumoInternacao->appendChild($dadosBeneficiario);


                            $dadosExecutante = $xml->createElement("ans:dadosExecutante"); //dadosExecutante
                            $guiaResumoInternacao->appendChild($dadosExecutante);

                            $numeroCarteira = $xml->createElement("ans:numeroCarteira", $d_xml['numeroCarteira']); //numeroCarteira
                            $dadosBeneficiario->appendChild($numeroCarteira);
                            $CalcHash .= $d_xml['numeroCarteira'];


                            $atendimentoRN = $xml->createElement("ans:atendimentoRN", $d_xml['atendimentoRN']); //atendimentoRN
                            $dadosBeneficiario->appendChild($atendimentoRN);
                            $CalcHash .= $d_xml['atendimentoRN'];

                            $nomeB = Qlib::sanitizeString(@$d_xml['nomeBeneficiario']);
                            $nomeBeneficiario = $xml->createElement("ans:nomeBeneficiario", $nomeB); //nomeBeneficiario
                            $dadosBeneficiario->appendChild($nomeBeneficiario);
                            $CalcHash .= $nomeB;

                            //contratadoExecutante
                            $contratadoExecutante = $xml->createElement("ans:contratadoExecutante");
                            $dadosExecutante->appendChild($contratadoExecutante);



                            $cnpjContratado = $xml->createElement("ans:cnpjContratado", $d_xml['cnpjContratado']); //cnpjContratado
                            $contratadoExecutante->appendChild($cnpjContratado);
                            $CalcHash .= $d_xml['cnpjContratado'];


                            $nomeContratado = $xml->createElement("ans:nomeContratado", $d_xml['nomeContratado']); //nomeContratado
                            $contratadoExecutante->appendChild($nomeContratado);
                            $CalcHash .= $d_xml['nomeContratado'];

                            $CNES = $xml->createElement("ans:CNES", $d_xml['CNES']); //CNES
                            $dadosExecutante->appendChild($CNES);
                            $CalcHash .= $d_xml['CNES'];


                            //No dados internação
                            $dadosInternacao = $xml->createElement("ans:dadosInternacao"); //dadosInternacao
                            $guiaResumoInternacao->appendChild($dadosInternacao);

                                $caraterAtendimento = $xml->createElement("ans:caraterAtendimento",$d_xml['caraterAtendimento']); //caraterAtendimento
                                $dadosInternacao->appendChild($caraterAtendimento);
                                $CalcHash .= $d_xml['caraterAtendimento'];

                                $tipoFaturamento = $xml->createElement("ans:tipoFaturamento",$d_xml['tipoFaturamento']); //tipoFaturamento
                                $dadosInternacao->appendChild($tipoFaturamento);
                                $CalcHash .= $d_xml['tipoFaturamento'];

                                $dataInicioFaturamento = $xml->createElement("ans:dataInicioFaturamento",$d_xml['dataInicioFaturamento']); //dataInicioFaturamento
                                $dadosInternacao->appendChild($dataInicioFaturamento);
                                $CalcHash .= $d_xml['dataInicioFaturamento'];

                                $horaInicioFaturamento = $xml->createElement("ans:horaInicioFaturamento",$d_xml['horaInicioFaturamento']); //horaInicioFaturamento
                                $dadosInternacao->appendChild($horaInicioFaturamento);
                                $CalcHash .= $d_xml['horaInicioFaturamento'];

                                $dataFinalFaturamento = $xml->createElement("ans:dataFinalFaturamento",$d_xml['dataFinalFaturamento']); //dataFinalFaturamento
                                $dadosInternacao->appendChild($dataFinalFaturamento);
                                $CalcHash .= $d_xml['dataFinalFaturamento'];

                                $horaFinalFaturamento = $xml->createElement("ans:horaFinalFaturamento",$d_xml['horaFinalFaturamento']); //horaFinalFaturamento
                                $dadosInternacao->appendChild($horaFinalFaturamento);
                                $CalcHash .= $d_xml['horaFinalFaturamento'];

                                $tipoInternacao = $xml->createElement("ans:tipoInternacao",$d_xml['tipoInternacao']); //tipoInternacao
                                $dadosInternacao->appendChild($tipoInternacao);
                                $CalcHash .= $d_xml['tipoInternacao'];

                                $regimeInternacao = $xml->createElement("ans:regimeInternacao",$d_xml['regimeInternacao']); //regimeInternacao
                                $dadosInternacao->appendChild($regimeInternacao);
                                $CalcHash .= $d_xml['regimeInternacao'];

                            //No dados saida internação
                            $dadosSaidaInternacao = $xml->createElement("ans:dadosSaidaInternacao"); //dadosSaidaInternacao
                            $guiaResumoInternacao->appendChild($dadosSaidaInternacao);
                                if(isset($d_xml['diagnostico']) && !empty($d_xml['diagnostico'])){
                                    $diagnostico = $xml->createElement("ans:diagnostico",$d_xml['diagnostico']); //diagnostico
                                    $dadosSaidaInternacao->appendChild($diagnostico);
                                    $CalcHash .= $d_xml['diagnostico'];
                                }
                                if(isset($d_xml['diagnostico2']) && !empty($d_xml['diagnostico2'])){
                                    $diagnostico2 = $xml->createElement("ans:diagnostico",$d_xml['diagnostico2']); //diagnostico2
                                    $dadosSaidaInternacao->appendChild($diagnostico2);
                                    $CalcHash .= $d_xml['diagnostico2'];
                                }
                                if(isset($d_xml['diagnostico3']) && !empty($d_xml['diagnostico3'])){
                                    $diagnostico3 = $xml->createElement("ans:diagnostico",$d_xml['diagnostico3']); //diagnostico3
                                    $dadosSaidaInternacao->appendChild($diagnostico3);
                                    $CalcHash .= $d_xml['diagnostico3'];
                                }
                                if(isset($d_xml['diagnostico4']) && !empty($d_xml['diagnostico4'])){
                                    $diagnostico4 = $xml->createElement("ans:diagnostico",$d_xml['diagnostico4']); //diagnostico4
                                    $dadosSaidaInternacao->appendChild($diagnostico4);
                                    $CalcHash .= $d_xml['diagnostico4'];
                                }

                                $indicadorAcidente = $xml->createElement("ans:indicadorAcidente",$d_xml['indicadorAcidente']); //indicadorAcidente
                                $dadosSaidaInternacao->appendChild($indicadorAcidente);
                                $CalcHash .= $d_xml['indicadorAcidente'];

                                $motivoEncerramento = $xml->createElement("ans:motivoEncerramento",$d_xml['motivoEncerramento']); //motivoEncerramento
                                $dadosSaidaInternacao->appendChild($motivoEncerramento);
                                $CalcHash .= $d_xml['motivoEncerramento'];
                            //inicio No de procedimentos
                            $procedimentosExecutados = $xml->createElement("ans:procedimentosExecutados"); //procedimentosExecutados
                            $guiaResumoInternacao->appendChild($procedimentosExecutados);
                            if(isset($d_xml['procedimentos']) && is_array($d_xml['procedimentos'])){
                                foreach ($d_xml['procedimentos'] as $key => $v) {
                                            //listagem de procedimentos
                                            $procedimentoExecutado = $xml->createElement("ans:procedimentoExecutado"); //procedimentoExecutado
                                            $procedimentosExecutados->appendChild($procedimentoExecutado);
                                            //elementos
                                            if($num_versao>(double)$ver_referencia){
                                                $sequencialItem = $xml->createElement("ans:sequencialItem",@$v['item']); //sequencialItem
                                                $procedimentoExecutado->appendChild($sequencialItem);
                                                $CalcHash .= trim(@$v['item']);
                                            }
                                            $dataExecucao = $xml->createElement("ans:dataExecucao",@$v['data']); //dataExecucao
                                            $procedimentoExecutado->appendChild($dataExecucao);
                                            $CalcHash .= trim(@$v['data']);
                                            if(!empty($v['hora1'])){
                                                $v['hora1'] .= ':00';
                                                $horaInicial = $xml->createElement("ans:horaInicial",@$v['hora1']); //horaInicial
                                                $procedimentoExecutado->appendChild($horaInicial);
                                                $CalcHash .= trim(@$v['hora1']);
                                            }
                                            if(!empty($v['hora2'])){
                                                $v['hora2'] .= ':00';
                                                $horaFinal = $xml->createElement("ans:horaFinal",@$v['hora2']); //horaFinal
                                                $procedimentoExecutado->appendChild($horaFinal);
                                                $CalcHash .= trim(@$v['hora2']);
                                            }

                                            $procedimento = $xml->createElement("ans:procedimento"); //procedimento
                                            $procedimentoExecutado->appendChild($procedimento);

                                                $codigoTabela = $xml->createElement("ans:codigoTabela",@$v['tabela']); //procedimento
                                                $procedimento->appendChild($codigoTabela);
                                                $CalcHash .= trim(@$v['tabela']);

                                                $codigoProcedimento = $xml->createElement("ans:codigoProcedimento",@$v['codigo']); //procedimento
                                                $procedimento->appendChild($codigoProcedimento);
                                                $CalcHash .= trim(@$v['codigo']);
                                                $desc = Qlib::sanitizeString(@$v['descricao']);
                                                $descricaoProcedimento = $xml->createElement("ans:descricaoProcedimento",$desc); //procedimento
                                                $procedimento->appendChild($descricaoProcedimento);
                                                $CalcHash .= trim($desc);


                                            $quantidadeExecutada = $xml->createElement("ans:quantidadeExecutada",@$v['quantidade']); //quantidadeExecutada
                                            $procedimentoExecutado->appendChild($quantidadeExecutada);
                                            $CalcHash .= trim(@$v['quantidade']);
                                            if(!empty($v['via'])){
                                                $viaAcesso = $xml->createElement("ans:viaAcesso",@$v['via']); //viaAcesso
                                                $procedimentoExecutado->appendChild($viaAcesso);
                                                $CalcHash .= trim(@$v['via']);
                                            }
                                            if(!empty($v['via'])){
                                                $tecnicaUtilizada = $xml->createElement("ans:tecnicaUtilizada",@$v['tec']); //tecnicaUtilizada
                                                $procedimentoExecutado->appendChild($tecnicaUtilizada);
                                                $CalcHash .= trim(@$v['tec']);
                                            }
                                            $reducaoAcrescimo = $xml->createElement("ans:reducaoAcrescimo",@$v['fator']); //reducaoAcrescimo
                                            $procedimentoExecutado->appendChild($reducaoAcrescimo);
                                            $CalcHash .= trim(@$v['fator']);

                                            $v['valor_unitario'] = str_replace('R$','',@$v['valor_unitario']);
                                            $v['valor_unitario'] = str_replace('.','',@$v['valor_unitario']);
                                            $v['valor_unitario'] = str_replace(',','.',@$v['valor_unitario']);
                                            $v['valor_unitario'] = trim(@$v['valor_unitario']);

                                            $valorUnitario = $xml->createElement("ans:valorUnitario",@$v['valor_unitario']); //valorUnitario
                                            $procedimentoExecutado->appendChild($valorUnitario);
                                            $CalcHash .= @$v['valor_unitario'];

                                            $valorTotal = $xml->createElement("ans:valorTotal",@$v['valor_total']); //valorTotal
                                            $procedimentoExecutado->appendChild($valorTotal);
                                            $CalcHash .= trim(@$v['valor_total']);

                                }
                            }


                            //Fim Nô de procedimentos
                            //Inicio Nô de total
                            //inicio No de procedimentos
                            $valorTotal = $xml->createElement("ans:valorTotal"); //valorTotal
                            $guiaResumoInternacao->appendChild($valorTotal);

                                //$valorProcedimentos = $xml->createElement("ans:valorProcedimentos",$d_xml['valorProcedimentos']); //valorProcedimentos
                                //$valorTotal->appendChild($valorProcedimentos);
                                //$CalcHash .= $d_xml['valorProcedimentos'];
                                $valorPr = str_replace(',','.',$d_xml['valorProcedimentos']);
                                $valorProcedimentos = $xml->createElement("ans:valorProcedimentos",$valorPr); //valorProcedimentos
                                $valorTotal->appendChild($valorProcedimentos);
                                $CalcHash .= $valorPr;
                                if($d_xml['valorDiarias'] == '0.00'){
                                    $d_xml['valorDiarias']=0;
                                }
                                $vld = Qlib::precoBanco($d_xml['valorDiarias']);
                                $valorDiarias = $xml->createElement("ans:valorDiarias",str_replace(',','.',$vld)); //valorDiarias
                                $valorTotal->appendChild($valorDiarias);
                                $CalcHash .= $vld;

                                if($d_xml['valorTaxasAlugueis'] == '0.00'){
                                    $d_xml['valorTaxasAlugueis']=0;
                                }
                                $vlta = Qlib::precoBanco($d_xml['valorTaxasAlugueis']);
                                $valorTaxasAlugueis = $xml->createElement("ans:valorTaxasAlugueis",$vlta); //valorTaxasAlugueis
                                $valorTotal->appendChild($valorTaxasAlugueis);
                                $CalcHash .= $vlta;

                                if($d_xml['valorMateriais'] == '0.00'){
                                    $d_xml['valorMateriais']=0;
                                }

                                $vlM = Qlib::precoBanco($d_xml['valorMateriais']);

                                $valorMateriais = $xml->createElement("ans:valorMateriais",$vlM); //valorMateriais
                                $valorTotal->appendChild($valorMateriais);
                                $CalcHash .= $vlM;

                                if($d_xml['valorMedicamentos'] == '0.00'){
                                    $d_xml['valorMedicamentos']=0;
                                }

                                $valorMed = Qlib::precoBanco($d_xml['valorMedicamentos']);

                                $valorMedicamentos = $xml->createElement("ans:valorMedicamentos",$valorMed); //valorMedicamentos
                                $valorTotal->appendChild($valorMedicamentos);
                                $CalcHash .= $valorMed;

                                if($d_xml['valorOPME'] == '0.00'){
                                    $d_xml['valorOPME']=0;
                                }

                                $vOP = Qlib::precoBanco($d_xml['valorOPME']);

                                $valorOPME = $xml->createElement("ans:valorOPME",Qlib::precoBanco($vOP)); //valorOPME
                                $valorTotal->appendChild($valorOPME);
                                $CalcHash .= $vOP;

                                if($d_xml['valorGasesMedicinais'] == '0.00'){
                                    $d_xml['valorGasesMedicinais']=0;
                                }

                                $valorGaMe = str_replace(',','.',Qlib::precoBanco($d_xml['valorGasesMedicinais']));
                                $valorGasesMedicinais = $xml->createElement("ans:valorGasesMedicinais",$valorGaMe); //valorGasesMedicinais
                                $valorTotal->appendChild($valorGasesMedicinais);
                                $CalcHash .= $valorGaMe;
                                $valorTotGer = Qlib::precoBanco($d_xml['valorTotalGeral']);
                                $valorTotalGeral = $xml->createElement("ans:valorTotalGeral",$valorTotGer); //valorTotalGeral
                                $valorTotal->appendChild($valorTotalGeral);
                                $CalcHash .= $valorTotGer;

                                if(isset($d_xml['despesas']) && is_array($d_xml['despesas'])){
                                    $outrasDespesas = $xml->createElement("ans:outrasDespesas"); //outrasDespesas
                                    $guiaResumoInternacao->appendChild($outrasDespesas);
                                    foreach ($d_xml['despesas'] as $key => $v) {
                                                //listagem de despesas
                                                $despesa = $xml->createElement("ans:despesa"); //despesa
                                                $outrasDespesas->appendChild($despesa);
                                                //elementos
                                                if($num_versao>(double)$ver_referencia){
                                                    $sequencialItem = $xml->createElement("ans:sequencialItem",@$key); //sequencialItem
                                                    $despesa->appendChild($sequencialItem);
                                                    $CalcHash .= trim(@$key);
                                                }
                                                $codDesp = Qlib::zerofill(@$v['tipo'],2);
                                                $codigoDespesa = $xml->createElement("ans:codigoDespesa",$codDesp); //codigoDespesa
                                                $despesa->appendChild($codigoDespesa);
                                                $CalcHash .= trim($codDesp);

                                                $servicosExecutados = $xml->createElement("ans:servicosExecutados"); //servicosExecutados
                                                $despesa->appendChild($servicosExecutados);

                                                    $dataExecucao = $xml->createElement("ans:dataExecucao",@$v['data']); //dataExecucao
                                                    $servicosExecutados->appendChild($dataExecucao);
                                                    $CalcHash .= trim(@$v['data']);
                                                    if(!empty($v['hora1'])){
                                                        $v['hora1'] .= ':00';
                                                        $horaInicial = $xml->createElement("ans:horaInicial",@$v['hora1']); //horaInicial
                                                        $servicosExecutados->appendChild($horaInicial);
                                                        $CalcHash .= trim(@$v['hora1']);
                                                    }
                                                    if(!empty($v['hora2'])){
                                                        $v['hora2'] .= ':00';
                                                        $horaFinal = $xml->createElement("ans:horaFinal",@$v['hora2']); //horaFinal
                                                        $servicosExecutados->appendChild($horaFinal);
                                                        $CalcHash .= trim(@$v['hora2']);
                                                    }

                                                    $codigoTabela = $xml->createElement("ans:codigoTabela",@$v['tabela']); //despesa
                                                    $servicosExecutados->appendChild($codigoTabela);
                                                    $CalcHash .= trim(@$v['tabela']);

                                                    $codigoProcedimento = $xml->createElement("ans:codigoProcedimento",@$v['codigo']); //despesa
                                                    $servicosExecutados->appendChild($codigoProcedimento);
                                                    $CalcHash .= trim(@$v['codigo']);

                                                    $quantidadeExecutada = $xml->createElement("ans:quantidadeExecutada",@$v['quantidade']); //quantidadeExecutada
                                                    $servicosExecutados->appendChild($quantidadeExecutada);
                                                    $CalcHash .= trim(@$v['quantidade']);
                                                    $unid = trim(Qlib::zerofill(@$v['unidade'],3));
                                                    $unidadeMedida = $xml->createElement("ans:unidadeMedida",$unid); //unidadeMedida
                                                    $servicosExecutados->appendChild($unidadeMedida);
                                                    $CalcHash .= $unid;


                                                    $reducaoAcrescimo = $xml->createElement("ans:reducaoAcrescimo",@$v['fator']); //reducaoAcrescimo
                                                    $servicosExecutados->appendChild($reducaoAcrescimo);
                                                    $CalcHash .= trim(@$v['fator']);

                                                    $v['valor_unitario'] = str_replace('R$','',@$v['valor_unitario']);
                                                    $v['valor_unitario'] = str_replace('.','',@$v['valor_unitario']);
                                                    $v['valor_unitario'] = str_replace(',','.',@$v['valor_unitario']);
                                                    $v['valor_unitario'] = trim(@$v['valor_unitario']);

                                                    $valorUnitario = $xml->createElement("ans:valorUnitario",@$v['valor_unitario']); //valorUnitario
                                                    $servicosExecutados->appendChild($valorUnitario);
                                                    $CalcHash .= @$v['valor_unitario'];

                                                    $valorTotal = $xml->createElement("ans:valorTotal",@$v['valor_total']); //valorTotal
                                                    $servicosExecutados->appendChild($valorTotal);
                                                    $CalcHash .= trim(@$v['valor_total']);

                                                    $desc = Qlib::sanitizeString(@$v['descricao']);
                                                    $descricaoProcedimento = $xml->createElement("ans:descricaoProcedimento",$desc); //despesa
                                                    $servicosExecutados->appendChild($descricaoProcedimento);
                                                    $CalcHash .= trim($desc);
                                                    if(isset($v['anvisa'])&&!empty($v['anvisa'])){
                                                        $registroANVISA = $xml->createElement("ans:registroANVISA",@$v['anvisa']); //registroANVISA
                                                        $servicosExecutados->appendChild($registroANVISA);
                                                        $CalcHash .= trim(@$v['anvisa']);
                                                    }
                                                    if(isset($v['anvisa'])&&!empty($v['anvisa'])){
                                                        $codigoRefFabricante = $xml->createElement("ans:codigoRefFabricante",@$v['anvisa']); //codigoRefFabricante
                                                        $servicosExecutados->appendChild($codigoRefFabricante);
                                                        $CalcHash .= trim(@$v['anvisa']);
                                                    }
                                                    if(isset($v['autorizacao'])&&!empty($v['autorizacao'])){
                                                        $autorizacaoFuncionamento = $xml->createElement("ans:autorizacaoFuncionamento",@$v['autorizacao']); //autorizacaoFuncionamento
                                                        $servicosExecutados->appendChild($autorizacaoFuncionamento);
                                                        $CalcHash .= trim(@$v['autorizacao']);
                                                    }

                                                //Fim nô serviços executados.

                                    }
                                }

                    }
                            /*

                    //profissionalExecutante
                    $profissionalExecutante = $xml->createElement("ans:profissionalExecutante"); //profissionalExecutante
                    $guiaResumoInternacao->appendChild($profissionalExecutante);

                    $nomeProfissional = $xml->createElement("ans:nomeProfissional", 'Médico Teste'); //nomeProfissional
                    $profissionalExecutante->appendChild($nomeProfissional);

                    $conselhoProfissional = $xml->createElement("ans:conselhoProfissional", '02'); //conselhoProfissional
                    $profissionalExecutante->appendChild($conselhoProfissional);

                    $numeroConselhoProfissional = $xml->createElement("ans:numeroConselhoProfissional", '1234'); //numeroConselhoProfissional
                    $profissionalExecutante->appendChild($numeroConselhoProfissional);

                    $UF = $xml->createElement("ans:UF", 'SP'); //UF
                    $profissionalExecutante->appendChild($UF);

                    $CBOS = $xml->createElement("ans:CBOS", '201115'); //CBOS
                    $profissionalExecutante->appendChild($CBOS);

                    //dadosAtendimento
                    $dadosAtendimento = $xml->createElement("ans:dadosAtendimento"); //dadosAtendimento
                    $guiaResumoInternacao->appendChild($dadosAtendimento);

                    $tipoAtendimento = $xml->createElement("ans:tipoAtendimento", '05'); //tipoAtendimento
                    $dadosAtendimento->appendChild($tipoAtendimento);

                    $indicacaoAcidente = $xml->createElement("ans:indicacaoAcidente", '9'); //indicacaoAcidente
                    $dadosAtendimento->appendChild($indicacaoAcidente);
                    */

                    // Calculo o Hash - Você poderia gerar os dados, usar um (replace do PHP) para substituir as tags, e pegar apenas os dados

                    $_XML['hash'] = md5($CalcHash);
                    //var_dump($CalcHash);
                    //$_XML['hash'] = strtolower($_XML['hash']);
                    //echo $_XML['hash'];exit;
                        /* terceiro bloco */
                        // ans:mensagemTISS / ans:epilogo
                        $epilogo = $xml->createElement("ans:epilogo");
                        $mensagemTISS->appendChild($epilogo);

                            // ans:mensagemTISS / ans:epilogo / ans:hash
                            $hash = $xml->createElement("ans:hash", $_XML['hash']);
                            $epilogo->appendChild($hash);

                    # Comando para salvar/gerar o arquivo XML TISS
                    # Geralmente o nome do arquivo é o HASH que foi calculado ou número do lote, pois são informações únicas.
                    # você pode usar as variáveis: $_XML['fatura_remessa'] . $_XML['hash']

                    //$xml->save("xml_tiss.xml");
                    $path = storage_path();
                    //$file = $path.'/app/public/xml/'.$_XML['numeroLote']."_".$_XML['hash'].'.xml';
                    $nome_arquivo = Qlib::zerofill($_XML['numeroLote'],5).'.xml';
                    $file = $path.'/app/public/xml/'.$nome_arquivo;
                    $link = '/storage/xml/'.$nome_arquivo;
                    $ret['file'] = $file;
                    $ret['link'] = $link;
                    $ret['guias'] = Qlib::lib_array_json($idGuia);
                    $ret['numeroLote'] = $_XML['numeroLote'];
                    $ret['id_operadora'] = @$dadosGuia[0]['config']['op_id'];
                    //dd($ret);
                    if($xml->save($file)){
                        $ret['exec']=true;
                    }


                    # Imprime / Gera o xml em tela
                   // header('content-type: text/xml');
                   // print $xml->saveXML();

        return $ret;

    }
    public function guiaConsulta( $var = null)
    {
        $ret = false;
        $arr_var = [];

        #Definindo as variáveis
        $_XML['dados']['padrao_tiss'] = '3.05.00';
        $_XML['dados']['numeroLote'] = '1';

        #Dados Operadora:
        $_XML['dados']['registro_ans'] = '1111';
        $_XML['dados']['numeroGuiaSolicitacaoInternacao'] = '1111';
        #Dados Autorização
        $_XML['dados']['numeroGuiaOperadora'] = '1111';
        $_XML['dados']['dataAutorizacao'] = '1111';

        #Dados Prestador:
        $_XML['dados']['codigo_credenciamento'] = '12345';
        $_XML['dados']['cnpj'] = '0000000000000';
        $_XML['dados']['prestador'] = 'Hospital ABCD';

        #Dados do atendimento e beneficiário
        $_XML['dados']['data_hora'] = '03/04/2018 22:15:00';
        $_XML['dados']['rn'] = 'N'; #Não
        $_XML['dados']['tipo_atd'] = 'Ambulatorial';
        $_XML['dados']['carater'] = 'E'; #Eletivo
        $_XML['dados']['guia'] = '1111';
        $_XML['dados']['senha'] = '2222';
        $_XML['dados']['medico'] = 'Dr. Fabiano';
        $_XML['dados']['crm'] = '0000';
        $_XML['dados']['cbos'] = '';
        #Dados do beneficiário
        $_XML['dados']['nome'] = 'Fulano de Tal';
        $_XML['dados']['dt_nascimento'] = '01/01/1980';
        $_XML['dados']['carteira']  = '4444444444444444';
        $_XML['dados']['validade'] = '31/12/2030';

        #Dados da Fatura / Lote
        $_XML['dados']['fatura_remessa'] = '123';

        #Dados do procedimento realizado
        $_XML['dados']['tabela'] = '22';
        $_XML['dados']['procedimento_tuss'] = '10101012';
        $_XML['dados']['descricao_proced'] = 'Consulta em consultório';
        $_XML['dados']['valor_unitario'] = '500,00';
        $_XML['dados']['qtde'] = '1';
        $_XML['dados']['valor_total'] = '500,00';

        $_XML['dados']['tipoTransacao'] = 'ENVIO_LOTE_GUIAS';
        $_XML['dados']['sequencialTransacao'] = '6658';
        $_XML['dados']['dataRegistroTransacao'] = '2018-01-18';
        $_XML['dados']['horaRegistroTransacao'] = '10:00:00';

        # Utilize a variável $_XML['dados']['hash_dados'] para concatenar os dados e calcular o HASH antes do terceiro bloco
        $_XML['dados']['hash_dados'] = '';

        #A variável $_XML['dados']['hash'] está nula pois deve ser calculada com os dados dos elementos(tags) do XML
        $_XML['dados']['hash'] = 'calculo do HASH';
        $_XML['dados']['registroANS'] = '45621';
        $_XML['dados']['numeroGuiaPrestador'] = '789461';

        //$_XML['dados'][''] = ''; // para criar novas variáveis apenas siga o padrão
        #versao XML e codificação

        $xml = new DOMDocument('1.0', 'UTF-8');;
        //também poderia ser UTF-8

        #remove os espacos em branco
        $xml->preserveWhiteSpace = false;

        #Realizar a quebra dos blocos do XML por linha
        $xml->formatOutput = true;



        // Nó / Bloco Principal
        // ans:mensagemTISS
        $mensagemTISS = $xml->createElement("ans:mensagemTISS");
        $xml->appendChild($mensagemTISS);
            //Criação dos elementos do Namespace ans:mensagemTISS
            $xml->createAttributeNS( 'http://www.w3.org/2000/09/xmldsig#', 'ds:attr' );
            $xml->createAttributeNS( 'http://www.ans.gov.br/padroes/tiss/schemas', 'ans:attr' );
            $xml->createAttributeNS( 'http://www.w3.org/2001/XMLSchema-instance', 'xsi:attr' );
            //$xml->createAttributeNS( 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_05_00.xsd', 'schemaLocation:attr' );
            //$xml->createAttribute('xsi:schemaLocation');
            //$xml->setAttribute('xsi:schemaLocation');
            $domAttribute = $xml->createAttribute('xsi:schemaLocation');
            $domAttribute->value = 'http://www.ans.gov.br/padroes/tiss/schemas tissV3_05_00.xsd';
            $mensagemTISS->appendChild($domAttribute);
        // Value for the created attribute

            /* primeiro bloco */
            // ans:mensagemTISS / ans:cabecalho
            $cabecalho = $xml->createElement("ans:cabecalho");
            $mensagemTISS->appendChild($cabecalho);


                // ans:mensagemTISS / ans:cabecalho / ans:identificacaoTransacao
                $identificacaoTransacao = $xml->createElement("ans:identificacaoTransacao");
                $cabecalho->appendChild($identificacaoTransacao);

                        # ans:tipoTransacao
                        $tipoTransacao = $xml->createElement("ans:tipoTransacao", $_XML['dados']['tipoTransacao']);
                        $identificacaoTransacao->appendChild($tipoTransacao);

                        #sequencialTransacao
                        $sequencialTransacao = $xml->createElement("ans:sequencialTransacao", $_XML['dados']['sequencialTransacao']);
                        $identificacaoTransacao->appendChild($sequencialTransacao);

                        #dataRegistroTransacao
                        $dataRegistroTransacao = $xml->createElement("ans:dataRegistroTransacao", $_XML['dados']['dataRegistroTransacao']);
                        $identificacaoTransacao->appendChild($dataRegistroTransacao);

                        #horaRegistroTransacao
                        $horaRegistroTransacao = $xml->createElement("ans:horaRegistroTransacao", $_XML['dados']['horaRegistroTransacao']);
                        $identificacaoTransacao->appendChild($horaRegistroTransacao);


                // ans:mensagemTISS / ans:cabecalho / ans:origem
                $origem = $xml->createElement("ans:origem");
                $cabecalho->appendChild($origem);

                        // ans:mensagemTISS / ans:cabecalho / ans:origem / identificacaoPrestador
                        $identificacaoPrestador = $xml->createElement("ans:identificacaoPrestador");
                        $origem->appendChild($identificacaoPrestador);
                            //$CNPJ = $xml->createElement("ans:CNPJ", $_XML['dados']['cnpj']);
                            //$identificacaoPrestador->appendChild($CNPJ);


                // ans:mensagemTISS / ans:cabecalho / ans:destino
                $destino = $xml->createElement("ans:destino");
                $cabecalho->appendChild($destino);

                        // ans:mensagemTISS / ans:cabecalho / ans:registroANS
                        $registroANS = $xml->createElement("ans:registroANS", $_XML['dados']['registro_ans']);
                        $destino->appendChild($registroANS);

                // ans:mensagemTISS / ans:cabecalho / ans:Padrao
                $Padrao = $xml->createElement("ans:Padrao", $_XML['dados']['padrao_tiss']);
                $cabecalho->appendChild($Padrao);


            /* segundo bloco */
            // ans:mensagemTISS / ans:prestadorParaOperadora
            $prestadorParaOperadora = $xml->createElement("ans:prestadorParaOperadora");
            $mensagemTISS->appendChild($prestadorParaOperadora);

                // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias
                $loteGuias = $xml->createElement("ans:loteGuias");
                $prestadorParaOperadora->appendChild($loteGuias);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / numeroLote
                    $numeroLote = $xml->createElement("ans:numeroLote", $_XML['dados']['numeroLote']);
                    $loteGuias->appendChild($numeroLote);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiasTISS
                    $guiasTISS = $xml->createElement("ans:guiasTISS");
                    $loteGuias->appendChild($guiasTISS);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaConsulta

                    $guiaConsulta = $xml->createElement("ans:guiaConsulta");
                    $loteGuias->appendChild($guiaConsulta);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / cabecalhoConsulta
                    $cabecalhoConsulta = $xml->createElement("ans:cabecalhoConsulta");
                    $guiaConsulta->appendChild($cabecalhoConsulta);

                    $registroANS = $xml->createElement("ans:registroANS", $_XML['dados']['registroANS']); //registroANS
                    $cabecalhoConsulta->appendChild($registroANS);

                    $registroANS = $xml->createElement("ans:numeroGuiaPrestador", $_XML['dados']['numeroGuiaPrestador']); //numeroGuiaPrestador
                    $cabecalhoConsulta->appendChild($registroANS);

                    $numeroGuiaOperadora = $xml->createElement("ans:numeroGuiaOperadora", $_XML['dados']['numeroGuiaOperadora']); //numeroGuiaOperadora
                    $guiaConsulta->appendChild($numeroGuiaOperadora);

                    $dadosBeneficiario = $xml->createElement("ans:dadosBeneficiario"); //dadosBeneficiario
                    $guiaConsulta->appendChild($dadosBeneficiario);

                    $numeroCarteira = $xml->createElement("ans:numeroCarteira", '1122334455'); //numeroCarteira
                    $dadosBeneficiario->appendChild($numeroCarteira);

                    $atendimentoRN = $xml->createElement("ans:atendimentoRN", 'N'); //atendimentoRN
                    $dadosBeneficiario->appendChild($atendimentoRN);

                    $nomeBeneficiario = $xml->createElement("ans:nomeBeneficiario", 'Fulano de Tal'); //nomeBeneficiario
                    $dadosBeneficiario->appendChild($nomeBeneficiario);

                    //contratadoExecutante
                    $contratadoExecutante = $xml->createElement("ans:contratadoExecutante");
                    $guiaConsulta->appendChild($contratadoExecutante);


                    $codigoPrestadorNaOperadora = $xml->createElement("ans:codigoPrestadorNaOperadora", '001'); //codigoPrestadorNaOperadora
                    $contratadoExecutante->appendChild($codigoPrestadorNaOperadora);

                    $nomeContratado = $xml->createElement("ans:nomeContratado", 'Hospital Teste'); //nomeContratado
                    $contratadoExecutante->appendChild($nomeContratado);

                    $CNES = $xml->createElement("ans:CNES", '9999'); //CNES
                    $contratadoExecutante->appendChild($CNES);

                    //profissionalExecutante
                    $profissionalExecutante = $xml->createElement("ans:profissionalExecutante"); //profissionalExecutante
                    $guiaConsulta->appendChild($profissionalExecutante);

                    $nomeProfissional = $xml->createElement("ans:nomeProfissional", 'Médico Teste'); //nomeProfissional
                    $profissionalExecutante->appendChild($nomeProfissional);

                    $conselhoProfissional = $xml->createElement("ans:conselhoProfissional", '02'); //conselhoProfissional
                    $profissionalExecutante->appendChild($conselhoProfissional);

                    $numeroConselhoProfissional = $xml->createElement("ans:numeroConselhoProfissional", '1234'); //numeroConselhoProfissional
                    $profissionalExecutante->appendChild($numeroConselhoProfissional);

                    $UF = $xml->createElement("ans:UF", 'SP'); //UF
                    $profissionalExecutante->appendChild($UF);

                    $CBOS = $xml->createElement("ans:CBOS", '201115'); //CBOS
                    $profissionalExecutante->appendChild($CBOS);

                    //dadosAtendimento
                    $dadosAtendimento = $xml->createElement("ans:dadosAtendimento"); //dadosAtendimento
                    $guiaConsulta->appendChild($dadosAtendimento);

                    $tipoAtendimento = $xml->createElement("ans:tipoAtendimento", '05'); //tipoAtendimento
                    $dadosAtendimento->appendChild($tipoAtendimento);

                    $indicacaoAcidente = $xml->createElement("ans:indicacaoAcidente", '9'); //indicacaoAcidente
                    $dadosAtendimento->appendChild($indicacaoAcidente);


                    // Calculo o Hash - Você poderia gerar os dados, usar um (replace do PHP) para substituir as tags, e pegar apenas os dados
                    $xmlAqui = $xml->saveXML();
                    $_XML['dados']['hash_dados'] = trim(strip_tags($xmlAqui));
                    $_XML['dados']['hash_dados'] = preg_replace('/\s+/', '', $_XML['dados']['hash_dados']);
                    $_XML['dados']['hash'] = md5($_XML['dados']['hash_dados']);
                    //echo $_XML['dados']['hash'];exit;
                        /* terceiro bloco */
                        // ans:mensagemTISS / ans:epilogo
                        $epilogo = $xml->createElement("ans:epilogo");
                        $mensagemTISS->appendChild($epilogo);

                            // ans:mensagemTISS / ans:epilogo / ans:hash
                            $hash = $xml->createElement("ans:hash", $_XML['dados']['hash']);
                            $epilogo->appendChild($hash);

                    # Comando para salvar/gerar o arquivo XML TISS
                    # Geralmente o nome do arquivo é o HASH que foi calculado ou número do lote, pois são informações únicas.
                    # você pode usar as variáveis: $_XML['dados']['fatura_remessa'] . $_XML['dados']['hash']

                    $xml->save("xml_tiss.xml");


                    # Imprime / Gera o xml em tela
                    header('content-type: text/xml');
                    print $xml->saveXML();

        return $ret;
    }
}
