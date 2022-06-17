<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQoptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qoptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token','64')->nullable();
            $table->string('nome','64')->nullable();
            $table->string('url','64')->nullable();
            $table->longText('valor')->nullable();
            $table->text('obs')->nullable();
            $table->string('painel','2')->nullable();
            $table->enum('ativo',['s','n']);
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
        Schema::dropIfExists('qoptions');
    }
}
