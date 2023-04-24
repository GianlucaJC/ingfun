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
        Schema::create('presenze', function (Blueprint $table) {
            $table->id();
			$table->integer('id_lav')->index();
			$table->integer('id_servizio')->index();
			$table->string('periodo',7)->index();
			$table->date('data');
			$table->double('importo',10,3);
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
        Schema::dropIfExists('presenzes');
    }
};
