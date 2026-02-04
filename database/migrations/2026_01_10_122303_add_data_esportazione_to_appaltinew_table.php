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
        Schema::table('appaltinew', function (Blueprint $table) {
            $table->timestamp('data_esportazione')->nullable()->after('dele');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appaltinew', function (Blueprint $table) {
            $table->dropColumn('data_esportazione');
        });
    }
};

