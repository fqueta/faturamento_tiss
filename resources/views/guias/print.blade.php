@extends('layouts.principal')

@section('nav')

@endsection

@section('content')

    <style>
        html,
        body {
            font-size: 10px;
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Segoe UI, Verdana, Tahoma, Arial, sans-serif;
        }

        .td_texto {
            font-size: 16px;
            color: #000000;
            text-align: left;
            line-height: 25px;
            background-color: silver;
            border: 1px solid silver;
            border-left: 0;
            border-right: 0;
            padding-left: 9px
        }

        .celula_guia {
            font-size: 12px;
            color: #000000;
            border: 1px solid #000000;
            background-color: #ffffff;
            height: 45px;
        }

        .campo_titulo {
            font-size: 12px;
            color: #000000;
            border: none;
            padding-left: 3px;
            height: 15px;
        }

        .campo_texto {
            font-size: 12px;
            color: #000000;
            border: none;
            padding-left: 3px;
            padding-right: 3px;
        }

        .campo_texto2 {
            font-size: 12px;
            color: #000000;
            border: none;
            padding-left: 3px;
            padding-right: 3px;
            text-align: right
        }

        .campo_texto3 {
            font-size: 12px;
            color: #000000;
            border: none;
            padding-left: 3px;
            padding-right: 3px;
            text-align: center
        }

        .celula_item {
            border: 1px solid #000000;
            height: 20px;
            font-size: 11px
        }

        p.breakhere {
            page-break-after: always;
        }
    </style>

    <table cellpading="0" cellspacing="0" width="980" align="center" bgcolor="white" style="border:1px solid #444444">
        <tr>
            <td>
                <table width="100%" height="100">
                    <tr>
                        <td width="240" style="font-size:12px; overflow:hidden"><b>{{@$dados_operadora['nome']}}</b></td>
                        <td align="center" style="font-size:25px; font-weight:bold">GUIA DE RESUMO DE INTERNAÇÃO</td>
                        <td width="240" align="right">2-Nº Guia no Prestador
                            <BR><span style="font-size:14px; font-weight:bold">{{$dados['numero_guia']}}</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                <tr>
                                    <td class="campo_titulo">1-Registro ANS</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['registro_ans']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="235">
                                <tr>
                                    <td class="campo_titulo">3-Nº da Guia de Solicitação de Internação</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['numeroGuiaSolicitacaoInternacao']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                <tr>
                                    <td class="campo_titulo">4-Data da Autorização</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{App\Qlib\Qlib::dataExibe($dados['config']['data_autorizacao'])}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="175">
                                <tr>
                                    <td class="campo_titulo">5-Senha</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['senha']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="165">
                                <tr>
                                    <td class="campo_titulo">6-Data de Validade da Senha</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{App\Qlib\Qlib::dataExibe($dados['config']['dataValidadeSenha'])}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="220">
                                <tr>
                                    <td class="campo_titulo">7-Nº da Guia Atribuído pela Operadora</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['numeroGuiaOperadora']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td_texto">Dados do Beneficiário</td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                <tr>
                                    <td class="campo_titulo">8-Número da Carteira</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['numeroCarteira']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                <tr>
                                    <td class="campo_titulo">9-Validade da Carteira</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{App\Qlib\Qlib::dataExibe($dados['config']['validadeCarteira'])}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="510">
                                <tr>
                                    <td class="campo_titulo">10-Nome</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['nome']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="165">
                                <tr>
                                    <td class="campo_titulo">11-Cartão Nacional de Saúde</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto"></td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="125">
                                <tr>
                                    <td class="campo_titulo">12-Atendimento a RN</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['atendimentoRN']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td_texto">Dados do Contratado Executante</td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="160">
                                <tr>
                                    <td class="campo_titulo">13-Código na Operadora</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['codigoNaOperadora']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="785">
                                <tr>
                                    <td class="campo_titulo">14-Nome do Contratado</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['nomeContratado']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="120">
                                <tr>
                                    <td class="campo_titulo">15-Código CNES</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['codigoCNES']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td_texto">Dados da Internação</td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="180">
                                <tr>
                                    <td class="campo_titulo">16-Caráter do Atendimento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{$dados['config']['caraterAtendimento']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="175">
                                <tr>
                                    <td class="campo_titulo">17-Tipo de Faturamento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['tipoFaturamento']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="180">
                                <tr>
                                    <td class="campo_titulo">18-Data Início Faturamento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{App\Qlib\Qlib::dataExibe(@$dados['config']['dataInicioFaturamento'])}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="170">
                                <tr>
                                    <td class="campo_titulo">19-Hora Início Faturamento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['horaInicioFaturamento']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="180">
                                <tr>
                                    <td class="campo_titulo">20-Data Fim Faturamento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['dataFinalFaturamento']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="170">
                                <tr>
                                    <td class="campo_titulo">21-Hora Fim Faturamento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['horaFinalFaturamento']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="162">
                                <tr>
                                    <td class="campo_titulo">22-Tipo de Internação</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['tipoInternacao']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="162">
                                <tr>
                                    <td class="campo_titulo">23-Regime de Internação</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['regimeInternacao']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                <tr>
                                    <td class="campo_titulo">24-CID 10 Principal</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['CID10Principal']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                <tr>
                                    <td class="campo_titulo">25-CID 10 (2)</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['CID10_2']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                <tr>
                                    <td class="campo_titulo">26-CID 10 (3)</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['CID10_3']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                <tr>
                                    <td class="campo_titulo">27-CID 10 (4)</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['CID10_4']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="170">
                                <tr>
                                    <td class="campo_titulo">28-Indicação de Acidente</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['indicadorAcidente']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="200">
                                <tr>
                                    <td class="campo_titulo">29-Motivo do Encerramento</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['motivoEncerramento']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="260">
                                <tr>
                                    <td class="campo_titulo">30-Número da declaração de nascido vivo</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['declaracaoNascidoVivo']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                <tr>
                                    <td class="campo_titulo">31-CID 10 Óbito</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['CID10Obito']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="260">
                                <tr>
                                    <td class="campo_titulo">32-Número da declaração de óbito</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['nDeclaracaoObito']}}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="2"></td>
                        <td class="celula_guia">
                            <table cellpadding="0" height="100%" cellspacing="0" width="200">
                                <tr>
                                    <td class="campo_titulo">33-Indicador DO de RN</td>
                                </tr>
                                <tr>
                                    <td class="campo_texto">{{@$dados['config']['indicadorDoRN']}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td class="td_texto">Procedimentos e Exames Realizados</td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td class="celula_item" align="center" width="20">&nbsp;</td>
                        <td class="celula_item" align="center" width="65">Data</td>
                        <td class="celula_item" align="center" width="65">Hora Inicial</td>
                        <td class="celula_item" align="center" width="60">Hora Final</td>
                        <td class="celula_item" align="center" width="40">Tabela</td>
                        <td class="celula_item" align="center" width="70">Código</td>
                        <td class="celula_item" align="center">Descrição</td>
                        <td class="celula_item" align="center" width="40">Qtde</td>
                        <td class="celula_item" align="center" width="35">Via</td>
                        <td class="celula_item" align="center" width="35">Téc</td>
                        <td class="celula_item" align="center" width="50">Fator</td>
                        <td class="celula_item" align="center" width="80">Valor Unitário</td>
                        <td class="celula_item" align="center" width="60">Valor Total</td>
                    </tr>
                    @php
                        $min_proced_imprime = App\Qlib\Qlib::qoption('min_proced_imprime');
                        $linhasProced= $min_proced_imprime?$min_proced_imprime:3;
                        $tipo=1;
                        $lin=0;
                        if(isset($dados['config']['procedimento'])){
                            $procedi = count($dados['config']['procedimento']);
                            if($procedi>$linhasProced){
                                $linhasProced=$procedi;
                                $tipo=2;
                            }
                        }
                        //dd($dados['config']['procedimento']);
                    @endphp
                    @if($tipo==1)
                        @for ($i=1;$i<=$linhasProced;$i++)
                            @if(isset($dados['config']['procedimento'][$i]['item']) && !empty($dados['config']['procedimento'][$i]['descricao']))
                            <tr>
                                <td class="celula_item" align="center" width="20">{{$i}}</td>
                                <td class="celula_item" align="center" width="65">{{App\Qlib\Qlib::dataExibe(@$dados['config']['procedimento'][$i]['data'])}}</td>
                                <td class="celula_item" align="center" width="65">{{@$dados['config']['procedimento'][$i]['hora1']}}</td>
                                <td class="celula_item" align="center" width="60">{{@$dados['config']['procedimento'][$i]['hora2']}}</td>
                                <td class="celula_item" align="center" width="40">{{@$dados['config']['procedimento'][$i]['tabela']}}</td>
                                <td class="celula_item" align="center" width="70">{{@$dados['config']['procedimento'][$i]['codigo']}}</td>
                                <td class="celula_item" align="left">{{@$dados['config']['procedimento'][$i]['descricao']}}</td>
                                <td class="celula_item" align="center" width="40">{{@$dados['config']['procedimento'][$i]['quantidade']}}</td>
                                <td class="celula_item" align="center" width="35">{{@$dados['config']['procedimento'][$i]['via']}}</td>
                                <td class="celula_item" align="center" width="35">{{@$dados['config']['procedimento'][$i]['tec']}}</td>
                                <td class="celula_item" align="center" width="50">{{@$dados['config']['procedimento'][$i]['fator']}}</td>
                                <td class="celula_item" align="center" width="80">{{@$dados['config']['procedimento'][$i]['valor_unitario']}}</td>
                                <td class="celula_item" align="center" width="60">{{@$dados['config']['procedimento'][$i]['valor_total']}}</td>
                            </tr>
                            @else

                            <tr>
                                <td class="celula_item" align="center" width="20">{{$i}}</td>
                                <td class="celula_item" align="center" width="65">&nbsp;</td>
                                <td class="celula_item" align="center" width="65">&nbsp;</td>
                                <td class="celula_item" align="center" width="60">&nbsp;</td>
                                <td class="celula_item" align="center" width="40">&nbsp;</td>
                                <td class="celula_item" align="center" width="70">&nbsp;</td>
                                <td class="celula_item" align="left">&nbsp;</td>
                                <td class="celula_item" align="center" width="40">&nbsp;</td>
                                <td class="celula_item" align="center" width="35">&nbsp;</td>
                                <td class="celula_item" align="center" width="35">&nbsp;</td>
                                <td class="celula_item" align="center" width="50">&nbsp;</td>
                                <td class="celula_item" align="center" width="80">&nbsp;</td>
                                <td class="celula_item" align="center" width="60">&nbsp;</td>
                            </tr>

                            @endif
                        @endfor
                    @elseif ($tipo==2)
                        @foreach ($dados['config']['procedimento'] as $i=>$val)

                            @if(!empty($val['descricao']))
                                @php
                                    $lin++;
                                @endphp
                                <tr>
                                    <td class="celula_item" align="center" width="20">{{$lin}}</td>
                                    <td class="celula_item" align="center" width="65">{{App\Qlib\Qlib::dataExibe(@$val['data'])}}</td>
                                    <td class="celula_item" align="center" width="65">{{@$val['hora1']}}</td>
                                    <td class="celula_item" align="center" width="60">{{@$val['hora2']}}</td>
                                    <td class="celula_item" align="center" width="40">{{@$val['tabela']}}</td>
                                    <td class="celula_item" align="center" width="70">{{@$val['codigo']}}</td>
                                    <td class="celula_item" align="left">{{@$val['descricao']}}</td>
                                    <td class="celula_item" align="center" width="40">{{@$val['quantidade']}}</td>
                                    <td class="celula_item" align="center" width="35">{{@$val['via']}}</td>
                                    <td class="celula_item" align="center" width="35">{{@$val['tec']}}</td>
                                    <td class="celula_item" align="center" width="50">{{@$val['fator']}}</td>
                                    <td class="celula_item" align="center" width="80">{{@$val['valor_unitario']}}</td>
                                    <td class="celula_item" align="center" width="60">{{@$val['valor_total']}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif

                </table>
            </td>
        </tr>
        <tr>
            <td class="td_texto">Identificação do(s) Profissional(is) Executante(s)</td>
        </tr>
        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td class="celula_item" align="center" width="50">Seq. Ref.</td>
                        <td class="celula_item" align="center" width="70">Grau Part.</td>
                        <td class="celula_item" align="center">Nome do Profissional</td>
                        <td class="celula_item" align="center" width="140">Cod. na Operadora/CPF</td>
                        <td class="celula_item" align="center" width="60">Conselho</td>
                        <td class="celula_item" align="center" width="95">Nº no Conselho</td>
                        <td class="celula_item" align="center" width="30">UF</td>
                        <td class="celula_item" align="center" width="75">Código CBO</td>
                    </tr>
                    @php
                        $linhasExec=8;
                    @endphp
                    @for ($i=1;$i<=$linhasExec;$i++)
                        @if(isset($dados['config']['executantes'][$i]['item']))
                            <tr>
                                <td class="celula_item" align="center" width="50">{{@$dados['config']['executantes'][$i]['item']}}</td>
                                <td class="celula_item" align="center" width="70">{{@$dados['config']['executantes'][$i]['seq']}}</td>
                                <td class="celula_item" align="left">{{@$dados['config']['executantes'][$i]['ex_nome']}}</td>
                                <td class="celula_item" align="center" width="140">{{@$dados['config']['executantes'][$i]['grau_part']}}</td>
                                <td class="celula_item" align="center" width="60">{{@$dados['config']['executantes'][$i]['codigo']}}</td>
                                <td class="celula_item" align="center" width="95">{{@$dados['config']['executantes'][$i]['conselho']}}</td>
                                <td class="celula_item" align="center" width="30">{{@$dados['config']['executantes'][$i]['conselho_uf']}}</td>
                                <td class="celula_item" align="center" width="75">{{@$dados['config']['executantes'][$i]['conselho_numero']}}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="celula_item" align="center" width="50">&nbsp;</td>
                                <td class="celula_item" align="center" width="70">&nbsp;</td>
                                <td class="celula_item" align="left">&nbsp;</td>
                                <td class="celula_item" align="center" width="140">&nbsp;</td>
                                <td class="celula_item" align="center" width="60">&nbsp;</td>
                                <td class="celula_item" align="center" width="95">&nbsp;</td>
                                <td class="celula_item" align="center" width="30">&nbsp;</td>
                                <td class="celula_item" align="center" width="75">&nbsp;</td>
                            </tr>
                        @endif
                    @endfor



                </table>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tr>
                                            <td class="campo_titulo">54-Procedimentos (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorProcedimentos']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tr>
                                            <td class="campo_titulo">55-Diárias (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorDiarias']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                        <tr>
                                            <td class="campo_titulo">56-Taxas e Aluguéis (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorTaxasAlugueis']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="110">
                                        <tr>
                                            <td class="campo_titulo">57-Materiais (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorMateriais']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="110">
                                        <tr>
                                            <td class="campo_titulo">58-OPME (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorOPME']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="145">
                                        <tr>
                                            <td class="campo_titulo">59-Medicamentos (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorMedicamentos']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="150">
                                        <tr>
                                            <td class="campo_titulo">60-Gases Medicinais (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorGasesMedicinais']}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tr>
                                            <td class="campo_titulo">61-Total Geral (R$)</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">{{@$dados['config']['valorTotalGeral']}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="220">
                                        <tr>
                                            <td class="campo_titulo">62- Data da Assinatura do Contratado</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="350">
                                        <tr>
                                            <td class="campo_titulo">63- Assinatura do Contratado</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="495">
                                        <tr>
                                            <td class="campo_titulo">64-Assinatura do(s) Auditor(es) da Operadora</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto">&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="1073">
                                        <tr>
                                            <td class="campo_titulo">65-Observações</td>
                                        </tr>
                                        <tr>
                                            <td class="campo_texto" height="80" valign="top">{{@$dados['obs']}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
    </table>
    <style>
        .btn{
            font-size: 1.3rem !important;
        }
    </style>
    <div class="col-md-12 d-print-none " style="position: fixed;bottom:3px;z-index:2;background-color:#ffffff">
        <button type="button" onclick="window.close();" class="btn btn-outline-danger">Fechar</button>
        <button type="button" class="btn btn-primary" onclick="window.print();">Imprimir</button>
        @if (isset($dados['config']['despesas']))
            <a href="{{route('guias.print.anexo',['id'=>$dados['id']])}}" class="btn btn-outline-primary" title="Imprimir Anexo">Anexo</a>
        @endif
    </div>

    <div style="page-break-after:avoid;font-size:1;margin:0;border:0;"><span style="visibility: hidden;">&nbsp;</span></div>

    <script language="javascript">
        window.onload = function(){
            window.print();
        }
        //parent.btn_print_3.style.display = 'none'
    </script>
    <BR>
    <BR>
    <BR>
    <BR>

@endsection

@section('css')
    @include('qlib.cssiframe')
@endsection

@section('js')
    {{-- @include('qlib.jslib') --}}
@endsection
