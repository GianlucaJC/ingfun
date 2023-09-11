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
        Schema::create('prod_spostamenti', function (Blueprint $table) {
            $table->id();
			$table->integer('id_prodotto');
			$table->integer('id_magazzino_orig');
			$table->integer('id_magazzino_dest');
			$table->integer('qta_spostata');
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
        Schema::dropIfExists('prod_spostamenti');
    }
};
