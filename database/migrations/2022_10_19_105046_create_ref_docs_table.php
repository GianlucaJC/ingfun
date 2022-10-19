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
        Schema::create('ref_doc', function (Blueprint $table) {
            $table->id();	
			$table->integer('dele');
			$table->integer('id_cand');
			$table->integer('id_tipo_doc');
			$table->integer('id_sotto_tipo')->nullable();
			$table->date('scadenza')->nullable();
			$table->string('nomefile',100);
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
        Schema::dropIfExists('ref_doc');
    }
};
