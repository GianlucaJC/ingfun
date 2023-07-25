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
        Schema::create('parco_servizi_noleggio', function (Blueprint $table) {
            $table->id();
			$table->integer("dele");
            $table->timestamps();
			$table->string('descrizione',100);
        });		    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parco_servizi_noleggio');
    }
};
