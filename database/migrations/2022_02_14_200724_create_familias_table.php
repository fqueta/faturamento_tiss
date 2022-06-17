<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('familias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token','100')->nullable();
            $table->string('area_alvo','100')->nullable();
            $table->json('loteamento')->nullable();
            $table->integer('tipo_residencia')->nullable();
            $table->integer('etapa')->nullable();
            //$table->integer('id_loteamento')->nullable();
            $table->integer('id_beneficiario')->nullable();
            $table->integer('id_conjuge')->nullable();
            $table->string('matricula','100')->nullable();
            $table->string('quadra','50')->nullable();
            $table->string('lote','100')->nullable();
            $table->string('nome_completo','250')->nullable();
            $table->string('cpf','20')->unique()->nullable();
            $table->string('nome_conjuge','250')->nullable();
            $table->string('cpf_conjuge','20')->nullable();
            $table->string('telefone','20')->nullable();
            $table->string('escolaridade','100')->nullable();
            $table->string('estado_civil','50')->nullable();
            $table->string('situacao_profissional','150')->nullable();
            $table->integer('qtd_membros')->nullable();
            $table->longText('membros')->nullable();
            $table->enum('idoso',['n','s']);
            $table->enum('crianca_adolescente',['s','n']);
            $table->string('bcp_bolsa_familia','100')->nullable();
            $table->string('renda_familiar','200')->nullable();
            $table->text('doc_imovel')->nullable();
            $table->string('endereco','250')->nullable();
            $table->string('numero','100')->nullable();
            $table->string('bairro','200')->nullable();
            $table->string('cidade','100')->nullable();
            $table->string('complemento_lote','100')->nullable();
            $table->integer('autor')->nullable();
            $table->longText('obs')->nullable();
            $table->json('tags')->nullable();
            $table->json('config')->nullable();
            $table->enum('excluido',['n','s']);
            $table->text('reg_excluido')->nullable();
            $table->enum('deletado',['n','s']);
            $table->text('reg_deletado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('familias');
    }
}
