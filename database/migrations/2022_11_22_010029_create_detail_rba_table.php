<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detail_rba', function (Blueprint $table) {
            $table->uuid('id_detail_rba')->primary();
            $table->foreignUuid('id_rba');
            $table->foreignUuid('id_akun');
            $table->integer('vol');
            $table->string('satuan', 255);
            $table->integer('indikator')->nullable();
            $table->double('tarif');
            $table->double('total');
            $table->char('a_setuju', 1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detail_rba');
    }
};
