<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laksana_kegiatan', function (Blueprint $table) {
            $table->uuid('id_laksana_kegiatan')->primary();
            $table->foreignUuid('id_kegiatan_divisi');
            $table->dateTime('tgl_ajuan');
            $table->char('a_verif_kabag_keuangan', 1)->nullable();
            $table->foreignUuid('id_verif_kabag_keuangan')->nullable();
            $table->dateTime('tgl_verif_kabag_keuangan')->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('waktu_pelaksanaan');
            $table->dateTime('waktu_selesai');
            $table->char('tahun', 4);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('laksana_kegiatan');
    }
};
