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
        Schema::create('prod_giacenze', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_prodotto')->index();
			$table->integer('id_magazzino')->index();
			$table->integer('giacenza');
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
        Schema::dropIfExists('prod_giacenze');
    }
};
