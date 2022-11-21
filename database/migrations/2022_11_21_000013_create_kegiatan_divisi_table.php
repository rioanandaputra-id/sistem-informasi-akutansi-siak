<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan_divisi', function (Blueprint $table) {
            $table->uuid('id_kegiatan_divisi')->primary();
            $table->foreignUuid('id_divisi');
            $table->foreignUuid('id_kegiatan');
            $table->char('a_verif_rba', 1);
            $table->foreignUuid('id_verif_rba')->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_divisi');
    }
};
