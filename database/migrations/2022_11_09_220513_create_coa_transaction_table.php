<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.coa_transaction', function (Blueprint $table) {
            $table->uuid('id_coa_transaction')->primary();
            $table->foreignUuid('id_coa');
            $table->foreignUuid('id_depart_keg');
            $table->bigInteger('total');
            $table->dateTime('tgl');
            $table->char('a_keluar', 1);
            $table->char('a_masuk', 1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.coa_transaction');
    }
};
