<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome',300)->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
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
        Schema::dropIfExists('statuses');
    }
}
