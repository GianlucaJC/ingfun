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
        Schema::create('ditte', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->string('denominazione',150);
			$table->string('cap',40)->nullable();
			$table->string('comune',150)->nullable();
			$table->string('provincia',10)->nullable();
			$table->string('piva',15)->nullable();
			$table->string('cf',18)->nullable();
			$table->string('email',150)->nullable();
			$table->string('pec',150)->nullable();
			$table->string('telefono',40)->nullable();
			$table->string('fax',50)->nullable();
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
        Schema::dropIfExists('ditte');
    }
};
