<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.rab_keg', function (Blueprint $table) {
            $table->uuid('id_rab_keg')->primary();
            $table->foreignUuid('id_depart_keg');
            $table->text('uraian');
            $table->integer('qty');
            $table->string('satuan', 255);
            $table->bigInteger('total');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.rab_keg');
    }
};
