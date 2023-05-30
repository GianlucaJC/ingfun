<!-- SEZIONE SELEZIONE DITTE / CLIENTI !-->
<div id='div_sez_ditte' class="sezioni mb-5">
	<div class="card-body">
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="ditta" id="ditta"  required onchange='popola_servizi(this.value)'>
					<option value=''>Select...</option>
					<?php
						foreach ($ditte as $ditta_ref) {
							$id_ditta=$ditta_ref->id;
							$denominazione=$ditta_ref->denominazione;
							echo "<option value='".$id_ditta."' ";
							if ($id_ditta==$ditta) echo " selected ";
							echo ">".$denominazione."</option>";
						}
					?>						
					</select>
					<label for="ditta">Scelta Cliente/Ditta da fatturare*</label>
				</div>
			</div>			
		</div>				
		<div class="float-sm-right">		
			<button type="submit" name='btn_save' id='btn_save' onclick='' class="btn btn-success btn-lg">Avanti</button>
		</div>
	</div>
</div> 