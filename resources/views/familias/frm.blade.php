
<form id="frm-familias" class="" action="@if($familia['ac']=='cad'){{ route('familias.store') }}@elseif($familia['ac']=='alt'){{ route('familias.update',['id'=>$familia['id']]) }}@endif" method="post">
    @if($familia['ac']=='alt')
    @method('PUT')
    @endif
    <div class="row">
        <div class="col-md-12 text-right">
            @if (isset($familia['id']))
                <label for="">Id:</label> {{ $familia['id'] }}
            @endif
            @if (isset($familia['created_at']))
                <label for="">Cadastro:</label> {{ Carbon\Carbon::parse($familia['created_at'])->format('d/m/Y') }}
            @endif

        </div>


        {{App\Qlib\Qlib::qForm([
            'type'=>'tel',
            'campo'=>'area_alvo',
            'placeholder'=>'Ex.: 4',
            'label'=>'Área alvo*',
            'ac'=>$familia['ac'],
            'value'=>@$familia['area_alvo'],
            'tam'=>'2',
            'event'=>'',
        ])}}
        <div class="form-group col-md-3">
            <label for="loteamento">Loteamento*</label>
            <input type="text" class="form-control @error('loteamento') is-invalid @enderror" id="loteamento" name="loteamento" aria-describedby="loteamento" placeholder="Loteamento" value="@if(isset($familia['loteamento'])){{$familia['loteamento']}}@elseif($familia['ac']=='cad'){{old('loteamento')}}@endif" />
            @error('loteamento')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-3">
            <label for="matricula">Matrícula*</label>
            <input type="text" class="form-control @error('matricula') is-invalid @enderror" id="matricula" name="matricula" aria-describedby="matricula" placeholder="Matrícula" value="@if(isset($familia['matricula'])){{$familia['matricula']}}@elseif($familia['ac']=='cad'){{old('matricula')}}@endif" />
            @error('matricula')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-2">
            <label for="quadra">Quadra*</label>
            <input type="text" class="form-control @error('quadra') is-invalid @enderror" id="quadra" name="quadra" aria-describedby="quadra" placeholder="Quadra" value="@if(isset($familia['quadra'])){{$familia['quadra']}}@elseif($familia['ac']=='cad'){{old('quadra')}}@endif" />
            @error('quadra')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-2">
            <label for="lote">Lote*</label>
            <input type="text" class="form-control @error('lote') is-invalid @enderror" id="lote" name="lote" aria-describedby="lote" placeholder="Lote" value="@if(isset($familia['lote'])){{$familia['lote']}}@elseif($familia['ac']=='cad'){{old('lote')}}@endif" />
            @error('lote')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-8">
            <label for="nome_completo">Nome completo do responsável</label>
            <input type="text" class="form-control @error('nome_completo') is-invalid @enderror" id="nome_completo" name="nome_completo" aria-describedby="nome_completo" placeholder="Nome completo" value="@if(isset($familia['nome_completo'])){{$familia['nome_completo']}}@elseif($familia['ac']=='cad'){{old('nome_completo')}}@endif" />
            @error('nome_completo')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="cpf">CPF</label>
            <input type="cpf" class="form-control @error('cpf') is-invalid @enderror" id="cpf" data-mask="999.999.999-99" name="cpf" aria-describedby="cpf" placeholder="999.999.999-99" value="@if(isset($familia['cpf'])){{$familia['cpf']}}@elseif($familia['ac']=='cad'){{old('cpf')}}@endif">
            @error('cpf')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-8">
            <label for="nome_conjuge">Nome do cônjuge</label>
            <input type="text" class="form-control @error('nome_conjuge') is-invalid @enderror" id="nome_conjuge" name="nome_conjuge" aria-describedby="nome_conjuge" placeholder="Nome do cônjuge" value="@if(isset($familia['nome_conjuge'])){{$familia['nome_conjuge']}}@elseif($familia['ac']=='cad'){{old('nome_conjuge')}}@endif" />
            @error('nome_conjuge')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="cpf_conjuge">CPF do cônjuge</label>
            <input type="tel" class="form-control @error('cpf_conjuge') is-invalid @enderror" id="cpf_conjuge" data-mask="999.999.999-99" name="cpf_conjuge" aria-describedby="cpf_conjuge" placeholder="999.999.999-99" value="@if(isset($familia['cpf_conjuge'])){{$familia['cpf_conjuge']}}@elseif($familia['ac']=='cad'){{old('cpf_conjuge')}}@endif">
            @error('cpf_conjuge')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        {{App\Qlib\Qlib::qForm([
            'type'=>'tel',
            'campo'=>'telefone',
            'placeholder'=>'Telefone de contato',
            'label'=>'Telefone',
            'ac'=>$familia['ac'],
            'value'=>@$familia['telefone'],
            'tam'=>'6',
            'event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);',
        ])}}
        {{App\Qlib\Qlib::qForm([
            'type'=>'tel',
            'campo'=>'config[telefone2]',
            'placeholder'=>'Segundo con',
            'label'=>'Telefone 2',
            'ac'=>$familia['ac'],
            'value'=>@$familia['config']['telefone2'],
            'tam'=>'6',
            'event'=>'onblur=mask(this,clientes_mascaraTelefone); onkeypress=mask(this,clientes_mascaraTelefone);',
        ])}}
        {{App\Qlib\Qlib::qForm([
            'type'=>'select',
            'campo'=>'escolaridade',
            'label'=>'Escolaridade',
            'arr_opc'=>$arr_escolaridade,
            'value'=>@$familia['escolaridade'],
            'tam'=>'6',
            'option_select'=>true,
        ])}}
        {{App\Qlib\Qlib::qForm([
            'type'=>'select',
            'campo'=>'estado_civil',
            'label'=>'Estado civil',
            'arr_opc'=>$arr_estadocivil,
            'value'=>@$familia['estado_civil'],
            'tam'=>'6',
            'option_select'=>true,
        ])}}
        <div class="form-group col-md-4">
            <label for="situacao_profissional">Situação profissional</label>
            <input type="text" class="form-control @error('situacao_profissional') is-invalid @enderror" id="situacao_profissional" name="situacao_profissional" aria-describedby="situacao_profissional" placeholder="Situação profissional" value="@if(isset($familia['situacao_profissional'])){{$familia['situacao_profissional']}}@elseif($familia['ac']=='cad'){{old('situacao_profissional')}}@endif" />
            @error('situacao_profissional')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-2">
            <label for="qtd_membros" title="quantidade de membros">Qtd membros</label>
            <input type="number" class="form-control @error('qtd_membros') is-invalid @enderror" id="qtd_membros" name="qtd_membros" aria-describedby="qtd_membros" placeholder="0" value="@if(isset($familia['qtd_membros'])){{$familia['qtd_membros']}}@elseif($familia['ac']=='cad'){{old('qtd_membros')}}@endif" />
            @error('qtd_membros')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group col-md-3">
            <label for="bcp_bolsa_familia">BPC ou Bolsa família</label>
            <input type="text" class="form-control @error('bcp_bolsa_familia') is-invalid @enderror" id="bcp_bolsa_familia" name="bcp_bolsa_familia" aria-describedby="bcp_bolsa_familia" placeholder="BPC ou Bolsa família" value="@if(isset($familia['bcp_bolsa_familia'])){{$familia['bcp_bolsa_familia']}}@elseif($familia['ac']=='cad'){{old('bcp_bolsa_familia')}}@endif" />
            @error('bcp_bolsa_familia')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        {{App\Qlib\Qlib::qForm([
            'type'=>'text',
            'campo'=>'renda_familiar',
            'label'=>'Renda familiar',
            'class'=>'moeda',
            'value'=>number_format(@$familia['renda_familiar'],2,',','.'),
            'tam'=>'3',
            'option_select'=>true,
        ])}}

        <div class="form-row col-md-12">
            {{App\Qlib\Qlib::qForm([
                'type'=>'chave_checkbox',
                'campo'=>'idoso',
                'label'=>'Idoso',
                'arr_opc'=>'',
                'checked'=>@$familia['idoso'],
                'value'=>'s',
                'tam'=>'6',
            ])}}

            <div class="col-md-6">
                <div class="custom-control custom-switch">
                    <input type="checkbox" @if(isset($familia['crianca_adolescente']) && $familia['crianca_adolescente']=='s') checked @endif value="s" name="crianca_adolescente" class="custom-control-input" id="customSwitch2">
                    <label class="custom-control-label" for="customSwitch2">Criança e adolescente</label>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12">
            <label for="doc_imovel">Possui doc. do Imóvel</label>
            <input type="text" class="form-control @error('doc_imovel') is-invalid @enderror" id="doc_imovel" name="doc_imovel" aria-describedby="doc_imovel" placeholder="Se possui, informe qual documento" value="@if(isset($familia['doc_imovel'])){{$familia['doc_imovel']}}@elseif($familia['ac']=='cad'){{old('doc_imovel')}}@endif" />
            @error('doc_imovel')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-12 mb-5">
            <div class="form-group">
            <label for="obs">Observação</label><br>
            <textarea name="obs" class="form-control" rows="8" cols="80">@if(isset($familia['obs'])){{$familia['obs']}}@elseif($familia['ac']=='cad'){{old('obs')}}@endif</textarea>
            </div>
        </div>
        <div class="col-md-12 div-salvar">
            <div class="form-group">
            <a href=" {{route('familias.index')}} " class="btn btn-light"><i class="fa fa-chevron-left"></i> Voltar</a>

            <button type="submit" class="btn btn-primary">Salvar <i class="fa fa-chevron-right"></i></button>
            </div>
        </div>
        @csrf
    </div>
</form>
