<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('spj', function (Blueprint $table) {
            $table->uuid('id_spj')->primary();
            $table->foreignUuid('id_laksana_kegiatan');
            $table->char('a_verif_bendahara_pengeluaran', 1)->nullable();
            $table->foreignUuid('id_verif_bendahara_pengeluaran')->nullable();
            $table->dateTime('tgl_verif_bendahara_pengeluaran')->nullable();
            $table->char('a_verif_kabag_keuangan', 1)->nullable();
            $table->foreignUuid('id_verif_kabag_keuangan')->nullable();
            $table->dateTime('tgl_verif_kabag_keuangan')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spj');
    }
};
