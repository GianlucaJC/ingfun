<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('sottocategorie_prodotti') }}" id='frm_categorie1' name='frm_categorie1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>

				<div class="row">
					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-control" name="id_categoria" id="id_categoria" aria-label="Categoria" required>
								<option value=''>Select...</option>
								@foreach ($categorie as $categoria) 
									<option value='{{$categoria->id}}'
									>{{$categoria->descrizione}}
									</option>	
								@endforeach
							</select>
							<label for="categoria">Categoria*</label>
						</div>	
					</div>				
					<div class="col-md-4">
						<div class="input-group h-100 mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Descrizione</span>
						  </div>
						  <input type="text" class="form-control h-100" placeholder="Descrizione Sotto Categoria" aria-label="Descrizione sotto categoria" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-success h-100" >Crea/Modifica Sotto Categoria</button>
						<button type="button" class="btn btn-secondary h-100" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>