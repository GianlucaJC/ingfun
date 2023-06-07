<!-- SEZIONE SELEZIONE DITTE / CLIENTI !-->
<div id='div_sez_0' class="sezioni mb-5">
	<div class="card-body">
		<?php
			if (strlen($id_fattura)!=0 && $id_fattura!=0)
				echo "<div class='mb-2'><small>Attenzione! Se si modifica la ditta ed in precedenza sono stati importati servizi tramite associazione appalti, gli articoli inseriti in fattura andranno persi!</small></div>"; 

		?>		
		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_invito" name='data_invito' type="date" required  value="{{$data_invito}}" />
					<label for="data_invito">Data invito a fatturare*</label>
				</div>
			</div>		
			<div class="col-md-8">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="ditta" id="ditta"  required onchange='popola_servizi(this.value)'>
					<option value=''>Select...</option>
					<?php
						$old_az="?";
						foreach ($ditte as $ditta_ref) {
							$id_azienda=$ditta_ref->id_azienda;
							$azienda=$ditta_ref->azienda;
							if ($old_az!=$id_azienda) {
								$old_az=$id_azienda;
								if ($old_az!="?")echo "</optgroup>";
								echo "<optgroup label='$azienda'>";
							}	
							$azienda=$ditta_ref->azienda;
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
			<button type="submit" name='btn_ditta' id='btn_ditta' onclick="$('.step').val('1')" value='btn_ditta' class="btn btn-success btn-lg">Salva e vai avanti</button>
		</div>
	</div>
</div> 