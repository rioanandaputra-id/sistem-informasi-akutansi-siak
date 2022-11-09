<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('siak.role_pengguna', function (Blueprint $table) {
            $table->foreign('id_pengguna')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_pengguna')
                ->on('siak.pengguna');
            $table->foreign('id_peran')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_peran')
                ->on('siak.peran');
        });
        Schema::table('siak.depart_keg', function (Blueprint $table) {
            $table->foreign('id_depart')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_depart')
                ->on('siak.depart');
            $table->foreign('id_prog')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_prog')
                ->on('siak.prog');
            $table->foreign('id_keg')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_keg')
                ->on('siak.keg');
        });
        Schema::table('siak.verif_keg', function (Blueprint $table) {
            $table->foreign('id_depart_keg')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_depart_keg')
                ->on('siak.depart_keg');
        });
        Schema::table('siak.rab_keg', function (Blueprint $table) {
            $table->foreign('id_depart_keg')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_depart_keg')
                ->on('siak.depart_keg');
        });
        Schema::table('siak.coa_transaction', function (Blueprint $table) {
            $table->foreign('id_coa')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->references('id_coa')
                ->on('siak.coa');
        });
    }

    public function down()
    {
        Schema::table('siak.role_pengguna', function (Blueprint $table) {
            $table->dropForeign(['id_pengguna']);
            $table->dropForeign(['id_peran']);
        });
        Schema::table('siak.depart_keg', function (Blueprint $table) {
            $table->dropForeign(['id_depart']);
            $table->dropForeign(['id_prog']);
            $table->dropForeign(['id_keg']);
        });
        Schema::table('siak.verif_keg', function (Blueprint $table) {
            $table->dropForeign(['id_depart_keg']);
        });
        Schema::table('siak.rab_keg', function (Blueprint $table) {
            $table->dropForeign(['id_depart_keg']);
        });
        Schema::table('siak.coa_transaction', function (Blueprint $table) {
            $table->dropForeign(['id_coa']);
        });
    }
};
