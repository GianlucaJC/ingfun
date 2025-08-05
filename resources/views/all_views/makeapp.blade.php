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
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

	<link rel="manifest" href="{{ asset('/manifest.json') }}">
	<script>
	</script>	
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
	
@endsection
<style>
.itemlist {
    padding:0.4rem !important;
}
.panel-footer {
  display: flex;
  justify-content: space-between;
}
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
    <div class="content" id='div_tbx'>
    
      <div class="container-fluid mt-4">
	  <!--<div class='onesignal-customlink-container'></div>!-->

		<form method='post' action="{{ route('makeapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
            <input type='hidden' name='id_giorno_appalto' id='id_giorno_appalto' value='{{$id_giorno_appalto}}'>
            
                <?php
                $all_servizi="";
                foreach($servizi as $servizio) {
                    if (strlen($all_servizi)>0) $all_servizi.="|";
                    $all_servizi.=$servizio->id.";".$servizio->descrizione;
                }
                echo "<input type='hidden' name='all_servizi' id='all_servizi' value='$all_servizi'>";

                $strm="";$strp="";
                foreach ($info_box as $ib) {
                    $me=$ib->m_e;
                    $id_box=$ib->id_box;
                    if ($me=="M") {
                        if (strlen($strm)!=0) $strm.=";";
                        $strm.=$id_box;
                    }
                    if ($me=="P") {
                        if (strlen($strp)!=0) $strp.=";";
                        $strp.=$id_box;                     
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
                    $responsabile_targa=$appbox->responsabile_targa;
                    if (strlen($strall)!=0) $strall.="|";
                    $strall.=$m_e.";".$id_box.";".$id_lav.";".$rowbox.";".$responsabile_targa;
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

					<div class="row" style='width:1400px'>
                    
					<div class="col-md-2">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-success btn-sm mb-2" id="btn_save_all" onclick="save_all()"><i class="fa-solid fa-floppy-disk"></i> Salva Tutto</button>
                        </div>

                        <label for="zoomlevel" class="form-label">Zoom level</label>
                        <input type="range" class="form-range" min="0.10" max="1.05" step="0.02" id="zoomlevel"  onchange="setZoom(this.value,1)">


                        <div class="card-body">

                            <div class="accordion" id="accordionFlushExample">
                                <!--accordion ditte !-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Ditte
                                    </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" >
                                        <div class="accordion-body">
                                            <!--testo ditte !-->
                                            <input type='text' class='form-control mt-2' id='cerca_ditta' placeholder='Cerca Ditta' style='width:110px'>
                                            <div id="div_dit" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                                <div class="d-grid gap-1">
                                                    <?php $alld=""; ?>
                                                    @foreach ($ditte as $ditta)
                                                        <?php
                                                            if (strlen($alld)!=0) $alld.="|";
                                                            $alld.=$ditta->id.";".$ditta->denominazione;
                                                        ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm allditte" data-nome='{{$ditta->denominazione}}' id='btndit{{$ditta->id}}' data-iddit='{{$ditta->id}}' 
                                                        draggable="true" ondragstart="dragstartHandlerDitta(event)" style='width:110px'>              
                                                        {{$ditta->denominazione}}
                                                        </button>
                                                    @endforeach	
                                                    <input type='hidden' name='alld' id='alld' value='{{$alld}}'>
                                                </div>
                                            </div>          
                                        </div>
                                    </div>
                                </div>
                                <!-- fine accordion ditte !-->

                                <!--accordion mezzi !-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                        Mezzi
                                    </button>
                                    </h2>
                                    <div id="flush-collapseThree" class="accordion-collapse collapse" >
                                        <div class="accordion-body">
                                            <!--testo mezzi!-->
                                                <input type='text' class='form-control mt-2' id='cerca_mezzo' placeholder='Cerca Mezzo' style='width:110px'>
                                                <div id="div_lav" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                                    <div class="d-grid gap-1">
                                                        <?php $infomezzi=""; ?>
                                                        @foreach($inventario as $flotta)
                                                            <?php
                                                                $mezzo=$flotta->targa;
                                                                $targa=$flotta->targa;
                                                                $marca="";
                                                                if (isset($marche[$flotta->marca])) {
                                                                    $marca=$marche[$flotta->marca];
                                                                    $mezzo.=" - ".$marca;
                                                                }
                                                                $modello="";
                                                                if (isset($modelli[$flotta->modello])) {
                                                                    $modello=$modelli[$flotta->modello];
                                                                    $mezzo.=" - ".$modello;
                                                                }
                                                            ?>        
                                                            <button type="button" class="btn btn-outline-success btn-sm allmezzi" data-nome='{{$mezzo}}' id='btnmezzo{{$flotta->id}}' data-idmezzo='{{$flotta->id}}' 
                                                            data-targa='{{$flotta->targa}}' data-mezzo='{{$mezzo}}'
                                                            draggable="true" ondragstart="dragstartHandlerMezzi(event)" style='width:110px'>              
                                                            {{$mezzo}}
                                                            </button>
                                                            <?php
                                                                if (strlen($infomezzi)!=0) $infomezzi.=";";

                                                                $infomezzi.="$targa-$marca-$modello";
                                                            ?>
                                                        @endforeach	
                                                                                                                                            

                                                    </div>
                                                </div>       
                                        </div>
                                    </div>
                                </div>
                                <input type='hidden' name='infomezzi' id='infomezzi' value='{{$infomezzi}}'>
                                <!--fine accordion mezzi !-->

                                <!--accordion persone !-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo" >
                                        Persone
                                    </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse show" >
                                        <div class="accordion-body">
                                            <!--testo persone!-->
                                            <input type='text' class='form-control mt-2' id='cerca_nome' placeholder='Cerca nome' style='width:110px'>
                                            <div id="div_lav" class='mt-2' style='max-height:800px;overflow-y:scroll'>
                                                <div class="d-grid gap-1">
                                                    @foreach ($lavoratori as $lavoratore)
                                                        <button type="button" class="btn btn-outline-success  btn-sm allnomi" data-nome='{{$lavoratore->nominativo}}' id='btnlav{{$lavoratore->id}}' data-idlav='{{$lavoratore->id}}' onclick='impegnalav({{$lavoratore->id}})' draggable="true" ondragstart="dragstartHandler(event)" style='width:110px'>  
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
                                    </div>
                                </div>
                                <!-- fine accordion persone !-->

                            </div>
                        </div>

					</div>
					<!-- /.col -->
                    
					<div class="col-md-10">
                        <div id='div_tb' style='border:2px ;width:2500px' >
                            <?php
                                $dap=date("Y-m-d");$dap1=$dap;
                                if (isset($info_app[0]->data_appalto)) {
                                    $dap=$info_app[0]->data_appalto;
                                    $dap1=substr($dap,8,2)."-".substr($dap,5,2)."-".substr($dap,0,4);
                                    echo "Appalti del <b>".$dap1."</b>";
                                }
                            ?>

                            <input type='hidden' id='dap' value='{{$dap}}'>
                            <input type='hidden' id='dap1' value='{{$dap1}}'>
                            
                            <div style='overflow-x:hidden;white-space: nowrap;'>
                                <span style="float:right">
                                    <a href='#' onclick="newapp('M','man');$('.collapse').collapse('hide')">
                                    Aggiungi appalto mattutino</a>						
                                </span>
                            
                            
                                <table id='tbAppM' class='table'>	
                                    <tbody>
                                    <tr>
                                            <!--colonne popolate dinamicamente!-->
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                               
                            <div style='overflow-x:hidden;white-space: nowrap;'>
                                <span style="float:right">
                                    <a href='#' onclick="newapp('P','man');$('.collapse').collapse('hide')" >Aggiungi appalto pomeridiano</a>
                                </span>
                                
                                <table id='tbAppP' class='table'>	
                                    <tbody>
                                        <tr>
                                            <!--colonne popolate dinamicamente!-->
                                        </tr>
                                    </tbody>
                                </table>  
                            </div>

                            <div class="container-fluid">
                                <h2>Urgenze</h2>
                                <a href='javascript:void(0)' onclick='urgenze()'>
                                    <h3><i class="fas fa-calendar-plus"></i> Nuova urgenza</h3>
                                </a>    
                            </div>                               
					    </div>
                     
                    </div>    
					<!-- /.col -->
				</div>
				<!-- /.row -->
				</div><!-- /.container-fluid -->
				</section>            

		</form>
      </div><!-- /.container-fluid -->
    </div>

    @section('operazioni')
    @endsection
    <!-- MODAL !-->


    <div class="modal fade bd-example-modal-lg" id="modalinfo" role="dialog" aria-labelledby="Info appalto" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalinfotitle">Informazioni sull'appalto</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"  onclick="$('#body_content').html('');">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <center><div id='div_wait' class='mt-2'></div></center>
            <div class="modal-body" id='body_content'>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="$('#body_content').html('')">Chiudi</button>
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
	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	<!-- Bootstrap 5 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- jQuery UI -->
	<script src="{{ URL::asset('/') }}plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>





	<script src="{{ URL::asset('/') }}dist/js/makeapp.js?ver=<?php echo time(); ?>"></script>


	

@endsection