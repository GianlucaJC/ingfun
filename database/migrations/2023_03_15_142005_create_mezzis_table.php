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
        Schema::create('mezzi', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->string('tipologia',40)->nullable();
			$table->string('marca',60)->nullable();
			$table->string('modello',50)->nullable();
			$table->string('targa',10)->nullable()->index();
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
        Schema::dropIfExists('mezzi');
    }
};
