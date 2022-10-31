<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('contatti') }}" id='frm_contatto1' name='frm_contatto1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>

		<div class="container-fluid">
			<hr>
			<h4>
				<font color='red'>Definizione Contatto</font>
			</h4>
				<div class="row">
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Contatto</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Contatto" aria-label="Contatto" name="descr_contr" id="descr_contr" required>
						</div>			
					</div>
					
					<div class="col-md-4">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">@</span>
						  </div>
						  <input type="text" class="form-control" placeholder="email" aria-label="Email" name="email" id="email" required>
						</div>			
					</div>

					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Contatto</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>
					
				</div>
			<hr>	
		</div>	
	</form>		
</div>