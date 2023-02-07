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
        Schema::create('appalti', function (Blueprint $table) {
            $table->id();
			$table->integer('dele');
			$table->integer('id_ditta')->index();
			$table->string('descrizione_appalto',150);
			$table->text('note')->nullable();
			$table->date('data_ref');
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
        Schema::dropIfExists('appalti');
    }
};
