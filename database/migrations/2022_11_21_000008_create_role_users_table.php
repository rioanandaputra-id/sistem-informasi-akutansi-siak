<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('role_users', function (Blueprint $table) {
            $table->uuid('id_role_user')->primary();
            $table->foreignId('id_role');
            $table->foreignUuid('id_user');
            $table->tinyInteger('a_active')->default(0);
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('role_users');
    }
};
