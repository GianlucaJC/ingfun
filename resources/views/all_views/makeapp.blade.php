<?php
	use App\Models\appalti;
	use App\Models\lavoratoriapp;
	use App\Models\servizi;
	use App\Models\serviziapp;
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')


<meta name="csrf-token" content="{{{ csrf_token() }}}">


@section('extra_style') 
	<link rel="manifest" href="{{ asset('/manifest.json') }}">
	<script>
	</script>	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('/') }}plugins/fullcalendar/main.css">
@endsection
<style>
.box {
    
}

th, td {
   padding-right: 18px;
}
</style>

@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid mt-4">
	  <!--<div class='onesignal-customlink-container'></div>!-->

		<form method='post' action="{{ route('makeappalti') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
            <input type='hidden' name='id_giorno_appalto' id='id_giorno_appalto' value='{{$id_giorno_appalto}}'>
            <?php
                $strm="";$strp="";
                foreach ($info_box as $ib) {
                    $me=$ib->m_e;
                    if ($me=="M") {
                        if (strlen($strm)!=0) $strm.=";";
                        $strm.=$ib->id_box;
                    }
                    if ($me=="P") {
                        if (strlen($strp)!=0) $strp.=";";
                        $strp.=$ib->id_box;
                    }
                }

                $strall="";
                $b=array();
                foreach ($appaltibox as $appbox) {
                    $m_e=$appbox->m_e;
                    $id_box=$appbox->id_box;
                    $b[$m_e]=$id_box;
                    $id_lav=$appbox->id_lav;
                    $rowbox=$appbox->rowbox;
                    if (strlen($strall)!=0) $strall.="|";
                    $strall.=$m_e.";".$id_box.";".$id_lav.";".$rowbox;
                }
                $maxM=0;$maxP=0;
                if (isset($b['M'])) $maxM=$b['M']+1;
                if (isset($b['P'])) $maxP=$b['P']+1;
            ?>    

            <input type='hidden' name='strm' id='strm' value='{{$strm}}'> 
            <input type='hidden' name='strp' id='strp' value='{{$strp}}'> 
            <input type='hidden' name='strall' id='strall' value='{{$strall}}'> 
            <input type='hidden' name='maxM' id='maxM' value='{{$maxM}}'> 
            <input type='hidden' name='maxP' id='maxP' value='{{$maxP}}'> 

				<section class="content">
				<div class="container-fluid">
					<div class="row">
                    
					<div class="col-md-2">
                    
                        <div class="card-body">
                            
                            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">Ditte</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">Persone</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">mezzi</a>
                                </li>

                            </ul>
                            <div class="tab-content" id="custom-content-below-tabContent">
                            <div class="tab-pane fade show active" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
                                    <!--testo ditte !-->
                                    <input type='text' class='form-control mt-2' id='cerca_ditta' placeholder='Cerca Ditta'>
                                    <div id="div_dit" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                        <div class="d-grid gap-1" style="padding:10px">
                                            @foreach ($ditte as $ditta)
                                                <button type="button" class="btn btn-outline-success allditte" data-nome='{{$ditta->denominazione}}' id='btndit{{$ditta->id}}' data-iddit='{{$ditta->id}}' onclick='impegnadit({{$ditta->id}})'>              
                                                {{$ditta->denominazione}}
                                                </button>
                                            @endforeach	
                                        </div>
                                    </div>                                    
                            </div>

                       
                            <div class="tab-pane fade" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
                                    <!--testo persone!-->
                                    <input type='text' class='form-control mt-2' id='cerca_nome' placeholder='Cerca Nominativo'>
                                    <div id="div_lav" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                        <div class="d-grid gap-1" style="padding:10px">
                                            @foreach ($lavoratori as $lavoratore)
                                                <button type="button" class="btn btn-outline-success allnomi" data-nome='{{$lavoratore->nominativo}}' id='btnlav{{$lavoratore->id}}' data-idlav='{{$lavoratore->id}}' onclick='impegnalav({{$lavoratore->id}})'>              
                                                {{$lavoratore->nominativo}}
                                                </button>
                                                <div style='display:none' id='unlock{{$lavoratore->id}}'>
                                                    <a href='#' onclick="unlock({{$lavoratore->id}})">
                                                        <i class="fa-solid fa-unlock"></i>
                                                    </a>   
                                                    <hr>
                                                </div>
                                            @endforeach	
                                        </div>
                                    </div>
                            </div>
                            <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
                                <!--testo mezzi!-->
                                    <input type='text' class='form-control mt-2' id='cerca_mezzo' placeholder='Cerca Mezzo'>
                                    <div id="div_lav" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                        <div class="d-grid gap-1" style="padding:10px">
                                            @foreach($inventario as $flotta)
                                                <?php
                                                    $mezzo=$flotta->targa;
                                                    if (isset($marche[$flotta->marca]))
                                                        $mezzo.=" - ".$marche[$flotta->marca];
                                                    if (isset($modelli[$flotta->modello]))
                                                        $mezzo.=" - ".$modelli[$flotta->modello];
                                                ?>        
                                                <button type="button" class="btn btn-outline-success allmezzi" data-nome='{{$mezzo}}' id='btnlav{{$flotta->id}}' data-idmezzo='{{$flotta->id}}' onclick='impegnamezzo({{$flotta->id}})'>              
                                                {{$mezzo}}
                                                </button>
                                            @endforeach	
                                        </div>
                                    </div>                                
                                
                            </div>
                    
                            </div>
                        </div>

					</div>
					<!-- /.col -->
					<div class="col-md-10" style='overflow-x:scroll;max-height:800px'>
                        <a href='#' onclick="newapp('M','man')">Aggiungi appalto mattutino</a>						
						<table id='tbAppM' class='table'>	
                            <tbody>
							<tr>
                                    <!--colonne popolate dinamicamente!-->
							</tr>
                            </tbody>
						</table>

                        <a href='#' onclick="newapp('P','man')">Aggiungi appalto pomeridiano</a>						
						<table id='tbAppP' class='table'>	
                            <tbody>
                                <tr>
                                    <!--colonne popolate dinamicamente!-->
                                </tr>
                            </tbody>
						</table>                        
					</div>
					<!-- /.col -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
				</section>            

		</form>
      </div><!-- /.container-fluid -->
    </div>

    <!-- MODAL !-->


    <div class="modal fade bd-example-modal-lg" id="modalinfo" tabindex="-1" role="dialog" aria-labelledby="Info appalto" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalinfotitle">Definizione appalto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <center><div id='div_wait' class='mt-2'></div></center>
            <div class="modal-body" id='body_content'>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>
                
                
            </div>
            </div>
        </div>
    </div>    
    <!-- Fine MODAL !-->
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- jQuery UI -->
	<script src="{{ URL::asset('/') }}plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>


	<!-- fullCalendar 2.2.5 -->
	<script src="{{ URL::asset('/') }}plugins/moment/moment.min.js"></script>
	<script src="{{ URL::asset('/') }}plugins/fullcalendar/main.js"></script>



	<script src="{{ URL::asset('/') }}dist/js/makeapp.js?ver=<?php echo time(); ?>"></script>


	

@endsection