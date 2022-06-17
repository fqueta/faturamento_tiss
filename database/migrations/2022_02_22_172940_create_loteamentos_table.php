<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoteamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome',300)->nullable();
            $table->integer('quadra')->nullable();
            $table->string('token',60)->nullable();
            $table->string('cep','25')->nullable();
            $table->string('endereco','250')->nullable();
            $table->string('numero','100')->nullable();
            $table->text('complemento')->nullable();
            $table->string('cidade','100')->nullable();
            $table->integer('bairro')->nullable();
            $table->json('config')->nullable();
            $table->integer('autor')->nullable();
            $table->enum('ativo',['s','n']);
            $table->longText('obs')->nullable();
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
        Schema::dropIfExists('lotes');
    }
}
