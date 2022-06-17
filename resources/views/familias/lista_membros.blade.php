<div class="card card-secondary">
    <div class="card-header">
        <h3 class="card-title">
            {{__($config['label'])}}
        </h3>
    </div>
    @php
        $tema = '<td><input type="text" name="nome" value="{nome}" class="form-control" /></td>
                    <td><input type="text" name="parentesco" value="{parentesco}" class="form-control" /></td>
                    <td><input type="number" name="idade" value="{idade}" class="form-control" /></td>
                    <td><input type="tel" name="renda" value="{renda}" class="form-control moeda" /></td>
                    <td><button class="btn btn-default" type="button" onclick="removeRow()"><i class="fas fa-trash"></i></button></td>';
        $json_value = isset($config['value'])?$config['value']:false;
        if($json_value){
            $arr = json_decode($json_value,true);
        }else{
            $arr = [
                ['nome'=>'','parentesco'=>'','idade'=>'','renda'=>''],
                ['nome'=>'','parentesco'=>'','idade'=>'','renda'=>''],
            ];
        }
    @endphp
    <div class="card-body">
        <div class="row">
            <div class="table-responsive">
                <table id="list-membros" class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center" style="width: 35%">Nome</th>
                            <th scope="col" class="text-center">Parentesco</th>
                            <th scope="col" class="text-center">Nascimento</th>
                            <th scope="col" class="text-center">Renda</th>
                            <th scope="col" class="text-center">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($arr as $k=>$v)
                        <tr data-id="{{$k}}">
                            <td><input type="text" name="membros[{{$k}}][nome]" value="{{$v['nome']}}" class="form-control" /></td>
                            <td><input type="text" name="membros[{{$k}}][parentesco]" value="{{$v['parentesco']}}" class="form-control" /></td>
                            <td><input type="date" name="membros[{{$k}}][nascimento]" value="{{@$v['nascimento']}}{{@$v['idade']}}" class="form-control" /></td>
                            <td><input type="tel" name="membros[{{$k}}][renda]" value="{{$v['renda']}}" class="form-control moeda" /></td>
                            <td> <button class="btn btn-default" type="button" onclick="removeRow('{{$k}}')"><i class="fas fa-trash"></i></button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer text-muted">
        <input type="hidden" id="tema2" value="{{base64_encode($tema)}}"/>
        <button class="btn btn-default" type="button" onclick="addRowPag('#list-membros tbody','membros','<tr data-id={id}>{td}</tr>');"> <i class="fas fa-plus"></i> Adicionar membro</button>
    </div>
</div>
<script>
    function addRowPag(s,campo,tm){
        var t = $(s).find('tr:last').html(),id = $(s).find('tr:last').data('id');
        if(typeof id=='undefined'){
            id = 0;
            var tm2 =$('#tema2').val();
            t = atob(tm2);
        }else{
            id++;
        }
        var v = tm.replaceAll('{td}',t);
        v = v.replaceAll('{id}',id);
        $(s).append(v);
        var arr_c = ['nome','parentesco','idade','renda'];
        //alert(id);
        $(s).find('tr:last').data('id',id);
        for (let i = 0; i < arr_c.length; i++) {
            const el = arr_c[i];
            var n = campo+'['+id+']['+el+']';
            $(s).find('tr:last td input').eq( i ).attr('name',n);
            $(s).find('tr:last td input').eq( i ).attr('value','');
        }
        $(s).find('tr:last td button').attr('onclick','removeRow('+id+')');
        carregaMascaraMoeda(".moeda");
    }
    function removeRow(id){
        $('tr[data-id="'+id+'"]').remove();
    }
</script>
