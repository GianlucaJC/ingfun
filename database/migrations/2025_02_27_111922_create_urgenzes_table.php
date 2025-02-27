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
        Schema::create('urgenze', function (Blueprint $table) {
            $table->id();
			$table->tinyInteger('dele');
			$table->integer('id_user')->index();
            $table->text('descrizione')->nullable();
			$table->integer('status');
            $table->datetime('dataora');
            $table->integer('id_ditta')->index();
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
        Schema::dropIfExists('urgenze');
    }
};
