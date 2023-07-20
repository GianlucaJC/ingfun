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
        Schema::create('parco_modello_mezzo', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->integer('id_marca')->index();
			$table->string('modello',100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parco_modello_mezzos');
    }
};
