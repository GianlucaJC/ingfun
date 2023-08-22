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
        Schema::table('societa', function ($table) {
			$table->string('mail_pec',150)->nullable()->after('mail_fatture');
			$table->string('mail_azienda',150)->after('mail_pec')->nullable();
			$table->string('telefono',40)->after('mail_azienda')->nullable();
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
