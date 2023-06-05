<!-- SEZIONE DOCUMENTI !-->
<div id='div_sez_1' class="sezioni mb-5">
	<div class="card-body">
		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_invito" name='data_invito' type="date" required />
					<label for="data_invito">Data invito a fatturare*</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating">
					<input class="form-control" id="data_pagamento" name='data_pagamento' type="date" required />
					<label for="data_pagamento">Data pagamento*</label>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">				
					<select class="form-select" id="sezionale" aria-label="Sezionale" name='sezionale' required>
						<option value=''>Select...</option>
						@foreach ($lista_sezionali as $info_sezionale) 
							<option value='{{$info_sezionale->id}}'>
								{{$info_sezionale->descrizione}}
							</option>	
						@endforeach
					</select>
					<label for="sezionale">Sezionale</label>
				</div>

			</div>

		</div>	

		<div class="row mb-3">
			<div class="col-md-4">
				<div class="form-floating mb-3 mb-md-0">				
					<select class="form-select" id="tipo_pagamento" aria-label="Pagamento" name='tipo_pagamento' required>
						<option value=''>Select...</option>
						<?php
						for ($sca=0;$sca<=count($lista_pagamenti)-1;$sca++) {?>
							<option value="{{$lista_pagamenti[$sca]['id']}}">
								{{$lista_pagamenti[$sca]['descrizione']}}
							</option>	
						<?php } ?>
					</select>
					<label for="tipo_pagamento">Pagamento</label>
				</div>
			</div>
			<div class="col-md-4">

			</div>
			<div class="col-md-4">

			</div>

		</div>

		
		<div class="float-sm-right">		
			<button type="submit" name='btn_save' id='btn_save' onclick="$('.step').val('2')" class="btn btn-success btn-lg">Avanti</button>

			<button type="button" name='btn_prec' id='btn_prec' onclick="set_step('0')" class="btn btn-secondary btn-lg">Indietro</button>
			
		</div>
	</div>
</div> 