<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modal_story" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

<!--
<div class="col-md-2">
	<div class="form-floating">
		<input class="form-control" id="ordine" name='ordine' type="text" placeholder="Ordinamento"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
		<label for="ordine">Ordine</label>
	</div>
</div>
!-->


      <div class="modal-body" id='body_modal'>
		<div class="row mb-3">
			<div class="col-md-12"> 
				<div class="row mb-3">

					<input type='hidden' name='id_riga' id='id_riga'>
					<div class="col-md-4">
						<div class="form-floating">
							<select class="form-control" name="id_fornitore" id="id_fornitore" aria-label="Fornitore" required>
							<option value=''>Select...</option>
								@foreach ($fornitori as $fornitore)
									<option value='{{$fornitore->id}}'
									>{{$fornitore->ragione_sociale}}</option>
								@endforeach
							</select>
							<label for="id_fornitore">FORNITORE*</label>
							
							<a href="{{ route('elenco_fornitori') }}" class="link-primary" target='_blank' onclick="		 $('.up').hide();$('#div_up_forn').show()">
								Definisci/modifica
							</a>					
							<span id='div_up_forn' class='up' style='display:none'>
								<a href='javascript:void(0)' class='ml-2' onclick='refresh_forn()'>
									<font color='green'>
										<i class="fas fa-sync-alt"></i>
									</font>	
								</a>	
							</span>						
						</div>
					</div>					
					
					<div class="col-md-4">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select select2" id="codice" aria-label="codice prodotto" name='codice' onchange="calcolo_riga()" required>
								<option value=''>Select...</option>
								@foreach ($prodotti as $prodotto)
									<option value='{{$prodotto->id}}'
									>{{$prodotto->descrizione}}</option>
								@endforeach
							</select>					
							
						<span><b>Prodotto*</b></span>
						
						
						<small><a href="{{ route('elenco_articoli') }}" class="link-primary mt-2 ml-2" target='_blank' onclick="$('.up').hide();$('#div_up_art').show()">
							Definizione
						</a></small>
						<span id='div_up_art' class='up' style='display:none'>
							<a href='javascript:void(0)' class='ml-2' onclick='refresh_prodotti()'>
								<font color='green'>
									<i class="fas fa-sync-alt" title='refresh'></i>
								</font>	
							</a>	
						</span>							
						</div>

						
					</div>
				
							
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control" id="prezzo_unitario" name='prezzo_unitario' type="text" placeholder="Prezzo unitario" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');;calcolo_riga()" required />
							<label for="prezzo_unitario">Prezzo Unitario*</label>
						</div>		
					</div>	

				</div>	
			</div>
			<div class="col-md-12"> 
				<div class="row mb-3">
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control" id="quantita" name='quantita' type="text" placeholder="Quantità" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');;calcolo_riga()" required />
							<label for="quantita">Quantità*</label>
						</div>		
					</div>
				
					<div class="col-md-4">
					  <div class="form-floating mb-3 mb-md-0">
						
						<select class="form-select" id="aliquota" aria-label="Stato Occupazione" name='aliquota' onchange="calcolo_riga()" required>
							<option value=''>Select...</option>
							@foreach ($aliquote_iva as $aliquota) 
								<option value='{{$aliquota->id}}|{{$aliquota->aliquota}}'>
									{{$aliquota->aliquota}}% - {{$aliquota->descrizione}}
								</option>	
							@endforeach
						</select>
						
						<label for="aliquota">Aliquota Iva</label>
						
						<small><a href="{{ route('aliquote') }}" class="link-primary mt-2" target='_blank' onclick="$('.up').hide();$('#div_up3').show()">
							Definisci o modifica
						</a></small>
						<span id='div_up3' class='up' style='display:none'>
							<a href='javascript:void(0)' class='ml-2' onclick='refresh_aliquota()'>
								<font color='green'>
									<i class="fas fa-sync-alt" title='refresh'></i>
								</font>	
							</a>	
						</span>							
						</div>
					</div>	
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control" id="subtotale" name='subtotale' type="text" placeholder="Subtotale" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" required />
							<label for="subtotale" >Subtotale</label>
						</div>		
					</div>
				</div>
			</div>
			
		</div>			
      </div>
      <div class="modal-footer">
		<div id='altri_btn'></div>
        <button type="submit" id='btn_save_art' name='btn_save_art' class="btn btn-success" value="save">Salva</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div> 