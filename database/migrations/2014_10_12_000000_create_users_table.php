<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id_user')->primary();
            $table->foreignUuid('id_divisi');
            $table->string('full_name', 255);
            $table->string('nik', 16)->nullable();
            $table->char('gender', 1);
            $table->string('username', 100)->unique();
            $table->string('password', 100);
            $table->string('phone', 15)->nullable();
            $table->string('email', 100)->unique();
            $table->text('address')->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->uuid('id_updater')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
