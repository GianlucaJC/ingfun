<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('magazzini') }}" id='frm_magazzini1' name='frm_magazzini1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Magazzino</font>
			</h4>
				<div class="row">


					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control"  id="descr_contr" name="descr_contr" type="text" placeholder="Descrizione magazzino"  required  />
							<label for="descr_contr">Descrizione</label>
						</div>
					</div>					

				
					
					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-control" name="id_sezionale" id="id_sezionale" aria-label="Sezionale" required>
								<option value=''>Select...</option>
								@foreach ($sezionali as $sez) 
									<option value='{{$sez->id}}'
									>{{$sez->descrizione}}
									</option>	
								@endforeach
							</select>
							<label for="sezionale">Sezionale*</label>
						</div>	
					</div>
					
					
					
					<div class="col-md-4">
						<button type="submit" class="btn btn-success btn-lg" >Crea/Modifica Magazzino</button>
						<button type="button" class="btn btn-secondary btn-lg" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>