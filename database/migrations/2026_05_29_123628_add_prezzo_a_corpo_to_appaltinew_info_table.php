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
            $table->decimal('prezzo_a_corpo', 10, 2)->nullable()->after('note_fatturazione');
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
            $table->dropColumn('prezzo_a_corpo');
        });
    }
};
