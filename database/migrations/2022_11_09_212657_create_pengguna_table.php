<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.pengguna', function (Blueprint $table) {
            $table->uuid('id_pengguna')->primary();
            $table->string('username', 100);
            $table->string('password', 100);
            $table->string('nm_pengguna', 255);
            $table->char('jk', 1);
            $table->text('alamat');
            $table->string('no_hp', 15);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.pengguna');
    }
};
