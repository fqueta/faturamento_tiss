<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaturamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token',100)->nullable();
            $table->string('nome',300)->nullable();
            $table->integer('id_operadora')->nullable();
            $table->string('mes')->nullable();
            $table->string('protocolo')->nullable();
            $table->longText('guias')->nullable();
            $table->enum('enviado',['n','s']);
            $table->integer('ano')->nullable();
            $table->string('type',300)->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
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
        Schema::dropIfExists('faturamentos');
    }
}
