<div class="col-md-12">

    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">
                {{__($config['label'])}}
            </h3>
        </div>
        @php
            $tema = '<td>{nome}</td>
                        <td>{parentesco}</td>
                        <td>{idade}</td>
                        <td>{renda}</td>';
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
                                <th scope="col" class="text-center" style="width: 35%">{{__('Nome')}}</th>
                                <th scope="col" class="text-center">{{__('Parentesco')}}</th>
                                <th scope="col" class="text-center">{{__('Nascimento')}}</th>
                                <th scope="col" class="text-center">{{__('Renda')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arr as $k=>$v)
                            <tr data-id="{{$k}}">
                                <td class="text-left">{{$v['nome']}}</td>
                                <td class="text-center">{{$v['parentesco']}}</td>
                                <td class="text-center">{{App\Qlib\Qlib::dataExibe(@$v['nascimento']).' '.@$v['idade']}}</td>
                                <td class="text-right">{{$v['renda']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
