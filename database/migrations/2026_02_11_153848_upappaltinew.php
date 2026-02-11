<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('appaltinew', function (Blueprint $table) {
            $table->integer('num_box')->default(20)->after('data_esportazione');
            $table->integer('elem_box')->default(6)->after('num_box');
            $table->integer('elem_rep')->default(15)->after('elem_box');
            $table->integer('elem_ass')->default(15)->after('elem_rep');
        });
    }

    public function down()
    {
        Schema::table('appaltinew', function (Blueprint $table) {
            $table->dropColumn(['num_box', 'elem_box', 'elem_rep', 'elem_ass']);
        });
    }
}

?>