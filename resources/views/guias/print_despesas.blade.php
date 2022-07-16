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
                                                <td class="campo_texto">364592</td>
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
                                                <td class="campo_texto">1</td>
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
                                                <td class="campo_texto">38010265000143</td>
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
                                                <td class="campo_texto">CLINICA SUPERAR</td>
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
                                                <td class="campo_texto">9999999</td>
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
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="celula_item" width="20">
                                    <font style="font-size: 9px;">CD</font>
                                    <div align="right">02</div>
                                </td>
                                <td class="celula_item" width="75">
                                    <font style="font-size: 9px;">Data</font>
                                    <div align="right">14/07/2022</div>
                                </td>
                                <td class="celula_item" width="50">
                                    <font style="font-size: 9px;">Hora Inicial</font>
                                    <div align="right">12:22</div>
                                </td>
                                <td class="celula_item" width="50">
                                    <font style="font-size: 9px;">Hora Final</font>
                                    <div align="right">45:41</div>
                                </td>
                                <td class="celula_item" width="35">
                                    <font style="font-size: 9px;">Tabela</font>
                                    <div align="right">20</div>
                                </td>
                                <td class="celula_item">
                                    <font style="font-size: 9px;">Código</font>
                                    <div align="right">95185682</div>
                                </td>
                                <td class="celula_item" width="35">
                                    <font style="font-size: 9px;">Qtde</font>
                                    <div align="right">1</div>
                                </td>
                                <td class="celula_item" width="70">
                                    <font style="font-size: 9px;">Fator Red/Acres.</font>
                                    <div align="right">1.00</div>
                                </td>
                                <td class="celula_item" width="55">
                                    <font style="font-size: 9px;">Valor Unitário</font>
                                    <div align="right">41.14</div>
                                </td>
                                <td class="celula_item" width="55">
                                    <font style="font-size: 9px;">Valor Total</font>
                                    <div align="right">41.14</div>
                                </td>
                                <td class="celula_item">
                                    <font style="font-size: 9px;">Registro ANVISA</font>
                                    <div align="right">11111122</div>
                                </td>
                                <td class="celula_item">
                                    <font style="font-size: 9px;">Ref. Fabricante</font>
                                    <div align="right">9999999999</div>
                                </td>
                            </tr>
                            <tr>
                                <td class="celula_item" colspan="3">
                                    <font style="font-size: 9px;">Nº Autorização</font>
                                    <div align="right">777777777</div>
                                </td>
                                <td class="celula_item" colspan="9" width="800">
                                    <font style="font-size: 9px;">Descrição</font>
                                    <div align="left">HEMIFUMARATO DE QUETIAPINA 100 MG X 30 KITAPEN</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table width="100%">
                        <tbody>
                            <tr>
                                <td class="celula_item" width="20">
                                    <font style="font-size: 9px;">CD</font>
                                    <div align="right">05</div>
                                </td>
                                <td class="celula_item" width="75">
                                    <font style="font-size: 9px;">Data</font>
                                    <div align="right">29/05/2022</div>
                                </td>
                                <td class="celula_item" width="50">
                                    <font style="font-size: 9px;">Hora Inicial</font>
                                    <div align="right">07:00</div>
                                </td>
                                <td class="celula_item" width="50">
                                    <font style="font-size: 9px;">Hora Final</font>
                                    <div align="right">07:00</div>
                                </td>
                                <td class="celula_item" width="35">
                                    <font style="font-size: 9px;">Tabela</font>
                                    <div align="right">18</div>
                                </td>
                                <td class="celula_item">
                                    <font style="font-size: 9px;">Código</font>
                                    <div align="right">60000694</div>
                                </td>
                                <td class="celula_item" width="35">
                                    <font style="font-size: 9px;">Qtde</font>
                                    <div align="right">18</div>
                                </td>
                                <td class="celula_item" width="70">
                                    <font style="font-size: 9px;">Fator Red/Acres.</font>
                                    <div align="right">1.00</div>
                                </td>
                                <td class="celula_item" width="55">
                                    <font style="font-size: 9px;">Valor Unitário</font>
                                    <div align="right">84.70</div>
                                </td>
                                <td class="celula_item" width="55">
                                    <font style="font-size: 9px;">Valor Total</font>
                                    <div align="right">1524.60</div>
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
                                    <div align="left">DIARIA DE ENFEMEIRA</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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

                    <table width="100%">
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
                                                <td class="campo_texto">0.00</td>
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
                                                <td class="campo_texto">41.14</td>
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
                                                <td class="campo_texto">0.00</td>
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
                                                <td class="campo_texto">0.00</td>
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
                                                <td class="campo_texto">0.00</td>
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
                                                <td class="campo_texto">1524.60</td>
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
                                                <td class="campo_texto">1565.74</td>
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
