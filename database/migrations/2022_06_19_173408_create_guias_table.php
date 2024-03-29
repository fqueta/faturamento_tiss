<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token',100)->nullable();
            $table->string('nome',300)->nullable();
            $table->integer('id_cliente')->nullable();
            $table->string('numero_guia')->nullable();
            $table->string('type',300)->nullable();
            $table->enum('ativo',['s','n']);
            $table->enum('lote',['n','s']);
            $table->integer('ordem')->nullable();
            $table->integer('id_lote')->nullable();
            $table->integer('autor')->nullable();
            $table->longText('obs')->nullable();
            $table->json('config')->nullable();
            $table->json('meta')->nullable();
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
        Schema::dropIfExists('guias');
    }
}
