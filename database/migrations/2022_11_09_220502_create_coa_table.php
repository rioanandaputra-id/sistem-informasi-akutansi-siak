<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('siak.coa', function (Blueprint $table) {
            $table->uuid('id_coa')->primary();
            $table->foreignUuid('id_sub_coa');
            $table->string('nm_coa', 255);
            $table->text('uraian');
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siak.coa');
    }
};
