<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.keg', function (Blueprint $table) {
            $table->uuid('id_keg')->primary();
            $table->text('nm_keg');
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_selesai');
            $table->char('a_aktif', 1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.keg');
    }
};
