<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="modal_prev" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Importazione da preventivo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='body_modal_prev'>
        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_preventivi' class="display">
					<thead>
						<tr>
							<th style='max-width:30px;text-align:center'>Sel</th>
							<th style='max-width:30px;text-align:center'>ID</th>
							<th style='max-width:120px;'>Data</th>
							<th>Sezionale</th>
							<th>Cliente</th>
							<th>Stato</th>
							<th>Totale</th>
							<th style='width:200px'>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($preventivi as $preventivo)
							<tr>
								<td style='max-width:30px;text-align:center'>
									<div class="form-check">
									  <input class="form-check-input" type="radio" name="prev_sel[]" id="prev_sel{{$preventivo->id}}" value="{{$preventivo->id}}">
									  <label class="form-check-label" for="prev_sel{{$preventivo->id}}">
										
									  </label>
									</div>							
								</td>	
								<td style='max-width:30px;text-align:center'>{{$preventivo->id}}</td>
								<td style='max-width:120px;'>
									{{$preventivo->data_preventivo}}
								</td>
								<td>
									{{$preventivo->sezionale}}
								</td>
								
								
								<td>
									 @if ($preventivo->dele=="1") 
										<font color='red'><del> 
									 @endif
										{{$preventivo->denominazione}}
										
									 @if ($preventivo->dele=="1") 
										 </del></font>
									 @endif									
								</td>
								<td>
									@if ($preventivo->status==2)
										<i>Bozza</i>
									@elseif($preventivo->status==3)
										<i>Elaborato</i>
									@elseif($preventivo->status==4)
										<i>Accettato</i>
									@elseif($preventivo->status==5)
										<i>Fatturato</i>
									@endif	
								</td>
								<td>
									<?php
										echo number_format($preventivo->totale,2)." €";
									?>
								</td>
								<td style='width:200px'>
									@if (1==2)
										<a href="{{ route('preventivo',['id'=>$preventivo->id]) }}">
											<button type="button" class="btn btn-info" alt='Edit' title='Modifica preventivo'><i class="fas fa-edit"></i></button>
										</a>
									@endif
									@if ($preventivo->status>0)
									<a href="../allegati/preventivi/{{$preventivo->id}}.pdf?ver=<?php echo time();?>" target='_blank'>
										<button type="button" class="btn btn-secondary" alt='Pdf' title='apri file pdf'><i class="fas fa-file-pdf" ></i></button>
									</a>
									
									
									
									@if ($preventivo->status>=2 && 1==2)
											<a href='javascript:void(0)'  onclick='change_state({{$preventivo->id}})'>
											<button type="button" class="btn btn-warning" alt='Status' title='Cambio stato'><i class="fas fa-cog"></i></button>
											</a>
										@endif

									
									@endif
									
									@if (1==2)
										<a href='#' onclick="dele_element({{$preventivo->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger" title='Elimina preventivo'><i class="fas fa-trash"></i></button>	
										</a>
									@endif
								

									
									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th style='max-width:30px;'></th>
							<th style='max-width:30px;'>ID</th>
							<th style='max-width:120px;'>Data</th>
							<th>Sezionale</th>
							<th>Cliente</th>
							<th>Stato</th>
							<th></th>
							<th style='width:200px'></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>

      </div>
      <div class="modal-footer">
		<div id='altri_btn'></div>
        <button type="submit" name='import_prev' value="importa" class="btn btn-success">Importa preventivo selezionato</button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
      </div>
    </div>
  </div>
</div> 