<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBairrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bairros', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token','100')->nullable();
            $table->string('nome',300)->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
            $table->string('cidade',200)->nullable();
            $table->string('area',12)->nullable();
            $table->string('matricula',25)->nullable();
            $table->integer('total_quadras')->nullable();
            $table->integer('total_lotes')->nullable();
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
        Schema::dropIfExists('bairros');
    }
}
