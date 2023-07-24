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
        Schema::create('parco_scheda_mezzo', function (Blueprint $table) {
            $table->id();
			$table->integer("dele");
            $table->timestamps();
			$table->string('targa',30);
			$table->string('numero_interno',50);
			$table->integer("tipologia");
			$table->integer("marca");
			$table->integer("modello");
			$table->string("telaio",100)->nullable();
			$table->integer("alimentazione");
			$table->integer("proprieta");
			$table->string("posti",50)->nullable();
			$table->string("chilometraggio",50)->nullable();
			$table->integer("catene");
			$table->integer("carta_carburante")->nullable();
			$table->integer("badge_cisterna")->nullable();
			$table->integer("telepass")->nullable();
			$table->date("data_immatricolazione");
			$table->date("ultima_revisione")->nullable();
			$table->date("scadenza_assicurazione")->nullable();
			$table->date("scadenza_bollo")->nullable();
			$table->string("prossimo_tagliando",50)->nullable();
			$table->string("marca_modello_pneumatico",80)->nullable();
			$table->string("misura_pneumatico",50)->nullable();
			$table->integer("primo_equipaggiamento")->nullable();
			$table->string("km_installazione",30)->nullable();
			$table->string("officina_installazione",80)->nullable();
			$table->text("anomalia_note")->nullable();
			$table->integer("mezzo_marciante")->nullable();
			$table->integer("mezzo_manutenzione")->nullable();
        });
	}
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parco_scheda_mezzo');
    }
};
