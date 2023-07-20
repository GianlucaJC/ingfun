<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('modello') }}" id='frm_modello1' name='frm_modello1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Modello</font>
			</h4>
				<div class="row">
					<div class="col-md-4">				
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<label class="input-group-text" for="marche">Marche</label>
						  </div>

						  <select class="custom-select" name="marche" id="marche" required>
							<option value="">Select...</option>
							@foreach($marche as $marca)
								<option value="{{$marca->id}}"
								>{{$marca->marca}}</option>
							@endforeach
						  </select>
						</div>
					</div>
				
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Modello</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Descrizione Modello" aria-label="Descrizione Modello" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Modello</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>