@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
<!-- x button export -->

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


</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">REGISTRO PRESENZE</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item">Registro</li>
              <li class="breadcrumb-item active">Presenze</li>
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
		<!-- form new ditte !-->	

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
			</div>
	
        <div class="row">
          <div class="col-md-12">
			<table id='tbl_list_presenze' class="display">
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
					
					
					@foreach ($lav_all as $lavoratore)
						@php ($somma_lav=0)
						<?php
							if (!isset($lav_lista[$lavoratore->id_lav])) 	continue;
						?>
						@php ($inc=0)	
						@foreach($servizi as $servizio)
							<?php
								$view_main=view_main($giorni,$lav_lista,$lavoratore,$servizio);	
								if (($view_main['presenza'])==false) continue;
							?>
							@php($inc++)
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
									<?php
										$js="";
										$js.="ins_value.periodo='$periodo';";
										$js.="ins_value.giorni=$giorni;";
										$js.="ins_value.mese='$mese';";
										$js.="ins_value.mese_num='$mese_num';";
										$js.="ins_value.id_lav=".$lavoratore->id_lav.";";
										$js.="ins_value.id_servizio='".$servizio['id']."';";
										$js.="ins_value.tipo_dato='".$servizio['tipo_dato']."';";
										$js.="ins_value()";
									?>
									<a href='#' class="link-primary" onclick="{{$js}}">
										{{$servizio['descrizione']}}
									</a>
								</td>
								
								<?php
									echo $view_main['view'];
									$somma_lav+=$view_main['somma'];
								?>	
								@if ($inc==count($servizi))
									<td>
										@if ($somma_lav>0) 
											<font color='green'>
												<b>{{$somma_lav}}</b>
											</font>	
										@endif
									</td>
								@else
									<td></td>
								@endif

							</tr>
							@php($old_lav=$lavoratore->id_lav)
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

function view_main($giorni,$lav_lista,$lavoratore,$servizio) {
	
	$view="";$somma=0;$presenza=false;
	for ($sca=1;$sca<=$giorni;$sca++) {
		$value="";
		$view.="<td>";
			
			$id_servizio=$servizio['id'];
			

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
							if (intval(substr($d_ref,8,2))==$sca)
								if ($servizio['tipo_dato']==0)
									$value=$obj[$ser]->importo;
								else
									$value=$obj[$ser]->note;

								if (strlen($value)>0) $presenza=true;
						}
					}
				}
			}	
			$id_ref=$lavoratore->id_lav."_".$id_servizio."_".$sca;
			$view.="<span id='imp_$id_ref' class='dati_presenze'>";
				//senza formattazione, altrimenti in casi di dati 
				//assenti ma con formattazione inserisce l'informazione nel db
				$view.=$value;
			$view.="</span>";
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
	
	

	<script src="{{ URL::asset('/') }}dist/js/registro.js?ver=1.445"></script>

@endsection