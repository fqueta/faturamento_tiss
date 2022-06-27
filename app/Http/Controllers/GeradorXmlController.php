<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use App\Qlib\Qlib;
use DOMDocument;
use Illuminate\Http\Request;

class GeradorXmlController extends Controller
{
    public function __construct()
    {

    }
    public function guiaResumoInternacao( $idGuia = null){
        $ret = false;
        $dadosGuia = false;
        if($idGuia){
            $dadosGuia = Guia::FindOrFail($idGuia);
        }
        $CalcHash=false;
        if(isset($dadosGuia['config'])&&!empty($dadosGuia['config'])){
            $dadosGuia['config'] = Qlib::lib_json_array($dadosGuia['config']);

        }else{
            return $ret;
        }
        $_XML['tipoTransacao']          = 'ENVIO_LOTE_GUIAS';
        $_XML['sequencialTransacao']    = '3';
        $_XML['dataRegistroTransacao']  = date('Y-m-d');
        $_XML['horaRegistroTransacao']  = date('H:i:s');
        $_XML['padrao_tiss']            = '3.05.00';
        $_XML['numeroLote']            = '3';
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
        ];


        $i=0;
        foreach ($arr_var as $key => $val) {
            if($key=='numeroGuiaPrestador'){
                $_XML[$key] = isset($dadosGuia[$val])?$dadosGuia[$val]:false;
            }elseif($key=='nomeBeneficiario'){
                $_XML[$key] = isset($dadosGuia[$val])?$dadosGuia[$val]:false;
            }elseif($key=='procedimento'){
                $_XML['procedimentos'] = $dadosGuia['config'][$val];
            }elseif($key=='horaInicioFaturamento' || $key=='horaFinalFaturamento' || $key=='horaInicial' || $key=='horaFinal'){
                $_XML[$key] = $dadosGuia['config'][$val].':00';
            }else{
                if(isset($dadosGuia['config'][$val])){
                    $_XML[$key] = trim($dadosGuia['config'][$val]);
                }
            }
        }

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

                        $CNPJ = $xml->createElement("ans:CNPJ", $_XML['cnpj']);
                        $identificacaoPrestador->appendChild($CNPJ);
                        $CalcHash .= $_XML['cnpj'];


                // ans:mensagemTISS / ans:cabecalho / ans:destino
                $destino = $xml->createElement("ans:destino");
                $cabecalho->appendChild($destino);

                        // ans:mensagemTISS / ans:cabecalho / ans:registroANS
                        $registroANS = $xml->createElement("ans:registroANS", $_XML['registroANS']);
                        $destino->appendChild($registroANS);
                        $CalcHash .= $_XML['registroANS'];

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

                    $guiaResumoInternacao = $xml->createElement("ans:guiaResumoInternacao");
                    $guiasTISS->appendChild($guiaResumoInternacao);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / cabecalhoGuia
                    $cabecalhoGuia = $xml->createElement("ans:cabecalhoGuia");
                    $guiaResumoInternacao->appendChild($cabecalhoGuia);

                    $registroANS = $xml->createElement("ans:registroANS", $_XML['registroANS']); //registroANS
                    $cabecalhoGuia->appendChild($registroANS);
                    $CalcHash .= $_XML['registroANS'];


                    $numeroGuiaPrestador = $xml->createElement("ans:numeroGuiaPrestador", $_XML['numeroGuiaPrestador']); //numeroGuiaPrestador
                    $cabecalhoGuia->appendChild($numeroGuiaPrestador);
                    $CalcHash .= $_XML['numeroGuiaPrestador'];

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaResumoInternacao / numeroGuiaSolicitacaoInternacao
                     $numeroGuiaSolicitacaoInternacao = $xml->createElement("ans:numeroGuiaSolicitacaoInternacao", $_XML['numeroGuiaSolicitacaoInternacao']);
                     $guiaResumoInternacao->appendChild($numeroGuiaSolicitacaoInternacao);
                     $CalcHash .= $_XML['numeroGuiaSolicitacaoInternacao'];
                     // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaResumoInternacao / dadosAutorizacao
                    $dadosAutorizacao = $xml->createElement("ans:dadosAutorizacao");
                    $guiaResumoInternacao->appendChild($dadosAutorizacao);



                    $dataAutorizacao = $xml->createElement("ans:dataAutorizacao", $_XML['dataAutorizacao']); //dataAutorizacao
                    $dadosAutorizacao->appendChild($dataAutorizacao);
                    $CalcHash .= $_XML['dataAutorizacao'];

                    $numeroGuiaOperadora = $xml->createElement("ans:numeroGuiaOperadora", $_XML['numeroGuiaOperadora']); //numeroGuiaOperadora
                    $dadosAutorizacao->appendChild($numeroGuiaOperadora);
                    $CalcHash .= $_XML['numeroGuiaOperadora'];


                    $senha = $xml->createElement("ans:senha", $_XML['senha']); //senha
                    $dadosAutorizacao->appendChild($senha);
                    $CalcHash .= $_XML['senha'];

                    $dataValidadeSenha = $xml->createElement("ans:dataValidadeSenha", $_XML['dataValidadeSenha']); //dataValidadeSenha
                    $dadosAutorizacao->appendChild($dataValidadeSenha);
                    $CalcHash .= $_XML['dataValidadeSenha'];


                    $dadosBeneficiario = $xml->createElement("ans:dadosBeneficiario"); //dadosBeneficiario
                    $guiaResumoInternacao->appendChild($dadosBeneficiario);


                    $dadosExecutante = $xml->createElement("ans:dadosExecutante"); //dadosExecutante
                    $guiaResumoInternacao->appendChild($dadosExecutante);

                    $numeroCarteira = $xml->createElement("ans:numeroCarteira", $_XML['numeroCarteira']); //numeroCarteira
                    $dadosBeneficiario->appendChild($numeroCarteira);
                    $CalcHash .= $_XML['numeroCarteira'];


                    $atendimentoRN = $xml->createElement("ans:atendimentoRN", $_XML['atendimentoRN']); //atendimentoRN
                    $dadosBeneficiario->appendChild($atendimentoRN);
                    $CalcHash .= $_XML['atendimentoRN'];


                    $nomeBeneficiario = $xml->createElement("ans:nomeBeneficiario", $_XML['nomeBeneficiario']); //nomeBeneficiario
                    $dadosBeneficiario->appendChild($nomeBeneficiario);
                    $CalcHash .= $_XML['nomeBeneficiario'];

                    //contratadoExecutante
                    $contratadoExecutante = $xml->createElement("ans:contratadoExecutante");
                    $dadosExecutante->appendChild($contratadoExecutante);



                    $cnpjContratado = $xml->createElement("ans:cnpjContratado", $_XML['cnpjContratado']); //cnpjContratado
                    $contratadoExecutante->appendChild($cnpjContratado);
                    $CalcHash .= $_XML['cnpjContratado'];


                    $nomeContratado = $xml->createElement("ans:nomeContratado", $_XML['nomeContratado']); //nomeContratado
                    $contratadoExecutante->appendChild($nomeContratado);
                    $CalcHash .= $_XML['nomeContratado'];

                    $CNES = $xml->createElement("ans:CNES", $_XML['CNES']); //CNES
                    $dadosExecutante->appendChild($CNES);
                    $CalcHash .= $_XML['CNES'];


                    //No dados internação
                    $dadosInternacao = $xml->createElement("ans:dadosInternacao"); //dadosInternacao
                    $guiaResumoInternacao->appendChild($dadosInternacao);

                        $caraterAtendimento = $xml->createElement("ans:caraterAtendimento",$_XML['caraterAtendimento']); //caraterAtendimento
                        $dadosInternacao->appendChild($caraterAtendimento);
                        $CalcHash .= $_XML['caraterAtendimento'];

                        $tipoFaturamento = $xml->createElement("ans:tipoFaturamento",$_XML['tipoFaturamento']); //tipoFaturamento
                        $dadosInternacao->appendChild($tipoFaturamento);
                        $CalcHash .= $_XML['tipoFaturamento'];

                        $dataInicioFaturamento = $xml->createElement("ans:dataInicioFaturamento",$_XML['dataInicioFaturamento']); //dataInicioFaturamento
                        $dadosInternacao->appendChild($dataInicioFaturamento);
                        $CalcHash .= $_XML['dataInicioFaturamento'];

                        $horaInicioFaturamento = $xml->createElement("ans:horaInicioFaturamento",$_XML['horaInicioFaturamento']); //horaInicioFaturamento
                        $dadosInternacao->appendChild($horaInicioFaturamento);
                        $CalcHash .= $_XML['horaInicioFaturamento'];

                        $dataFinalFaturamento = $xml->createElement("ans:dataFinalFaturamento",$_XML['dataFinalFaturamento']); //dataFinalFaturamento
                        $dadosInternacao->appendChild($dataFinalFaturamento);
                        $CalcHash .= $_XML['dataFinalFaturamento'];

                        $horaFinalFaturamento = $xml->createElement("ans:horaFinalFaturamento",$_XML['horaFinalFaturamento']); //horaFinalFaturamento
                        $dadosInternacao->appendChild($horaFinalFaturamento);
                        $CalcHash .= $_XML['horaFinalFaturamento'];

                        $tipoInternacao = $xml->createElement("ans:tipoInternacao",$_XML['tipoInternacao']); //tipoInternacao
                        $dadosInternacao->appendChild($tipoInternacao);
                        $CalcHash .= $_XML['tipoInternacao'];

                        $regimeInternacao = $xml->createElement("ans:regimeInternacao",$_XML['regimeInternacao']); //regimeInternacao
                        $dadosInternacao->appendChild($regimeInternacao);
                        $CalcHash .= $_XML['regimeInternacao'];

                    //No dados saida internação
                    $dadosSaidaInternacao = $xml->createElement("ans:dadosSaidaInternacao"); //dadosSaidaInternacao
                    $guiaResumoInternacao->appendChild($dadosSaidaInternacao);
                        if(isset($_XML['diagnostico']) && !empty($_XML['diagnostico'])){
                            $diagnostico = $xml->createElement("ans:diagnostico",$_XML['diagnostico']); //diagnostico
                            $dadosSaidaInternacao->appendChild($diagnostico);
                            $CalcHash .= $_XML['diagnostico'];
                        }

                        $indicadorAcidente = $xml->createElement("ans:indicadorAcidente",$_XML['indicadorAcidente']); //indicadorAcidente
                        $dadosSaidaInternacao->appendChild($indicadorAcidente);
                        $CalcHash .= $_XML['indicadorAcidente'];

                        $motivoEncerramento = $xml->createElement("ans:motivoEncerramento",$_XML['motivoEncerramento']); //motivoEncerramento
                        $dadosSaidaInternacao->appendChild($motivoEncerramento);
                        $CalcHash .= $_XML['motivoEncerramento'];
                    //inicio No de procedimentos
                    $procedimentosExecutados = $xml->createElement("ans:procedimentosExecutados"); //procedimentosExecutados
                    $guiaResumoInternacao->appendChild($procedimentosExecutados);
                    if(isset($_XML['procedimentos']) && is_array($_XML['procedimentos'])){
                        foreach ($_XML['procedimentos'] as $key => $v) {
                                    //listagem de procedimentos
                                    $procedimentoExecutado = $xml->createElement("ans:procedimentoExecutado"); //procedimentoExecutado
                                    $procedimentosExecutados->appendChild($procedimentoExecutado);
                                    //elementos

                                    $sequencialItem = $xml->createElement("ans:sequencialItem",@$v['item']); //sequencialItem
                                    $procedimentoExecutado->appendChild($sequencialItem);
                                    $CalcHash .= trim(@$v['item']);
                                    $dataExecucao = $xml->createElement("ans:dataExecucao",@$v['data']); //dataExecucao
                                    $procedimentoExecutado->appendChild($dataExecucao);
                                    $CalcHash .= trim(@$v['data']);
                                    if(!empty($v['hora1'])){
                                        $v['hora1'] .= ':00';
                                    }
                                    if(!empty($v['hora2'])){
                                        $v['hora2'] .= ':00';
                                    }
                                    $horaInicial = $xml->createElement("ans:horaInicial",@$v['hora1']); //horaInicial
                                    $procedimentoExecutado->appendChild($horaInicial);
                                    $CalcHash .= trim(@$v['hora1']);
                                    $horaFinal = $xml->createElement("ans:horaFinal",@$v['hora2']); //horaFinal
                                    $procedimentoExecutado->appendChild($horaFinal);
                                    $CalcHash .= trim(@$v['hora2']);

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
                                    $viaAcesso = $xml->createElement("ans:viaAcesso",@$v['via']); //viaAcesso
                                    $procedimentoExecutado->appendChild($viaAcesso);
                                    $CalcHash .= trim(@$v['via']);

                                    $tecnicaUtilizada = $xml->createElement("ans:tecnicaUtilizada",@$v['tec']); //tecnicaUtilizada
                                    $procedimentoExecutado->appendChild($tecnicaUtilizada);
                                    $CalcHash .= trim(@$v['tec']);

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

                        //$valorProcedimentos = $xml->createElement("ans:valorProcedimentos",$_XML['valorProcedimentos']); //valorProcedimentos
                        //$valorTotal->appendChild($valorProcedimentos);
                        //$CalcHash .= $_XML['valorProcedimentos'];

                        $valorProcedimentos = $xml->createElement("ans:valorProcedimentos",$_XML['valorProcedimentos']); //valorProcedimentos
                        $valorTotal->appendChild($valorProcedimentos);
                        $CalcHash .= $_XML['valorProcedimentos'];
                        if($_XML['valorDiarias'] == '0.00'){
                            $_XML['valorDiarias']=0;
                        }
                        $valorDiarias = $xml->createElement("ans:valorDiarias",$_XML['valorDiarias']); //valorDiarias
                        $valorTotal->appendChild($valorDiarias);
                        $CalcHash .= $_XML['valorDiarias'];

                        if($_XML['valorTaxasAlugueis'] == '0.00'){
                            $_XML['valorTaxasAlugueis']=0;
                        }

                        $valorTaxasAlugueis = $xml->createElement("ans:valorTaxasAlugueis",$_XML['valorTaxasAlugueis']); //valorTaxasAlugueis
                        $valorTotal->appendChild($valorTaxasAlugueis);
                        $CalcHash .= $_XML['valorTaxasAlugueis'];

                        if($_XML['valorMateriais'] == '0.00'){
                            $_XML['valorMateriais']=0;
                        }


                        $valorMateriais = $xml->createElement("ans:valorMateriais",$_XML['valorMateriais']); //valorMateriais
                        $valorTotal->appendChild($valorMateriais);
                        $CalcHash .= $_XML['valorMateriais'];

                        if($_XML['valorMedicamentos'] == '0.00'){
                            $_XML['valorMedicamentos']=0;
                        }


                        $valorMedicamentos = $xml->createElement("ans:valorMedicamentos",$_XML['valorMedicamentos']); //valorMedicamentos
                        $valorTotal->appendChild($valorMedicamentos);
                        $CalcHash .= $_XML['valorMedicamentos'];

                        if($_XML['valorOPME'] == '0.00'){
                            $_XML['valorOPME']=0;
                        }


                        $valorOPME = $xml->createElement("ans:valorOPME",$_XML['valorOPME']); //valorOPME
                        $valorTotal->appendChild($valorOPME);
                        $CalcHash .= $_XML['valorOPME'];

                        if($_XML['valorGasesMedicinais'] == '0.00'){
                            $_XML['valorGasesMedicinais']=0;
                        }


                        $valorGasesMedicinais = $xml->createElement("ans:valorGasesMedicinais",$_XML['valorGasesMedicinais']); //valorGasesMedicinais
                        $valorTotal->appendChild($valorGasesMedicinais);
                        $CalcHash .= $_XML['valorGasesMedicinais'];

                        $valorTotalGeral = $xml->createElement("ans:valorTotalGeral",$_XML['valorTotalGeral']); //valorTotalGeral
                        $valorTotal->appendChild($valorTotalGeral);
                        $CalcHash .= $_XML['valorTotalGeral'];

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
                    echo $file = $path.'/app/public/xml/'.$_XML['numeroLote']."_".$_XML['hash'].'.xml';
                    $xml->save($file);


                    # Imprime / Gera o xml em tela
                   // header('content-type: text/xml');
                    print $xml->saveXML();

        return $ret;

    }
    public function guiaConsulta( $var = null)
    {
        $ret = false;
        $arr_var = [];

        #Definindo as variáveis
        $_XML['padrao_tiss'] = '3.05.00';
        $_XML['numeroLote'] = '1';

        #Dados Operadora:
        $_XML['registro_ans'] = '1111';
        $_XML['numeroGuiaSolicitacaoInternacao'] = '1111';
        #Dados Autorização
        $_XML['numeroGuiaOperadora'] = '1111';
        $_XML['dataAutorizacao'] = '1111';

        #Dados Prestador:
        $_XML['codigo_credenciamento'] = '12345';
        $_XML['cnpj'] = '0000000000000';
        $_XML['prestador'] = 'Hospital ABCD';

        #Dados do atendimento e beneficiário
        $_XML['data_hora'] = '03/04/2018 22:15:00';
        $_XML['rn'] = 'N'; #Não
        $_XML['tipo_atd'] = 'Ambulatorial';
        $_XML['carater'] = 'E'; #Eletivo
        $_XML['guia'] = '1111';
        $_XML['senha'] = '2222';
        $_XML['medico'] = 'Dr. Fabiano';
        $_XML['crm'] = '0000';
        $_XML['cbos'] = '';
        #Dados do beneficiário
        $_XML['nome'] = 'Fulano de Tal';
        $_XML['dt_nascimento'] = '01/01/1980';
        $_XML['carteira']  = '4444444444444444';
        $_XML['validade'] = '31/12/2030';

        #Dados da Fatura / Lote
        $_XML['fatura_remessa'] = '123';

        #Dados do procedimento realizado
        $_XML['tabela'] = '22';
        $_XML['procedimento_tuss'] = '10101012';
        $_XML['descricao_proced'] = 'Consulta em consultório';
        $_XML['valor_unitario'] = '500,00';
        $_XML['qtde'] = '1';
        $_XML['valor_total'] = '500,00';

        $_XML['tipoTransacao'] = 'ENVIO_LOTE_GUIAS';
        $_XML['sequencialTransacao'] = '6658';
        $_XML['dataRegistroTransacao'] = '2018-01-18';
        $_XML['horaRegistroTransacao'] = '10:00:00';

        # Utilize a variável $_XML['hash_dados'] para concatenar os dados e calcular o HASH antes do terceiro bloco
        $_XML['hash_dados'] = '';

        #A variável $_XML['hash'] está nula pois deve ser calculada com os dados dos elementos(tags) do XML
        $_XML['hash'] = 'calculo do HASH';
        $_XML['registroANS'] = '45621';
        $_XML['numeroGuiaPrestador'] = '789461';

        //$_XML[''] = ''; // para criar novas variáveis apenas siga o padrão
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
                        $tipoTransacao = $xml->createElement("ans:tipoTransacao", $_XML['tipoTransacao']);
                        $identificacaoTransacao->appendChild($tipoTransacao);

                        #sequencialTransacao
                        $sequencialTransacao = $xml->createElement("ans:sequencialTransacao", $_XML['sequencialTransacao']);
                        $identificacaoTransacao->appendChild($sequencialTransacao);

                        #dataRegistroTransacao
                        $dataRegistroTransacao = $xml->createElement("ans:dataRegistroTransacao", $_XML['dataRegistroTransacao']);
                        $identificacaoTransacao->appendChild($dataRegistroTransacao);

                        #horaRegistroTransacao
                        $horaRegistroTransacao = $xml->createElement("ans:horaRegistroTransacao", $_XML['horaRegistroTransacao']);
                        $identificacaoTransacao->appendChild($horaRegistroTransacao);


                // ans:mensagemTISS / ans:cabecalho / ans:origem
                $origem = $xml->createElement("ans:origem");
                $cabecalho->appendChild($origem);

                        // ans:mensagemTISS / ans:cabecalho / ans:origem / identificacaoPrestador
                        $identificacaoPrestador = $xml->createElement("ans:identificacaoPrestador");
                        $origem->appendChild($identificacaoPrestador);
                            //$CNPJ = $xml->createElement("ans:CNPJ", $_XML['cnpj']);
                            //$identificacaoPrestador->appendChild($CNPJ);


                // ans:mensagemTISS / ans:cabecalho / ans:destino
                $destino = $xml->createElement("ans:destino");
                $cabecalho->appendChild($destino);

                        // ans:mensagemTISS / ans:cabecalho / ans:registroANS
                        $registroANS = $xml->createElement("ans:registroANS", $_XML['registro_ans']);
                        $destino->appendChild($registroANS);

                // ans:mensagemTISS / ans:cabecalho / ans:Padrao
                $Padrao = $xml->createElement("ans:Padrao", $_XML['padrao_tiss']);
                $cabecalho->appendChild($Padrao);


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

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiasTISS
                    $guiasTISS = $xml->createElement("ans:guiasTISS");
                    $loteGuias->appendChild($guiasTISS);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / guiaConsulta

                    $guiaConsulta = $xml->createElement("ans:guiaConsulta");
                    $loteGuias->appendChild($guiaConsulta);

                    // ans:mensagemTISS / ans:prestadorParaOperadora / loteGuias / cabecalhoConsulta
                    $cabecalhoConsulta = $xml->createElement("ans:cabecalhoConsulta");
                    $guiaConsulta->appendChild($cabecalhoConsulta);

                    $registroANS = $xml->createElement("ans:registroANS", $_XML['registroANS']); //registroANS
                    $cabecalhoConsulta->appendChild($registroANS);

                    $registroANS = $xml->createElement("ans:numeroGuiaPrestador", $_XML['numeroGuiaPrestador']); //numeroGuiaPrestador
                    $cabecalhoConsulta->appendChild($registroANS);

                    $numeroGuiaOperadora = $xml->createElement("ans:numeroGuiaOperadora", $_XML['numeroGuiaOperadora']); //numeroGuiaOperadora
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
                    $_XML['hash_dados'] = trim(strip_tags($xmlAqui));
                    $_XML['hash_dados'] = preg_replace('/\s+/', '', $_XML['hash_dados']);
                    $_XML['hash'] = md5($_XML['hash_dados']);
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

                    $xml->save("xml_tiss.xml");


                    # Imprime / Gera o xml em tela
                    header('content-type: text/xml');
                    print $xml->saveXML();

        return $ret;
    }
}
