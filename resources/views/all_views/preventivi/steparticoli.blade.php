<div id='div_sez_1' class="sezioni mb-5" style='display:none'> 
	<div class="card-body">
	@include('all_views.preventivi.metodocomposizione')	

	<?php
		$st1="";
		//if ($filtroa==true) $st1="display:none";
	?>	
	<div id='div_lista_articoli' style='{{$st1}}'>
		<hr>
		<table id='tbl_list_articoli' class="display">
			<thead>
				<tr>
					<th style='max-width:10px'>Ordine</th>
					<th>Codice</th>
					<th>Prodotto</th>
					<th>Quantità</th>
					<th>U.M.</th>
					<th>Prezzo Unitario</th>
					<th>Aliquota</th>
					<th>Subtotale</th>
					<th>Operazioni</th>
				</tr>
			</thead>
			<tbody>
				@php ($last_ordine=0)
				@php ($tot_qta=0)
				@php ($tot_prezzou=0)
				@php ($tot_subt=0)
				@foreach($articoli_preventivo as $articolo)
					@php ($last_ordine++)
					@php ($tot_qta+=$articolo->quantita)
					@php ($tot_prezzou+=$articolo->prezzo_unitario)
					@php ($tot_subt+=$articolo->subtotale)
					<tr>
						<td style='max-width:10px'>{{$articolo->ordine}}</td>
						<td>{{$articolo->codice}}</td>
						<td>{{$articolo->descrizione}}</td>
						<td>{{$articolo->quantita}}</td>
						<td>{{$articolo->um}}</td>
						<td><?php echo number_format($articolo->prezzo_unitario,2)." €"?></td>
						<td>
							@if (isset($arr_aliquota[$articolo->aliquota]))
								{{$arr_aliquota[$articolo->aliquota]}}%
							@endif
						</td>
						<td><?php echo number_format($articolo->subtotale,2)." €"?></td>
						<td>
							<!-- riga info per js !-->
							<span id='inforow{{$articolo->id}}' data-ordine='{{ $articolo->ordine}}' data-codice='{{ $articolo->codice}}' data-descrizione='{{ $articolo->descrizione}}' data-quantita='{{ $articolo->quantita}}' data-um='{{ $articolo->um}}' data-prezzo_unitario='{{ $articolo->prezzo_unitario}}'  data-subtotale='{{ $articolo->subtotale}}' data-aliquota='{{ $articolo->aliquota}}|{{$arr_aliquota[$articolo->aliquota]}}' >
							</span>	
							
							<a href='#' onclick="edit_product({{$articolo->id}},0)">
								<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
							</a>
							<a href='#' onclick="dele_product()">
								<button type="submit" name='dele_ele' class="btn btn-danger" value='{{$articolo->id}}'><i class="fas fa-trash"></i></button>	
							</a>
						</td>
					</tr>
				@endforeach
				@php($last_ordine++)
			</tbody>
		<tfoot>
			<tr>
				<td colspan=3>Totali</td>
				<td>{{$tot_qta}}</td>
				<td></td>
				<td><?php echo number_format($tot_prezzou,2)." €"?></td>
				<td></td>
				<td><?php echo number_format($tot_subt,2)." €"?></td>
				<td></td>
				
			</tr>
		</tfoot>
				
		</table>
	</div>	
	
	<div class='mt-3' id='div_btn_articoli'>
		<hr>
		<button type="button" name='btn_new_row' id='btn_new_row' onclick='edit_product(0,{{$last_ordine}})' class="btn btn-primary btn-lg">Aggiungi Riga</button>
		
		<div class="float-sm-right">
		<!--
			<button type="submit" name='btn_save' id='btn_save' onclick='' class="btn btn-success btn-lg">Salva Fattura</button>
		!-->	
			<button type="button" name='btn_prec' id='btn_prec' onclick="set_step('0')" class="btn btn-secondary btn-lg">Indietro</button>
			
			<button type="submit" name='preview_pdf' id='preview_pdf' class="btn btn-primary btn-lg" value='preview'>Anteprima PDF</button>		
			
			<button type="submit" name='genera_pdf' id='genera_pdf' class="btn btn-primary btn-lg" value='genera'>Genera PDF</button>

			@if ($genera_pdf!="genera")
				<button type="button"  name='send_fatt' id='send_fatt' class="btn btn-outline-success btn-lg" disabled value='invia'>Invia Preventivo</button>
			@else
				<button type="button" onclick="prepare_to_send({{$id_doc}})" name='send_fatt' id='send_fatt' class="btn btn-success btn-lg" value='invia'>Invia Preventivo</button>
			@endif

			
		</div>
		
		
	</div>
	
	</div>
	
</div>