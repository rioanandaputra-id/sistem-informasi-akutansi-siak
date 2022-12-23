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
            $table->uuid('no_akun_induk')->nullable();
            $table->string('elemen', 1);
            $table->string('sub_elemen', 1);
            $table->string('jenis', 2);
            $table->string('no_akun', 4);
            $table->string('nm_akun', 255);
            $table->text('keterangan')->nullable();
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
