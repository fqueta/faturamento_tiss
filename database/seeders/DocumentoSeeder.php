<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('documentos')->insert([
            [
                'nome'=>'Cabeçalho',
                'tipo'=>'html',
                'url'=>'cabecario-lista-beneficiario',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p>
                </p><table width="662" cellspacing="0" cellpadding="7">
                    <colgroup><col width="142">

                    <col width="492">

                    </colgroup><tbody><tr valign="top">
                        <td style="background: transparent" width="142" height="80"><img src="/storage/documentos/logo_prefeitura.png" style="width: 100%;">
                        <br></td>
                        <td style="background: transparent" width="492"><p style="margin-bottom: 0.35cm; orphans: 0; widows: 0" align="center">
                            <font face="Arial, serif"><b>MUNICÍPIO DE CONCEIÇÃO DO MATO
                            DENTRO<br>
                </b>Rua Daniel de Carvalho, 161, Centro – CEP
                            35.860-000</font></p>
                            <p style="orphans: 0; widows: 0" align="center"><font face="Arial, serif">ESTADO
                            DE MINAS GERAIS</font></p>
                        </td>
                    </tr>
                </tbody></table>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Lista de beneficiário parte1',
                'tipo'=>'html',
                'url'=>'lista-beneficiario',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p style="margin-bottom: 0cm; line-height: 100%" align="justify">
                <font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{lote}-
                BENEFICIÁRIO(A): {tipo_beneficiario}:</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font>{dados_beneficiario} residente e domiciliado na {endereco}, n°{numero},
                Bairro {bairro}, no município de {cidade}-{uf}, CEP: {cep}.</p><p style="margin-bottom: 0cm; line-height: 100%" align="justify">
<br>

</p>
<p style="margin-bottom: 0cm; line-height: 100%" align="justify"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>IMÓVEL</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
LOTE {lote} ({lote_extenso}) – QUADRA {quadra} ({quadra_extenso}), conforme memorial
descritivo do PRF.  </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Valor
Lote</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>R$</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{valor_lote}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
 </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Valor
Edificação</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>R$</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{valor_edificacao}.</b></font></font></p>
<p style="margin-bottom: 0cm; line-height: 100%" align="justify"></p><div><font face="Arial Narrow, serif"></font></div><p></p>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Beneficiario com parceiro',
                'tipo'=>'html',
                'url'=>'lista-beneficiario-2',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{nome_beneficiario}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">,
                {nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}
                {pai} e {mae}, RG:
                {rg}, CPF: {cpf} </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">e</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                {seu companheiro}</font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>
                {nome_conjuge}, </b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">{nacionalidade_conjuge},
                </font></font><font color="#000000"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">{estado_civil_conjuge},
                {profissao_conjuge}</font></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">,
                nascido aos {nascimento_conjuge},</font></font><font color="#ff0000"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">filho
                de {pai_conjuge} e {mae_conjuge}, RG: {rg_conjuge},
                CPF: {cpf_conjuge}, vivendo em união estável desde {data_uniao}, <br></font></font>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Beneficiario sem parceiro',
                'tipo'=>'html',
                'url'=>'lista-beneficiario-3',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{nome_beneficiario}</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">,
                {nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}
                {pai} e {mae}, RG:
                {rg}, CPF: {cpf},</font></font>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Lote sem beneficiario',
                'tipo'=>'html',
                'url'=>'lote-sem-beneficiario',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p style="margin-bottom: 0cm; line-height: 100%" align="justify">
                <font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>{lote}.
                BENEFICIÁRIO(A):</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">
                </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Município
                de Conceição do Mato Dentro/MG, </b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">pessoa
                Jurídica de Direito Público Interno, inscrita no CNPJ:
                18.303.156/0001-07, com sede à Rua Daniel de Carvalho, nº 161,
                Centro, nesta cidade e comarca Conceição do Mato Dentro/MG, CEP:
                35.680-000, representado por José Fernando Aparecido de Oliveira,
                brasileiro, casado, prefeito, portador do RG: M-3.618.630, inscrito
                no CPF sob o nº 032.412.426-09, residente e domiciliado na Rua Raul
                Soares, nº 253, Bairro centro, Conceição do Mato Dentro, CEP:
                35.680-000.</font></font></p>
                <p style="margin-bottom: 0cm; line-height: 100%" align="justify"><br>

                </p>
                <p style="margin-bottom: 0cm; line-height: 100%" align="justify"><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>IMÓVEL</b></font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">:
                LOTE {lote}  ({lote_extenso} ) – QUADRA </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">{quadra}
                ({quadra_extenso}),   </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3">conforme
                memorial descritivo do PRF.  </font></font><font face="Arial Narrow, serif"><font style="font-size: 12pt" size="3"><b>Valor
                Lote: R$ {valor_lote}.   Valor Edificação: R$ {valor_edificacao}.</b></font></font></p>
                <p style="margin-bottom: 0cm; line-height: 100%" align="justify"><font face="Arial Narrow, serif"></font></p>
                <p></p>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Ficha de cadastro de ocupante',
                'tipo'=>'html',
                'url'=>'ficha-cadastro-ocupante',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center">
                <font color="#000000"> <font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Ficha
                de Cadastro de Ocupante</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%"><br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Unidade
                Imobiliária</b></font></font></font><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>:
                </b></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Lote {lote} ({lote_extenso}) – Quadra {quadra} ({quadra_extenso})</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Bairro: {bairro}</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Lote: {area_lote} m²</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Construção: {area_construcao} m²</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify"><a name="_Hlk82675389"></a>
                {ocupantes}
                </p><p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Origem
                da </b></font></font></font><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Unidade:
                </b></font></font><font face="Arial, serif"><font style="font-size: 12pt" size="3">R-1
                da Matrícula {matricula} do Cartório de Imóveis de </font></font><font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">{cidade}-{uf}.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Declarações
                adicionais sobre a posse:</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">1.
                Os ocupantes acima adquiriram a unidade imobiliária por:</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="left">
                {declaracao_posse}</p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="left">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="left">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">2.
                Data do início da posse</font></font></font><font face="Arial, serif"><font style="font-size: 12pt" size="3">:
                </font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">{data_posse}</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">3.
                Titulação a ser outorgada ao ocupante: Legitimação fundiária</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">4.
                Em caso de se tratar de Reurb-S sobre imóvel público ou privado com
                titulação final em legitimação fundiária ou legitimação de
                posse, declaramos: I - não somos beneficiários concessionários,
                foreiros ou proprietários de imóvel urbano ou rural; II - não
                fomos beneficiários contemplados por legitimação de posse ou
                fundiária de imóvel urbano com a mesma finalidade, ainda que
                situado em núcleo urbano distinto; e III - quanto a imóvel urbano
                com finalidade não residencial, foi reconhecido pelo Poder Público
                o interesse público de minha ocupação.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">5.
                Declaro que identifico esse imóvel acima retratado, manifestando
                minha concordância com as descrições, os confrontantes e a
                titulação final.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">6.
                Declaro ciente de que a partir da disponibilidade de equipamentos e
                infraestrutura para prestação de serviço público, estamos
                obrigados a realizar a conexão da edificação que ocupo à rede de
                água, de coleta de esgoto ou de distribuição de energia elétrica
                e adotarmos as demais providências necessárias à utilização do
                serviço, exceto se houver disposição em contrário na legislação
                municipal ou distrital, conforme art. 5º, § 10 do Decreto 9310/18.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">7.
                Declaro, ainda, que tenho conhecimento das sanções penais que estou
                sujeito caso inverídica declaração prestada, sobretudo a
                disciplinada no artigo 299 do Código Penal.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify">
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><font color="#000000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">Conceição
                do Mato Dentro, </font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">{dia}
                de {mes_extenso} de {ano}.</font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><a name="_GoBack"></a>
                <br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>___________________________________________</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>{nome_proprietario}</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><br>

                </p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>_______________________________</b></font></font></font></p>
                <p class="western" style="margin-bottom: 0cm; line-height: 150%" align="center"><font color="#ff0000">
                <font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>{nome_conjuge}</b></font></font></font></p>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Ocupante com parceiro',
                'tipo'=>'html',
                'url'=>'ocupante-com-parceiro',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify"><a name="_Hlk82675389"></a>
                <font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Ocupantes
                identificados: </b></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>{nome_beneficiario}</b></font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">,
                {nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}
                {pai} e {mae},
                RG:{rg}, CPF:{cpf} </font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">e</font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>
                {nome_conjuge}, </b></font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">{nacionalidade_conjuge},
                {estado_civil_conjuge}, {profissao_conjuge}, nascido aos {nascimento_conjuge}, filho de {pai_conjuge} e {mae_conjuge}, RG: {rg_conjuge},
                CPF:{cpf_conjuge}, {casados} sob o regime de {tipo_uniao}
                desde {data_uniao}, residentes e domiciliadas na {endereco},
                n°{numero}, Bairro {bairro}, no município de {cidade}-MG, CEP: {cep}.</font></font></font></p>
                <p></p>',
                'excluido'=>'n',
            ],
            [
                'nome'=>'Ocupante sem parceiro',
                'tipo'=>'html',
                'url'=>'ocupante-sem-parceiro',
                'token'=>uniqid(),
                'ativo'=>'s',
                'conteudo'=>'<p class="western" style="margin-bottom: 0cm; line-height: 150%" align="justify"><a name="_Hlk82675389"></a>
                <font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>Ocupantes
                identificados: </b></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"><b>{nome_beneficiario}</b></font></font></font><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3">,
                {nacionalidade}, {estado_civil}, {profissao}, {nascida} aos {nascimento}, {filha de}
                {pai} e {mae},
                RG:{rg}, CPF:{cpf}, </font></font></font><font color="#ff0000"><font face="Arial, serif"><font color="#ff0000"><font face="Arial, serif"><font style="font-size: 12pt" size="3"> residentes e domiciliadas na {endereco},
                n°{numero}, Bairro {bairro}, no município de {cidade}-MG, CEP: {cep}.</font></font></font></font></font></p><font color="#ff0000"><font face="Arial, serif">
                <p></p></font></font>',
                'excluido'=>'n',
            ],
        ]);
    }
}
