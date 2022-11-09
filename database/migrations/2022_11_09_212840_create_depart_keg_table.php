<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.depart_keg', function (Blueprint $table) {
            $table->uuid('id_depart_keg')->primary();
            $table->foreignUuid('id_depart');
            $table->foreignUuid('id_prog');
            $table->foreignUuid('id_keg');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.depart_keg');
    }
};
