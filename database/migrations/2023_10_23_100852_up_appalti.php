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
        Schema::table('appalti', function ($table) {
			$table->text('luogo_incontro')->after('descrizione_appalto')->nullable();
			$table->text('orario_incontro')->after('luogo_incontro')->nullable();
			$table->text('chiesa')->after('orario_incontro')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
