<?php
	use App\Models\appalti;
	use App\Models\lavoratoriapp;
	use App\Models\servizi;
	use App\Models\serviziapp;
?>
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
            <h1 class="m-0">LISTA APPALTI</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Amministrazione</li>
			  <li class="breadcrumb-item active">Appalti</li>

              <li class="breadcrumb-item active">Lista Appalti</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		<div class="row">
			<?php
			
				if (!empty($num_send) && $num_send>0) {
					$txt="Notifiche push inviate!";
					if ($num_send==1) $txt="Notifica push inviata!";
					echo "<div class='alert alert-success' role='alert'>";
					  echo "<b>$num_send</b> $txt";
					echo "</div>";
				}
				if (!empty($num_send_mail) && $num_send_mail>0) {
					$txt="Mail inviate!";
					if ($num_send_mail==1) $txt="Mail inviata!";
					echo "<div class='alert alert-success' role='alert'>";
					  echo "<b>$num_send_mail</b> $txt";
					echo "</div>";
				}				
			?>
		</div>
	  
		<form method='post' action="{{ route('listapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	  
			<?php if (1==2) {?>
			<div class="container">
            <div class="row">
                <h1>Laravel: Whatsapp Twilio Notifications Example</h1>

                <div class="col-md-12 mt-5">
                    
                        @csrf

                        <div class="mb-3">
                            <label for="message" class="form-label">Your Message</label>
                            <textarea name="message" class="form-control" id="message" rows="3" required="required"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">To Phone Number</label>
                            <input type="text" name="to" class="form-control" id="phone" placeholder="+919876543210" required="required">
                        </div>

                        <div>
                            <button type="submit" name='send_wa' value='send' class="btn btn-primary">Send</button>
                        </div>
                    
                </div>
            </div>
        </div>
		<?php } ?>

			
			<div class="row">
			  <div class="col-lg-12">
				<h5>TOTALE  APPALTI</h5>
				
				
				
				<table id='tbl_list_pers' class="display">
					<thead>
						<tr>
							<th style='min-width:190px'>Operazioni</th>
							<th style='max-width:60px'>Stato</th>
							<th style='max-width:40px'>ID</th>
							<th>Azienda di proprietà</th>
							<th>Ditta</th>
							<th>Lavoratori coinvolti</th>
							<th>Servizi</th>
							<th>Mezzo</th>
							<th>Data e ora del servizio</th>
						</tr>
					</thead>
					<tbody>
			
					@foreach($gestione as $gest)
						<tr>

							<td style='min-width:220px'>
								
								<a href="#" onclick="if (!confirm('Sicuri di duplicare?')) event.preventDefault()">
									<button type="submit" class="btn btn-primary" name='clona' value="{{$gest->id}}" alt='Duplica' title="Duplica Appalto"><i class="fas fa-clone"></i></button>
								</a>							
								@if ($gest->dele=="0") 
									<a href="{{ route('newapp',['id'=>$gest->id,'from'=>1,'num_send'=>0]) }}" >
										<button type="button" class="btn btn-info" alt='Edit' title="Modifica Appalto"><i class="fas fa-edit"></i></button>
									</a>
								@endif

								@if ($gest->dele=="0") 
								<a href='#' onclick="dele_element({{$gest->id}})">
									<button type="submit" name='dele_ele' class="btn btn-danger" title="Cancella Appalto"><i class="fas fa-trash"></i></button>
								</a>
								@endif
								@if ($gest->dele=="1") 
									<a href='#'onclick="restore_element({{$gest->id}})" >
										<button type="submit" class="btn btn-warning" alt='Restore'><i class="fas fa-trash-restore" title="Ripristina"></i></button>
									</a>
								@endif	
								
								<a href='#' onclick="push_appalti({{$gest->id}})" >
									<button type="submit" class="btn btn-warning" alt='Sollecito'><i class="fas fa-share-square" title="Invia Sollecito Push solo a chi non ha risposto"></i></button>
								</a>
								

								<a href="{{ URL::asset('/') }}rifornimenti/{{$gest->id}}">
									<button type="button" class="btn btn-secondary" alt='Rifornimenti'><i class="fas fa-euro-sign" title="Visualizza tutti i rifornimenti dell'appalto"></i></button>
								</a>

								
							</td>	
							<td style='max-width:60px'>
								@if ($gest->status==0)
									<i>Aperto</i>
								@endif
								@if ($gest->status==1)
									<i>Fatturato</i>
								@endif
								
							</td>

							<td  style='max-width:40px'>
								@if ($gest->dele=="1") 
									<font color='red'><del> 
								@endif									
								{{ $gest->id }}
								@if ($gest->dele=="1") 
									</del></font>
								@endif											
							</td>
							
							<td>
								<?php
									if (isset($azienda_proprieta[$gest->id_azienda_proprieta])) {
										echo $azienda_proprieta[$gest->id_azienda_proprieta];
									}
								?>
							</td>

							<td>
								{{ $gest->denominazione }}
							</td>
							<td>
								<?php
									$id_appalto=$gest->id;

									$lavoratoriapp=lavoratoriapp::select('c.nominativo','lavoratoriapp.status')
									->join('candidatis as c', 'lavoratoriapp.id_lav_ref','=','c.id')
									->where('lavoratoriapp.id_appalto','=',$id_appalto)
									->get();
									
								?>
								@foreach($lavoratoriapp as $lavoratori)
									<?php 
									$colo="yellow";
									if ($lavoratori->status==0) {
										$back="yellow";$colo="black";
									}
									if ($lavoratori->status==1) {
										$back="green";$colo="white";
									}
									if ($lavoratori->status==2) {
										$back="red";$colo="white";
									}
									?>
									
									<div style='inline;background-color:{{$back}};color:{{$colo}}'>
										{{ $lavoratori->nominativo}}
									</div>
									
								@endforeach
							</td>
							<td>
								<?php
									
									$serviziapp=servizi::select('descrizione')
									->join('serviziapp as s', 'servizi.id','=','s.id_servizio')
									->where('s.id_appalto','=',$id_appalto)
									->get();
									
								$n_s=0;
								?>
								@foreach($serviziapp as $servizio)
									@if ($n_s!=0), @endif
									{{ $servizio->descrizione}}
									<?php $n_s++; ?>
								@endforeach

							
							</td>
							<td>
								<?php
								
									if (isset($targhe[$gest->targa]))
										echo $targhe[$gest->targa];
								?>
							</td>
							
							<td>
							<?php 
								$dx=$gest->data_ref;
								$date="";
								if ($dx!=null) {
									$date=date_create($dx);
									$date=date_format($date,"d/m/Y");
								}	
								
								$date.=" ".$gest->orario_ref;
							?>
							
								{{ $date }}
							</td>
						</tr>
					@endforeach
					</tbody>
					<tfoot>
						<tr>
							<th style='min-width:190px'></th>
							<th style='max-width:60px'>Stato</th>
							<th style='max-width:40px'>ID</th>
							<th>Azienda di proprietà</th>
							<th>Ditta</th>
							<th>Lavoratori coinvolti</th>
							<th>Servizi</th>
							<th>Mezzo</th>
							<th>Data-ora</th>
							
						</tr>
					</tfoot>					
				</table>
					
			  </div>
			  
			  

			</div>
			<!-- /.row -->

			<div class="row">
				<div class="col-lg-12">
					<a href="{{ route('newapp',['id'=>0,'from'=>1,'num_send'=>0]) }}" class="nav-link active">
						<button type="button" class="btn btn-primary btn-lg btn-block">Definisci Nuovo Appalto</button>
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
					  <input class="form-check-input" type="checkbox" id="view_dele" name="view_dele" onchange="$('#frm_appalti').submit()" {{ $check }}>
					  <label class="form-check-label" for="view_dele">Mostra anche Appalti eliminati</label>
					</div>
				</div>
			</div>	
			<input type='hidden' id='dele_cand' name='dele_cand'>
			<input type='hidden' id='restore_cand' name='restore_cand'>
			<input type='hidden' id='push_appalti' name='push_appalti'>
		</form>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>

	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->


	<script src="{{ URL::asset('/') }}dist/js/listapp.js?ver=1.09"></script>

@endsection