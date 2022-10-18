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
			$table->integer('appartenenza')->nullable();
			$table->string('subappalto',150)->nullable();
			$table->string('affiancamento',150)->nullable();
			$table->date('data_inizio')->nullable();
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
