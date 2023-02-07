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
        Schema::create('lavoratori', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_ditta')->index();
			$table->string('cognome',40);
			$table->string('nome',60);
			$table->string('nominativo',120);
			$table->string('indirizzo',150)->nullable();
			$table->string('cap',40)->nullable();
			$table->string('comune',150)->nullable();
			$table->string('provincia',10)->nullable();
			$table->string('codfisc',16)->nullable()->index();
			$table->date('datanasc')->nullable();
			$table->string('email',150)->nullable();
			$table->string('telefono',20)->nullable();
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
        Schema::dropIfExists('lavoratori');
    }
};
