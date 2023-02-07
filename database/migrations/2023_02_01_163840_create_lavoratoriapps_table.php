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
        Schema::create('lavoratoriapp', function (Blueprint $table) {
            $table->id();
			$table->integer('id_appalto')->index();
			$table->integer('id_ditta_ref')->index();
			$table->integer('id_lav_ref')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lavoratoriapp');
    }
};
