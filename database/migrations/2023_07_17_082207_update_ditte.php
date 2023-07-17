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
        Schema::table('ditte', function ($table) {
			$table->integer('pf_pi')->after('id');
			$table->string('cognome',50)->after('denominazione')->nullable();
			$table->string('nome',50)->after('cognome')->nullable();
			
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
