<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.verif_keg', function (Blueprint $table) {
            $table->uuid('id_verif_keg')->primary();
            $table->foreignUuid('id_depart_keg');
            $table->foreignUuid('id_verif');
            $table->dateTime('tgl_verif');
            $table->text('catatan');
            $table->char('a_verif', 1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.verif_keg');
    }
};
