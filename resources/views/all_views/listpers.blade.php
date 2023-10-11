@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style') 
<!-- x button export -->

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
<!-- crea problemi con il footer di fine pagina !-->
<!-- 
	foot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
!-->	
</style>
@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">GESTIONE PERSONALE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Risorse Umane</li>
              <li class="breadcrumb-item active">Gestione Personale</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<form method='post' action="{{ route('listpers') }}" id='frm_listc' name='frm_listc' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<div class="row">
			  <div class="col-lg-12">
					<h5>TOTALE {{$count}} ASSUNTI - {{$all_ris}} RISULTATI</h5>
					
					
					
					<table id='tbl_list_pers' class="display">
						<thead>
							<tr>
								<th>Operazioni/impostazioni</th>
								<th>Dipendente</th>
								<th>Stato</th>
								<th>Inizio</th>
								<th>Fine</th>
								
								<th>Società</th>
								<th>Area Impiego</th>
								<th>Centro Costo</th>
								<th>Appartenenza</th>
								<th>Contratto</th>
								<th>Livello</th>
								<th>Tipo Contratto</th>
								<th>Categoria Legale</th>
								<th>Ore Settimanali</th>
								<th>Codice Qualifica</th>
								<th>Qualificato</th>
								<th>Titolo studio</th>
								<th>C.F.</th>
								<th>Data Nascita</th>
								<th>Comune Nascita</th>
								<th>Prov. Nascita</th>
								<th>Ind. Residenza</th>
								<th>Comune Residenza</th>
								<th>CAP Residenza</th>
								<th>View</th>

								
							</tr>
						</thead>
						<tbody>
				
							@foreach($scadenze as $scadenza)
								<tr>

									<td>
										@if ($scadenza->dele=="0") 
											<a href="{{ route('newcand',['id'=>$scadenza->id,'from'=>1,'setuser'=>1]) }}" target='_blank' >
												<button type="button" class="btn btn-info" alt='Account' title='Account utente'><i class="fas fa-users-cog"></i></button>
											</a>
										@endif
										@if ($scadenza->dele=="0") 
											<a href="{{ route('newcand',['id'=>$scadenza->id,'from'=>1]) }}" >
												<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
											</a>
										@endif

										@if ($scadenza->dele=="0") 
										<a href='#' onclick="dele_element({{$scadenza->id}})">
											<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>
										</a>
										@endif
										@if ($scadenza->dele=="1") 
											<a href='#'onclick="restore_element({{$scadenza->id}})" >
												<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore"></i></button>
											</a>
										@endif									

									</td>									

									<td>
										@if ($scadenza->dele=="1") 
											<font color='red'><del> 
										@endif									
										{{ $scadenza->nominativo }}
										@if ($scadenza->dele=="1") 
											</del></font>
										@endif											
									</td>
									<td>
									@if ($scadenza->status_candidatura=="1") GESTIONE @endif
									@if ($scadenza->status_candidatura=="2") RESPINTA @endif
									@if ($scadenza->status_candidatura=="3") ASSUNZIONE @endif
									@if ($scadenza->status_candidatura=="4") DIMISSIONI @endif
									@if ($scadenza->status_candidatura=="5") LICENZIAMENTO 
									@endif
									@if ($scadenza->status_candidatura=="6") SCADENZA NATURALE
									@endif
									
									</td>
									<td>
									<?php 
										$dx=$scadenza->data_inizio;
										$date="";
										if ($dx!=null) {
											$date=date_create($dx);
											$date=date_format($date,"d/m/Y");
										}	
										
									?>
									
									{{ $date }}</td>
									<td>
									<?php 
										$dx=$scadenza->data_fine;
										$date="";
										if ($dx!=null) {
											$date=date_create($dx);
											$date=date_format($date,"d/m/Y");
										}	
									?>
									{{ $date }}</td>

									<td>
										@if (isset($info_soc[$scadenza->soc_ass])) 
											{{  $info_soc[$scadenza->soc_ass] }}
										@endif
									
									</td>

									<td>
										@if(isset($info_area[$scadenza->area_impiego]))
										{{  $info_area[$scadenza->area_impiego] }}
										@endif									
									</td>	
									<td>
										@if(isset($centri_costo[$scadenza->centro_costo]))
										{{  $centri_costo[$scadenza->centro_costo] }}
										@endif
									</td>
									
									<td>
										@if($scadenza->appartenenza=="1")
											SOCIALE
										@endif									
										@if($scadenza->appartenenza=="2")
											SUB-APPALTO
										@endif									

									</td>
									
									<td>
										@if(isset($ccnl[$scadenza->contratto]))
										{{  $ccnl[$scadenza->contratto] }}
										@endif									
									</td>

									<td>
										{{  $scadenza->livello }}
									</td>									
									<td>
										@if(isset($tipoc[$scadenza->tipo_contr]))
										{{  $tipoc[$scadenza->tipo_contr] }}
										@endif									
									</td>
									<td>
										@if($scadenza->categoria_legale=="0")
											OPERAIO
										@endif									
										@if($scadenza->categoria_legale=="1")
											IMPIEGATO
										@endif									

									</td>		
									<td>
										{{  $scadenza->ore_sett }}
									</td>									
									<td>
										{{  $scadenza->codice_qualifica }}
									</td>									
									<td>
										@if($scadenza->qualificato=="0")
											NO
										@endif									
										@if($scadenza->qualificato=="1")
											SI
										@endif									

									</td>		

									<td>
										@if($scadenza->titolo_studio=="1")
											Licenza Media
										@endif									
										@if($scadenza->titolo_studio=="2")
											Diploma Istituto Superiore
										@endif									
										@if($scadenza->titolo_studio=="3")
											Laurea
										@endif									
										@if($scadenza->titolo_studio=="4")
											Laurea Triennale
										@endif									
										@if($scadenza->titolo_studio=="5")
											Laurea Magistrale
										@endif									

									</td>		
									<td>
										{{  $scadenza->codfisc }}
									</td>									
									<td>
									<?php 
										$dx=$scadenza->datanasc;
										$date="";
										if ($dx!=null) {
											$date=date_create($dx);
											$date=date_format($date,"d/m/Y");
										}	
									?>
									{{ $date }}</td>

									<td>
										<?php
										$comunenasc=$scadenza->comunenasc;
										$ar=explode("|",$comunenasc);
										$istat_nasc="";$comune_nasc="";
										if (isset($ar[0])) $istat_nasc=$ar[0];
										
										if (isset($arr_loc[$istat_nasc])) $comune_nasc=$arr_loc[$istat_nasc];									
										

										?>
										{{ $comune_nasc }}									
									</td>
									<td>
										{{  $scadenza->pro_nasc }}
									</td>
									<td>
										{{  $scadenza->indirizzo }}
									</td>
									<td>
										<?php
										$cap=$scadenza->cap;
										$istat_res="";$comune_res="";
										if (isset($arr_cap[$cap])) $istat_res=$arr_cap[$cap];
										
										if (isset($arr_loc[$istat_res])) $comune_res=$arr_loc[$istat_res];									
										?>
										{{ $comune_res }}
									</td>
									<td>
										{{  $scadenza->cap }}
									</td>


									<td>
										<a href="{{ route('newcand',['id'=>$scadenza->id,'from'=>1]) }}" >
											<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
										</a>
									
									</td>
									
								</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th></th>
								<th>Nominativo</th>
								<th>Stato</th>
								<th>Inizio</th>
								<th>Fine</th>
								<th>Società</th>
								<th>Area Impiego</th>						
								<th>Centro Costo</th>
								<th>Appartenenza</th>
								<th>Contratto</th>
								<th>Livello</th>
								<th>Tipo Contratto</th>
								<th>Categoria Legale</th>
								<th>Ore Settimanali</th>
								<th>Codice Qualifica</th>
								<th>Qualificato</th>
								<th>Titolo studio</th>
								<th>C.F.</th>
								<th>Data Nascita</th>
								<th>Comune Nascita</th>
								<th>Prov. Nascita</th>
								<th>Ind. Residenza</th>
								<th>Comune Residenza</th>
								<th>CAP Residenza</th>
								<th></th>
							</tr>
						</tfoot>					
					</table>
					
			  </div>
			  
			  

			</div>
			<!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<a href="{{ route('newcand',['id'=>0,'from'=>1]) }}" class="nav-link active">
						<button type="button" class="btn btn-primary btn-lg btn-block">Inserisci Nuova Anagrafica</button>
					</a>
					
					<a href="{{ route('export-users') }}" class="nav-link active">
						<button type="button" class="btn btn-primary btn-lg btn-block">Esporta Tutti i dati</button>
					</a>

				</div>
			</div>
			<?php
				$check="";
				if ($view_dele=="1") $check="checked";
			?>
			<div class="row">
				<div class="col-lg-12">
					<div class="form-check form-switch mt-3 ml-3">
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_listc').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche Anagrafiche eliminate</label>
					</div>
				</div>
			</div>	
			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listpers.js?ver=2.01"></script>

@endsection