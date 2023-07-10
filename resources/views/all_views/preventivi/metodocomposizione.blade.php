<div>
	<h5 >Modalità di composizione fattura:</h5>
</div>
<div class="margin">

  <div class="btn-group">
	<button type="button" class="btn btn-info">Aggiungi righe preventivo tramite:</button>
	<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
	  <span class="sr-only">Toggle Dropdown</span>
	</button>
	<div class="dropdown-menu" role="menu">
	  <a class="dropdown-item" href="#" onclick="metodo_ins(1)">a) Modalità di inserimento manuale</a>

	  <a class="dropdown-item" href="javascript:void(0)"  onclick="metodo_ins(3)" >b) Importazione da Servizi</a>

	  <a class="dropdown-item" href="javascript:void(0)" >c) Importazione da prodotti Magazzino</a>	  
	  <!--
	  <a class="dropdown-item" href="#" onclick="metodo_ins(3)">c) Servizi associati alla ditta selezionata</a>
	  !-->
	  <div class="dropdown-divider"></div>
	  <a class="dropdown-item" href="#" onclick="metodo_ins(4)"><i>Torna alla visualizzazione degli articoli</i></a>

	</div>
  </div>		
</div>	



<div id='div_from_servizi' class='metodi mt-3' style='display:none'>
	<div class="row mb-3">
		<div class="col-md-12">
			<div class="form-floating mb-3 mb-md-0">
				<select class="form-select select2" style='height:auto' name="servizi[]" id="servizi" required multiple>
				<?php
					/*
					if (strlen($id_app)!=0 && $id_app!=0) {
						
						foreach ($servizi as $servizio) {
							$id_servizio=$servizio->id_servizio;
							$descr_servizio=$servizio->descrizione;
							echo "<option value='".$id_servizio."' ";
							if (in_array($id_servizio,$id_servizi)) echo " selected ";
							echo ">".$descr_servizio."</option>";
						}
					}
					*/
				?>						

				</select>
				
			</div>
			<label for="servizi">Servizi*</label>
		</div>	
	</div>	
</div>	





