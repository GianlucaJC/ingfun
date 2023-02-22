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
		Schema::table('users', function ($table) {
			$table->text('push_id')->nullable()->default(null);
			$table->text('key_p')->nullable()->default(null);
			$table->text('auth')->nullable()->default(null);
			$table->text('msg_push')->nullable()->default(null);
			$table->text('lnk_push')->nullable()->default(null);
			$table->string('t_browser',50)->nullable()->default(null);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
