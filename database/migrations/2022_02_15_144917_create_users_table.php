<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('status',['actived','inactived','pre_registred']);
            $table->enum('gender',['male','female']);
            $table->enum('profile',['dev','admin','user']);
            $table->integer('id_permission')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->text('image')->nullable();
            $table->enum('ativo',['s','n']);
            $table->integer('autor')->nullable();
            $table->string('token','60')->nullable();
            $table->enum('excluido',['n','s']);
            $table->text('reg_excluido')->nullable();
            $table->enum('deletado',['n','s']);
            $table->text('reg_deletado')->nullable();

        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
