<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('lavoratori') }}" id='frm_lav1' name='frm_lav1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type='hidden' name='old_ditta' id='old_ditta'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="container-fluid">

				<div class="row mb-3">
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Cognome</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Cognome" aria-label="Cognome" name="cognome" id="cognome" required>
						</div>			
					</div>

					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon2">Nome</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Nome" aria-label="Nome" name="nome" id="nome" required>
						</div>			
					</div>
					
				</div>


				


				<div class="row mb-3 mt-5">
					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Lavoratore</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>	
				</div>

							
				
			<hr>	
		</div>	
	</form>		
</div>