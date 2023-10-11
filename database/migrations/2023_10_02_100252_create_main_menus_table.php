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
        Schema::create('main_menu', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
			$table->text('voce');
			$table->integer('parent_id')->index();
			$table->text('route');
			$table->text('permissions');
			$table->text('roles');
			$table->text('class_divrow');
			$table->text('class_btn_action');
			$table->text('class_icon');
			$table->text('style_icon');
			$table->integer('visible')->default('1');
			$table->string('disable',30);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('main_menu');
    }
};
