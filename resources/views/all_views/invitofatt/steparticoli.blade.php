<div id='div_sez_articoli' class="sezioni mb-5" style='display:none'> 
	<div class="card-body">
	@include('all_views.invitofatt.metodocomposizione')	

	<div id='div_lista_articoli'>
		<hr>
		<table id='tbl_list_articoli' class="display">
			<thead>
				<tr>
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
				@foreach($articoli_fattura as $articolo)
					<tr>
						<td>{{$articolo->codice}}</td>
						<td>{{$articolo->descrizione}}</td>
						<td>{{$articolo->quantita}}</td>
						<td>{{$articolo->um}}</td>
						<td>{{$articolo->prezzo_unitario}}€</td>
						<td>{{$articolo->subtotale}}€</td>
						<td>
							@if (isset($arr_aliquota[$articolo->aliquota]))
								{{$arr_aliquota[$articolo->aliquota]}}%
							@endif
						</td>
						<td>
							<!-- riga info per js !-->
							<span id='inforow{{$articolo->id}}' data-codice='{{ $articolo->codice}}' data-descrizione='{{ $articolo->descrizione}}' data-quantita='{{ $articolo->quantita}}' data-um='{{ $articolo->um}}' data-prezzo_unitario='{{ $articolo->prezzo_unitario}}'  data-subtotale='{{ $articolo->subtotale}}' data-aliquota='{{ $articolo->aliquota}}' >
							</span>	
							
							<a href='#' onclick="edit_product({{$articolo->id}})">
								<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
							</a>
							<a href='#' onclick="dele_product()">
								<button type="submit" name='dele_ele' class="btn btn-danger" value='{{$articolo->id}}'><i class="fas fa-trash"></i></button>	
							</a>
						</td>
					</tr>
				@endforeach
			</tbody>
				
		</table>
	</div>	
	
	<div class='mt-3' id='div_btn_articoli'>
		<hr>
		<div class="float-sm-right">
			<button type="submit" name='btn_save' id='btn_save' onclick='' class="btn btn-success btn-lg">Avanti</button>
			
			
			<button type="button" name='btn_prec' id='btn_prec' onclick="set_step('ditte')" class="btn btn-secondary btn-lg">Indietro</button>
		</div>
		
		
	</div>
	
	</div>
	
</div>