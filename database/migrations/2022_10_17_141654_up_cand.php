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
			$table->string('tipo_init_anagr',10)->after('updated_at')->nullable();
			$table->string('tipo_anagr',10)->after('tipo_init_anagr')->nullable()->index();
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
