<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('societa_assunzione') }}" id='frm_societa' name='frm_societa' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Società</font>
			</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Descrizione</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Descrizione Contratto" aria-label="Descrizione" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					<div class="col-md-6">
						<button type="submit" class="btn btn-success" >Crea/Modifica Società</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>