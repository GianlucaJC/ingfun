<div id='div_definition' style='display:none'>
	<form method='post' action="{{ route('utenti') }}" id='frm_utenti1' name='frm_utenti1' autocomplete="off">	
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='edit_elem' id='edit_elem'>
		<input type="hidden" value="{{url('/')}}" id="url" name="url">
		<div class="container-fluid">

				<div class="row mb-3">
					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1">Nominativo</span>
						  </div>
						  <input type="text" class="form-control" placeholder="Nominativo" id='nome' name='nome' required>
						</div>			
					</div>

					<div class="col-md-6">
						<div class="input-group mb-3">
						  <div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon3">@Username</span>
						  </div>
						  <input type="email" class="form-control" placeholder="E-mail" id="email" name='email' maxlength=150 required>
						</div>			
					</div>
					
				</div>
				
				<div class="row mb-3">
					
				  <div class="col-md-6">
					  <div class="form-group">
						<label for="pw_first">Password Iniziale</label>
						<input type="password" class="form-control" id="pw_first" name="pw_first" placeholder="Password">
					  </div>
				  </div>
				  <div class="col-md-6">
					  <div class="form-group">
						<label for="pw_ripeti">Ripeti Password</label>
						<input type="password" class="form-control" id="pw_ripeti" name="pw_ripeti" placeholder="Password" >
					  </div>					
				  </div>					

				</div>
				
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">

							<select class="form-control" name="ruolo" id="ruolo" aria-label="Ruolo" required>
								<option value=''>Select...</option>
								@foreach ($roles as $role) 
									<option value="{{$role->name}}"
									
									>{{$role->name}}</option>		
									
								@endforeach
							</select>

							
							<label for="ruolo">Ruolo</label>
						</div>	
					</div>	
				</div>
				

				
				<div class="row mb-3 mt-5">
					<div class="col-md-4">
						<button type="submit" class="btn btn-success" >Crea/Modifica Utente</button>
						<button type="button" class="btn btn-secondary" onclick="$('#div_definition').toggle(150)">
						Chiudi
						</button>
						
					</div>	
				</div>

							
				
			<hr>	
		</div>	
	</form>		
</div>