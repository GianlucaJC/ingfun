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
        Schema::create('parco_telepass', function (Blueprint $table) {
            $table->id();
			$table->integer("dele");
            $table->timestamps();
			$table->string('id_telepass',100);
        });		    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parco_telepasses');
    }
};
