<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rba', function (Blueprint $table) {
            $table->uuid('id_rba')->primary();
            $table->dateTime('tgl_buat');
            $table->dateTime('tgl_submit')->nullable();
            $table->foreignUuid('id_kegiatan_divisi');
            $table->char('a_verif_rba', 1)->nullable();
            $table->text('catatan_verif_rba')->nullable();
            $table->foreignUuid('id_verif_rba')->nullable();
            $table->dateTime('tgl_verif_rba')->nullable();
            $table->char('a_verif_wilayah', 1)->nullable();
            $table->text('catatan_verif_wilayah')->nullable();
            $table->foreignUuid('id_verif_wilayah')->nullable();
            $table->dateTime('tgl_verif_wilayah')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rba');
    }
};
