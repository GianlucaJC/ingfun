<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('mansione') }}" id='frm_mans1' name='frm_mans1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Mansione</font>
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
						<button type="submit" class="btn btn-success" >Crea/Modifica Mansione</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>