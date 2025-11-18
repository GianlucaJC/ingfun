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
        Schema::table('appaltinew_info', function (Blueprint $table) {
            $table->text('note_fatturazione')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appaltinew_info', function (Blueprint $table) {
            $table->dropColumn('note_fatturazione');
        });
    }
};
