<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('program', function (Blueprint $table) {
            $table->uuid('id_program')->primary();
            $table->foreignUuid('id_misi')->nullable();
            $table->string('nm_program', 255);
            $table->char('periode', 4);
            $table->char('a_aktif', 1);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('program');
    }
};
