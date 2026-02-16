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
        Schema::table('ditte', function (Blueprint $table) {
            $table->string('codice_conto')->nullable()->after('sdi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ditte', function (Blueprint $table) {
            $table->dropColumn('codice_conto');
        });
    }
};
