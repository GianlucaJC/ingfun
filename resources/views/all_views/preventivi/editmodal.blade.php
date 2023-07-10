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
      <div class="modal-body" id='body_modal'>
			<div class="row mb-3">
				<div class="col-md-6">
					<div class="row mb-3">
						<div class="col-md-2">
							<div class="form-floating">
								<input class="form-control" id="ordine" name='ordine' type="text" placeholder="Ordinamento"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
								<label for="ordine">Ordine</label>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="codice" name='codice' type="text" placeholder="Codice"   />
								<label for="codice">Codice</label>
							</div>
						</div>
						
						
						
						<div class="col-md-5 tipoins" id='div_service'>
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="service" aria-label="Servizi" name='service' onchange='set_service(this.value)'>
							
								<option value=''>Select...</option>
								@foreach ($all_servizi as $servizio) 
									<?php
									$aliquota_v="0";
									if (isset($arr_aliquota[$servizio->aliquota]))
										$aliquota_v=$arr_aliquota[$servizio->aliquota];
									?>
								
									<option value="	{{$servizio->id_servizio}}|{{$servizio->descrizione}}|{{$servizio->importo_ditta}}|{{$servizio->aliquota}}|{{$aliquota_v}}">
										{{$servizio->descrizione}}
									</option>	
								@endforeach
							</select>
							
							<label for="service">Servizi associati alla ditta*</label>
							
							<small><a href="{{ route('servizi') }}" class="link-primary mt-2" target='_blank' onclick="$('.up').hide();$('#div_up4').show()">
								Definisci o modifica
							</a></small>
							<span id='div_up4' class='up' style='display:none'>
								<a href='javascript:void(0)' class='ml-2' onclick='refresh_servizi()'>
									<font color='green'>
										<i class="fas fa-sync-alt" title='refresh'></i>
									</font>	
								</a>	
							</span>							
							</div>
						</div>						
						
						<div class="col-md-5 tipoins" id='div_product'>
							<div class="form-floating">
								<input class="form-control" id="prodotto" name='prodotto' type="text" placeholder="Prodotto" required />
								<label for="prodotto">Prodotto</label>
							</div>		
						</div>
						
						
						<div class="col-md-2">
							<div class="form-floating">
								<input class="form-control" id="quantita" name='quantita' type="text" placeholder="Q.tà" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');calcolo_riga()" required />
								<label for="quantita">Quantità</label>
							</div>		
						</div>
					</div>
					
				</div>
				

				<div class="col-md-6">
					<div class="row mb-3">
						<div class="col-md-2">
							<div class="form-floating">
								<input class="form-control" id="um" name='um' type="text" placeholder="UM"  />
								<label for="um">U.M.</label>
							</div>		
						</div>							
						<div class="col-md-4">
							<div class="form-floating">
								<input class="form-control" id="prezzo_unitario" name='prezzo_unitario' type="text" placeholder="Prezzo unitario" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');;calcolo_riga()" required />
								<label for="prezzo_unitario">Prezzo Unitario</label>
							</div>		
						</div>	
						<div class="col-md-3">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="aliquota" aria-label="Aliquota" name='aliquota' onchange="calcolo_riga()" required>
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
						<div class="col-md-3">
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
        <button type="submit" class="btn btn-success" onclick='save_art()'>Salva</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div> 