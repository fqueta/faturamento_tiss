@component('mail::message')

<h1>Olá {{ $user->name }}</h1>
<p>Estou fazendo um teste de mail no email {{$user->email}} </p>
    @component('mail::button',['url'=>'https://databrasil.app.br'])
        Garanta sua vaga
    @endcomponent,

@endcomponent
