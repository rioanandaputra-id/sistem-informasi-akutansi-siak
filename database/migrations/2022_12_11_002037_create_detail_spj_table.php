<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_spj', function (Blueprint $table) {
            $table->uuid('id_detail_spj')->primary();
            $table->foreignUuid('id_spj');
            $table->foreignUuid('id_detail_laksana_kegiatan');
            $table->foreignUuid('id_akun');
            $table->double('total');
            $table->foreignUuid('id_dokumen')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_spj');
    }
};
