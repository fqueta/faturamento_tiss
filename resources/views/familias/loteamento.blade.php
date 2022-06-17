{{App\Qlib\Qlib::qForm([
    'type'=>'text',
    'campo'=>'complemento_lote',
    'label'=>'Complemento do lote (se o lote tiver mais de um beneficiário Ex.: 6A e 6B)',
    'placeholder'=>'Ex.: A ou B',
    'ac'=>$config['ac'],
    'value'=>@$_GET['dados']['complemento_lote'],
    'tam'=>'12',
    'event'=>'',
    'class'=>@$v['class'],
    'class_div'=>@$v['class_div'],
    'dados'=>@$v['dados'],
])}}
