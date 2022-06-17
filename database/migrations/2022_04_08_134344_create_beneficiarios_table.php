<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeneficiariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('tipo')->nullable();
            $table->string('nome');
            $table->string('email')->nullable();
            $table->enum('sexo',['m','f']);
            $table->text('image')->nullable();
            $table->longText('obs')->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
            $table->string('cpf','20')->nullable();
            $table->integer('conjuge')->nullable();
            $table->json('config')->nullable();
            $table->string('token','60')->nullable();
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
        Schema::dropIfExists('beneficiarios');
    }
}
