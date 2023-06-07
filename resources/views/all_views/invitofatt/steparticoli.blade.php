<div id='div_sez_2' class="sezioni mb-5" style='display:none'> 
	<div class="card-body">
	@include('all_views.invitofatt.metodocomposizione')	

	<div id='div_lista_articoli'>
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
					<th>Subtotale</th>
					<th>Aliquota</th>
					<th>Operazioni</th>
				</tr>
			</thead>
			<tbody>
				@php ($last_ordine=0)
				@foreach($articoli_fattura as $articolo)
					@php ($last_ordine++)
					<tr>
						<td style='max-width:10px'>{{$articolo->ordine}}</td>
						<td>{{$articolo->codice}}</td>
						<td>{{$articolo->descrizione}}</td>
						<td>{{$articolo->quantita}}</td>
						<td>{{$articolo->um}}</td>
						<td><?php echo number_format($articolo->prezzo_unitario,2)." €"?></td>
						<td><?php echo number_format($articolo->subtotale,2)." €"?></td>
						<td>
							@if (isset($arr_aliquota[$articolo->aliquota]))
								{{$arr_aliquota[$articolo->aliquota]}}%
							@endif
						</td>
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
				
		</table>
	</div>	
	
	<div class='mt-3' id='div_btn_articoli'>
		<hr>
		<button type="button" name='btn_new_row' id='btn_new_row' onclick='edit_product(0,{{$last_ordine}})' class="btn btn-primary btn-lg">Aggiungi Riga</button>
		
		<div class="float-sm-right">
		<!--
			<button type="submit" name='btn_save' id='btn_save' onclick='' class="btn btn-success btn-lg">Salva Fattura</button>
		!-->	
			
			
			<button type="button" name='btn_prec' id='btn_prec' onclick="set_step('1')" class="btn btn-secondary btn-lg">Indietro</button>
		</div>
		
		
	</div>
	
	</div>
	
</div>