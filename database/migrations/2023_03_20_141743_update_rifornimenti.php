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
		Schema::table('rifornimenti', function ($table) {
			$table->integer('id_appalto')->after('id_user')->index();
			$table->string('filename',30)->after('id_appalto')->nullable();
			$table->double('importo',10,3)->after('filename')->nullable();
			$table->integer('km')->after('importo')->nullable();
			$table->text('note')->after('km')->nullable();
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
