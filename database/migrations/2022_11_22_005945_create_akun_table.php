<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->uuid('id_akun')->primary();
            $table->string('no_akun', 10);
            $table->string('nm_akun', 255);
            $table->text('keterangan')->nullable();
            $table->foreignUuid('sumber_akun')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('akun');
    }
};
