<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bku', function (Blueprint $table) {
            $table->uuid('id_bku')->primary();
            $table->foreignUuid('id_divisi');
            $table->foreignUuid('id_laksana_kegiatan');
            $table->dateTime('tanggal');
            $table->foreignUuid('id_akun')->nullable();
            $table->double('masuk')->nullable();
            $table->double('keluar')->nullable();
            $table->double('saldo');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bku');
    }
};
