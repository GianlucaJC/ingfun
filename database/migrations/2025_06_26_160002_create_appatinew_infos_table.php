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
        Schema::create('appaltinew_info', function (Blueprint $table) {
            $table->id();
			$table->integer('id_appalto')->index();
            $table->string('m_e',1);
            $table->tinyInteger('id_box');
            $table->string('luogo_incontro', 200)->nullable();
            $table->string('orario_incontro', 10)->nullable();
            $table->string('luogo_destinazione', 200)->nullable();
            $table->string('ora_destinazione', 10)->nullable();
            $table->date('data_servizio')->nullable();
            $table->string('numero_persone', 4)->nullable();
            $table->string('servizi_svolti', 50)->nullable();
            $table->string('nome_salma', 70)->nullable();
            $table->text('note')->nullable();
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
        Schema::dropIfExists('appaltinew_info');
    }
};
