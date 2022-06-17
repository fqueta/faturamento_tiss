<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_uploads', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('token_produto','100')->nullable();
            $table->text('pasta')->nullable();
            $table->string('nome','150')->nullable();
            $table->integer('ordem')->nullable();
            $table->longText('obs')->nullable();
            $table->longText('config')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_uploads');
    }
}
