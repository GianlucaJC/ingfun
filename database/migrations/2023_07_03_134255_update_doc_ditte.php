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
        Schema::create('ref_doc_ditte', function (Blueprint $table) {
            $table->id();	
			$table->integer('dele');
			$table->integer('id_ditta');
			$table->string('nomefile',100);
			$table->string('descr_file',50);
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
