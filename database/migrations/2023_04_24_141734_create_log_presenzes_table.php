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
        Schema::create('log_presenze', function (Blueprint $table) {
            $table->id();
			$table->integer('id_user')->index();
			$table->integer('id_lav')->index();
			$table->integer('id_servizio')->index();
			$table->string('periodo',7)->index();
			$table->date('data');
			$table->double('importo',10,3)->nullable();
			$table->string('note',100)->nullable();
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
        Schema::dropIfExists('log_presenzes');
    }
};
