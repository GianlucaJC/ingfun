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
        Schema::create('rimborsi', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('id_user')->index();
            $table->integer('id_rimborso')->index();
			$table->dateTime('dataora')->nullable();
            $table->double('importo',10,2)->nullable();
            $table->string('filename',20)->nullable();
            $table->integer('stato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rimborsis');
    }
};
