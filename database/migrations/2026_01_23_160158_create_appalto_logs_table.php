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
        Schema::create('appalto_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('appalto_id'); // Corresponds to id_giorno_appalto
            $table->string('m_e', 1)->nullable(); // 'M' or 'P' for single box saves
            $table->integer('box_id')->nullable(); // Specific box number for single box saves
            $table->string('action_type'); // e.g., 'single_box_save', 'save_all'
            $table->text('payload')->nullable(); // Store relevant data as JSON
            $table->timestamps();

            $table->foreign('appalto_id')->references('id')->on('appaltinew')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appalto_logs');
    }
};
