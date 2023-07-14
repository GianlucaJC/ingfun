<!-- SEZIONE SELEZIONE DITTE / CLIENTI !-->
<div id='div_sez_0' class="sezioni mb-5">
	<div class="card-body">
		<?php
			if (strlen($id_fattura)!=0 && $id_fattura!=0)
				echo "<div class='mb-2'><small>Attenzione! Se si modifica la ditta ed in precedenza sono stati importati servizi tramite associazione appalti, gli articoli inseriti in fattura andranno persi!</small></div>"; 

		?>		
		<div class="row mb-3">
			<div class="col-md-3">
				<div class="form-floating">
					<input class="form-control" id="data_invito" name='data_invito' type="date" required  value="{{$data_invito}}" />
					<label for="data_invito">Data invito a fatturare*</label>
				</div>
			</div>		
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="sezionale" id="sezionale"  required>
					<option value=''>Select...</option>
						@foreach ($sezionali as $sez) 
							<option value='{{$sez->id}}'
							<?php
								if ($sez->id==$sezionale) echo " selected ";
								
							?>
							>{{$sez->descrizione}}</option>
						@endforeach
					</select>
					<label for="sezionale">Sezionale*</label>
				</div>
			</div>	

			<div class="col-md-5">
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
					<label for="ditta">Scelta Cliente da fatturare*</label>

				<a href="{{ route('ditte') }}" class="link-primary" target='_blank' onclick="
						 $('.up').hide();$('#div_up').show();">
					Definisci nuovo o modifica esistente
				</a>	
				
				<span id='div_up' class='up' style='display:none'>
					<a href='javascript:void(0)' class='ml-2' onclick=" $('#step_active').val('');$('#needs-validation2').submit();">
						<font color='green'>
							<i class="fas fa-sync-alt"></i>
						</font>	
					</a>	
				</span>					
				</div>
			</div>			
		</div>				
		<div class="float-sm-right">		
			<button type="submit" name='btn_ditta' id='btn_ditta' onclick="$('.step').val('1')" value='btn_ditta' class="btn btn-success btn-lg">Salva e vai avanti</button>
		</div>
	</div>
</div> 