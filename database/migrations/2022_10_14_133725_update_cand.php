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
		Schema::table('candidatis', function ($table) {
			$table->integer('contratto')->after('costo_azienda')->nullable();
			$table->string('livello',40)->after('contratto')->nullable();
			$table->integer('tipo_contr')->after('livello')->nullable();
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
