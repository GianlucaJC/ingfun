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
        Schema::create('set_global', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->text('email_parco')->nullable();
			$table->text('email_acquisti')->nullable();
        });	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('set_global');
    }
};
