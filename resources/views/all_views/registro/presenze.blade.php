@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<!-- x button export -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/daterangepicker/daterangepicker.css">

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
    th, td { white-space: nowrap; }

#tbl_list_presenze tbody th, #tbl_list_presenze tbody td {
    padding: 3px 3px; /* e.g. change 8x to 4px here */
}
#tbl_list_presenze td {
  font-size: 1em;
}

#div_main {
	background-color:white
}	


</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id='div_main'>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">REGISTRO SERVIZI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Risorse Umane</li>
              <li class="breadcrumb-item active">Registro Servizi</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<?php 
	//$periodi=array(); 
?>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new assenze !-->	
		@include('all_views.registro.assenze')
		
		<form method='post' action="{{ route('registro') }}" id='frm_registro' name='frm_registro' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">


			


			<div class="row mb-3">
				<div class="col-md-6">
					<div class="form-floating mb-3 mb-md-0">
						<select class="form-select" name="periodo" id="periodo" onchange="$('#frm_registro').submit()">
							<option value=''>Select...</option>
								<?php
									foreach($periodi as $per=>$descr_p) {?>
									<option value='{{ $per}}' 	
									<?php 
										
										if ($per==$periodo) 
										echo " selected ";
										
									?>	
									>{{ $descr_p}}</option>	
									<?php } ?>
						</select>
						<label for="periodo">Periodo analisi</label>
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-floating mb-3 mb-md-0">

						<select class="form-select" name="zoom_tbl" id="zoom_tbl" onchange="setZoom(this.value)">
							<?php

								for ($sca=0.50;$sca<=1.05;$sca+=0.05) {
									echo "<option value='$sca' ";
									if (strval($sca)==strval($zoom_tbl)) echo " selected ";
									echo ">$sca</option>";
								}
							?>

		
						</select>
						<label for="zoom_tbl">Zoom tabella</label>
					</div>
				</div>	
				<div class="col-md-3">
					<button type="button" onclick="$('#div_definition').show(150)"  class="btn btn-primary btn-lg" >Definisci periodo assenza</button>
				</div>			
			</div>
			
	
        <div class="row">
          <div class="col-md-12" id='div_tb' style='display:none'>
			<table id='tbl_list_presenze' class="display cell-border" width="100%">
				<thead>
					<tr>
						
						
						<th>Nominativi</th>
						<th>
							Descrizione
						</th>

						<?php
							for ($sca=1;$sca<=$giorni;$sca++) {
								echo "<th>";
									echo "$sca-$mese";
								echo "</th>";
							}
						?>
						<th>Totali</th>
					</tr>
				</thead>
				<tbody>
					@php ($old_lav="?")
					@php ($old_index=-1)
					
					@foreach ($lav_all as $lavoratore)
						@php ($somma_lav=0)
						<?php
						
							if (!isset($lav_lista[$lavoratore->id_lav])) 	continue;
						?>
						
						@foreach($servizi as $index=>$servizio)
						
							<?php
								
								$js="";
								$js.="ins_value.periodo='$periodo';";
								$js.="ins_value.giorni=$giorni;";
								$js.="ins_value.mese='$mese';";
								$js.="ins_value.mese_num='$mese_num';";
								$js.="ins_value.id_lav=".$lavoratore->id_lav.";";
								$js.="ins_value.id_servizio='".$servizio['id']."';";
								$js.="ins_value.tipo_dato='".$servizio['tipo_dato']."';";
								
									?>						
						
							
							
							<?php
							
								$view_main=view_main($giorni,$lav_lista,$lavoratore,$servizio,$js);	
							
								$js.="ins_value(0)";
								
								if (($view_main['presenza'])==false) continue;

								
							?>
							

							
							<tr>
								<?php
									if ($old_lav!=$lavoratore->id_lav)
										$st="style='color:rgba(100, 100, 100,1 )'";
									else
										$st="style='color:rgba(100, 100, 100,0.1 )'";
								echo "<td $st>";
								?>	@if(isset($lavoratori_mov[$lavoratore->id_lav])) 
										<b>{{$lavoratore->nominativo}}</b>
									@else
										<i>{{$lavoratore->nominativo}}</i>
									@endif
										
								
								</td>								
								<td>

									<a href='#' class="link-primary" onclick="{{$js}}">
										{{$servizio['descrizione']}}
									</a>


								</td>
								
								<?php
									echo $view_main['view'];
									$somma_lav+=$view_main['somma'];
								?>
								@if ($servizio['descrizione']=="NOTE")
									<td>
										@if ($somma_lav>0) 
										<center>	
											<font color='green'>
												<b>{{$somma_lav}}</b>
											</font>	
										</center>	
										@endif
									</td>
								@else
									<td></td>
								@endif

							</tr>
							@php($old_lav=$lavoratore->id_lav)
							@php ($old_index=$index)
						@endforeach

	
					@endforeach
					
				</tbody>
				<tfoot>
					<tr>
						
						<th>Nominativo</th>
						
						<th>Descrizione</th>
						<?php
							for ($sca=1;$sca<=$giorni;$sca++) {
								echo "<th>";
									echo " ";
								echo "</th>";
							}
						?>	
						<th>Totali</th>		

					</tr>
				</tfoot>					
			</table>
				<input type='text' id='c_page' name='c_page' value='{{$c_page}}'>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>


		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
	
	
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="modalvalue" tabindex="-1" role="dialog" aria-labelledby="title_doc" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_doc">Inserimento dati</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id='bodyvalue'>
        ...
      </div>
	  <div id='div_wait' class='mb-3'></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id='btn_close' onclick=''>Chiudi</button>
        <div id='div_save'></div>
      </div>
	  
    </div>
  </div>
</div>	
	
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
<?php

function view_main($giorni,$lav_lista,$lavoratore,$servizio,$js) {
	
	$view="";$somma=0;$presenza=false;
	$id_servizio=$servizio['id'];

	for ($sca=1;$sca<=$giorni;$sca++) {
		$value="";
		$view.="<td>";
			
			
			

			if (isset($lav_lista[$lavoratore->id_lav]['service'])) {
				$obj=$lav_lista[$lavoratore->id_lav]['service'];			
				
				for ($ser=0;$ser<=count($obj)-1;$ser++) {
					if ($obj[$ser]->id_service==$id_servizio) {
						$d_ref=$obj[$ser]->data_ref;
						if (strlen($d_ref)>8) {
							if (intval(substr($d_ref,8,2))==$sca)
								$value=$servizio['importo'];
								if (strlen($value)>0) $presenza=true;
						}
					}
				}
				
			}
			
			
			//eventuale override
			if (isset($lav_lista[$lavoratore->id_lav]['presenze'])) {
				$obj=$lav_lista[$lavoratore->id_lav]['presenze'];
				for ($ser=0;$ser<=count($obj)-1;$ser++) {
					if ($obj[$ser]->id_servizio==$id_servizio) {
						$d_ref=$obj[$ser]->data;
						if (strlen($d_ref)>8) {
							if (intval(substr($d_ref,8,2))==$sca) {
								if ($servizio['tipo_dato']==0)
									$value=$obj[$ser]->importo;
								else
									$value=$obj[$ser]->note;
							}	

							if ($value==0 || strlen($value)>0) 	
							$presenza=true;
						}
						
					}
				}
			}	
			$js1=$js."ins_value($sca);";
			$id_ref=$lavoratore->id_lav."_".$id_servizio."_".$sca;
			$view.="<a href='javascript:void(0)' onclick=\"$js1\">";
				$view.="<div style='text-align:center;min-height:20px'>";
					$view.="<span id='imp_$id_ref' class='dati_presenze'>";
						//senza formattazione, altrimenti in casi di dati 
						//assenti ma con formattazione inserisce l'informazione nel db
						if ($value==0) 
							if (isset($servizio['acronimo'])) $view.=$servizio['acronimo'];
							elseif (isset($servizio['alias_ref'])) $view.=$servizio['alias_ref'];
							else {
								if ($value!="-1") $view.=$value;
							}	
						else if ($value!="-1") $view.=$value;
					$view.="</span>";
				$view.="</div>";
			$view.="</a>";
			
			//esempio di debug
			/*
			if ($lavoratore->id_lav==7) {
				echo "giorno $sca - lavoratore_id: ".$lavoratore->id_lav." - lavoratore: ".$lavoratore->nominativo." servizio ".$servizio['id']." somma $somma<hr>";
			}
			*/
			if ($value>0)
				$somma+=intval($value);
			
			
		$view.="</td>";
	}

	$info['view']=$view;
	$info['somma']=$somma;
	if ($servizio['pre_load']=="S") $presenza=true;
	$info['presenza']=$presenza;
	return $info;
}
?> 
 
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>

	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>



		<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.2.2/js/dataTables.fixedColumns.min.js"></script>
		

	<!-- fine DataTables !-->
	
	<script src="{{ URL::asset('/') }}plugins/moment/moment.min.js"></script>
	<script src="{{ URL::asset('/') }}plugins/daterangepicker/daterangepicker.js"></script>	

	<script src="{{ URL::asset('/') }}dist/js/registro.js?ver=1.543"></script>

@endsection