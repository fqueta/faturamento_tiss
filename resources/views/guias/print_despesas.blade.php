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
            padding-left: 9px;
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
            text-align: right;
        }
        .campo_texto3 {
            font-size: 12px;
            color: #000000;
            border: none;
            padding-left: 3px;
            padding-right: 3px;
            text-align: center;
        }
        .celula_item {
            border: 1px solid #000000;
            height: 20px;
            font-size: 11px;
        }
        p.breakhere {
            page-break-after: always;
        }
        .border-2x{
            border: solid 2px #333333;
        }
    </style>

    <table cellpading="0" cellspacing="0" width="980" align="center" bgcolor="white" style="border: 1px solid #444444;">
        <tbody>
            <tr>
                <td>
                    <table width="100%" height="100">
                        <tbody>
                            <tr>
                                <td align="center" style="font-size: 25px; font-weight: bold;">
                                    ANEXO DE OUTRAS DESPESAS<br />
                                    (para Guia de SP/SADT e Resumo de Internação)
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="140">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">1-Registro ANS</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['registro_ans']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="200">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">2–Número da Guia Referenciada</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['numero_guia']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="td_texto">Dados do Contratado Executante</td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="150">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">3-Código na Operadora</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['codigoNaOperadora']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="718">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">4-Nome do Contratado</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['nomeContratado']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="100">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">5-Código CNES</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['codigoCNES']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="td_texto">Despesas Realizadas</td>
            </tr>
            <tr>
                <td>
                    @php
                        $linhasProced=10;
                        $tipo=1;
                        $lin=0;
                        if(isset($dados['config']['despesas'])){
                            $despesas = count($dados['config']['despesas']);
                            if($despesas>$linhasProced){
                                $linhasProced=$despesas;
                                $tipo=2;
                            }
                        }
                        //dd($dados['config']['despesas']);
                    @endphp
                    @if($tipo==1||$tipo==2)
                        @foreach ($dados['config']['despesas'] as $k1=>$v1)
                        @php
                            $linhasProced--;
                        @endphp
                        <table width="100%" class="mb-2 border-2x">
                            <tbody>

                                <tr>
                                    <td class="celula_item" width="20">
                                        <font style="font-size: 9px;">CD</font>
                                        <div align="right">{{App\Qlib\Qlib::zerofill(@$v1['tipo'],2)}}</div>
                                    </td>
                                    <td class="celula_item" width="75">
                                        <font style="font-size: 9px;">Data</font>
                                        <div align="right">{{App\Qlib\Qlib::dataExibe(@$v1['data'])}}</div>
                                    </td>
                                    <td class="celula_item" width="50">
                                        <font style="font-size: 9px;">Hora Inicial</font>
                                        <div align="right">{{$v1['hora1']}}</div>
                                    </td>
                                    <td class="celula_item" width="50">
                                        <font style="font-size: 9px;">Hora Final</font>
                                        <div align="right">{{$v1['hora2']}}</div>
                                    </td>
                                    <td class="celula_item" width="35">
                                        <font style="font-size: 9px;">Tabela</font>
                                        <div align="right">{{$v1['tabela']}}</div>
                                    </td>
                                    <td class="celula_item">
                                        <font style="font-size: 9px;">Código</font>
                                        <div align="right">{{$v1['codigo']}}</div>
                                    </td>
                                    <td class="celula_item" width="35">
                                        <font style="font-size: 9px;">Qtde</font>
                                        <div align="right">{{$v1['quantidade']}}</div>
                                    </td>
                                    <td class="celula_item" width="70">
                                        <font style="font-size: 9px;">Fator Red/Acres.</font>
                                        <div align="right">{{$v1['fator']}}</div>
                                    </td>
                                    <td class="celula_item" width="55">
                                        <font style="font-size: 9px;">Valor Unitário</font>
                                        <div align="right">{{$v1['valor_unitario']}}</div>
                                    </td>
                                    <td class="celula_item" width="55">
                                        <font style="font-size: 9px;">Valor Total</font>
                                        <div align="right">{{$v1['valor_total']}}</div>
                                    </td>
                                    <td class="celula_item">
                                        <font style="font-size: 9px;">Registro ANVISA</font>
                                        <div align="right">{{$v1['anvisa']}}</div>
                                    </td>
                                    <td class="celula_item">
                                        <font style="font-size: 9px;">Ref. Fabricante</font>
                                        <div align="right">{{$v1['fabricante']}}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="celula_item" colspan="3">
                                        <font style="font-size: 9px;">Nº Autorização</font>
                                        <div align="right">{{$v1['autorizacao']}}</div>
                                    </td>
                                    <td class="celula_item" colspan="9" width="800">
                                        <font style="font-size: 9px;">Descrição</font>
                                        <div align="left">{{$v1['descricao']}}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        @endforeach
                        @if ($tipo==1)
                        @for ($i=1;$i<=$linhasProced;$i++)
                            <table width="100%" class="mb-2 border-2x">
                                <tbody>
                                    <tr>
                                        <td class="celula_item" width="20">
                                            <font style="font-size: 9px;">CD</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="75">
                                            <font style="font-size: 9px;">Data</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="50">
                                            <font style="font-size: 9px;">Hora Inicial</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="50">
                                            <font style="font-size: 9px;">Hora Final</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="35">
                                            <font style="font-size: 9px;">Tabela</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item">
                                            <font style="font-size: 9px;">Código</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="35">
                                            <font style="font-size: 9px;">Qtde</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="70">
                                            <font style="font-size: 9px;">Fator Red/Acres.</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="55">
                                            <font style="font-size: 9px;">Valor Unitário</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" width="55">
                                            <font style="font-size: 9px;">Valor Total</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item">
                                            <font style="font-size: 9px;">Registro ANVISA</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item">
                                            <font style="font-size: 9px;">Ref. Fabricante</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="celula_item" colspan="3">
                                            <font style="font-size: 9px;">Nº Autorização</font>
                                            <div align="right">&nbsp;</div>
                                        </td>
                                        <td class="celula_item" colspan="9" width="800">
                                            <font style="font-size: 9px;">Descrição</font>
                                            <div align="left">&nbsp;</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        @endfor
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>
                    <table cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="150">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">21-Gases Medicinais (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorGasesMedicinais']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">22-Medicamentos (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorMedicamentos']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">23-Materiais (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorMateriais']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">24-OPME (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorOPME']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="150">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">25-Taxas e Aluguéis (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorTaxasAlugueis']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">26-Diárias (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorDiarias']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td width="2"></td>
                                <td class="celula_guia">
                                    <table cellpadding="0" height="100%" cellspacing="0" width="130">
                                        <tbody>
                                            <tr>
                                                <td class="campo_titulo">27-Total Geral (R$)</td>
                                            </tr>
                                            <tr>
                                                <td class="campo_texto">{{$dados['config']['valorTotalGeral']}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <style>
        .btn{
            font-size: 1.3rem !important;
        }
    </style>
    <div class="col-md-12 d-print-none " style="position: fixed;bottom:3px;z-index:2">
        <a href="{{route('guias.print',['id'=>$dados['id']])}}" class="btn btn-outline-danger">Voltar</a>
        <button type="button" class="btn btn-primary" onclick="window.print();">Imprimir</button>
    </div>
    <div style="page-break-after: avoid; font-size: 1; margin: 0; border: 0;"><span style="visibility: hidden;">&nbsp;</span></div>

    <br />
    <br />
    <br />
    <br />
@endsection

@section('css')
    @include('qlib.cssiframe')
@endsection

@section('js')
    {{-- @include('qlib.jslib') --}}
@endsection
