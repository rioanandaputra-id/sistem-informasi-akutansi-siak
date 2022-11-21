<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_laksana_kegiatan', function (Blueprint $table) {
            $table->uuid('id_detail_laksana_kegiatan')->primary();
            $table->foreignUuid('id_laksana_kegiatan');
            $table->foreignUuid('id_detail_rba');
            $table->integer('jumlah');
            $table->double('total');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_laksana_kegiatan');
    }
};
