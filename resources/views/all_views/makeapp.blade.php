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
    
    <link rel="stylesheet" href="{{ URL::asset('/') }}dist/css/print.css?ver=<?php echo time();?>">
	
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
@endsection
<style>
    .itemlist { padding:0.4rem !important; }
    .panel-footer { display: flex; justify-content: space-between; }
    th, td { padding-right: 18px; }
    .clprint { font-size:1.2rem; }
</style>

<style>
    /* Custom styles for fixed sidebar */
    #side_list {
        position: fixed;
        top: 55px; /* Altezza navbar (~57px) + altezza barra bottoni (~31px) */
        left: 0.6rem; /* Larghezza della sidebar principale collassata di AdminLTE */
        width: 190px; /* Larghezza fissa per contenere gli elementi interni */
        height: calc(100vh - 55px); /* Altezza rimanente del viewport */
        background-color: white; /* Corrisponde al colore di sfondo del content-wrapper */
        z-index: 1030; /* Assicura che rimanga sopra il contenuto principale */
        display: flex; /* Aggiunto per consentire al card-body di espandersi */
    }
 
    #side_list > .card-body {
        flex: 1; /* Fa in modo che il card-body occupi tutto lo spazio verticale */
        overflow-y: auto; /* Abilita lo scroll solo per il corpo della card */
        padding: 0.5rem; /* Aggiunge un po' di spazio interno */
    }

    /* Regola la colonna del contenuto principale per lasciare spazio alla sidebar fissa */
    /* Seleziona specificamente il col-md-11 che è fratello di #side_list */
    .content-wrapper .content > .container-fluid.mt-4 > form > section.content > .container-fluid > .row > .col-md-11 {
        margin-left: 190px; /* Sposta il contenuto principale a destra per fare spazio alla sidebar fissa */
        width: calc(100% - 190px); /* Adatta la larghezza per evitare overflow */
    }
</style>

@section('space_top')
    <div class="d-flex align-items-center">
        <button type="button" class="btn btn-outline-success btn-sm" id="btn_save_all" onclick="save_all()">
            <i class="fa-solid fa-floppy-disk"></i> Salva Tutto
        </button>

        <button type="button" class="btn btn-outline-info btn-sm ms-2" id="btn_check_persone" onclick="check_persone()">
            <i class="fa-solid fa-person-circle-check"></i> Check persone
        </button>

        <button type="button" onclick='make_msg("","",0)' class="btn btn-outline-success btn-sm ms-2">
            <i class="fab fa-whatsapp"></i> Messaggio libero
        </button>

        <button type="button" id='btn_print' class="btn btn-outline-success btn-sm ms-2" onclick="generatePdfFromData()">
            <i class="fas fa-print"></i> Stampa videata
        </button>

        @if (isset($role) && $role=="admin")
        <button type="button" id='btn_show_logs' class="btn btn-outline-secondary btn-sm ms-2" onclick="showAppaltoLogs()">
            <i class="fas fa-history"></i> Log Eventi
        </button>
        @endif
    </div>
    <!-- Sliders -->
    <div class="d-flex align-items-center noprint flex-grow-1 mx-3">
        <div class="form-group mb-0 mx-2 w-100">
            <label for="zoom_slider_all" class="form-label-sm mb-0" style="font-size: 0.7rem;">Zoom Gen.</label>
            <input type="range" class="form-range" id="zoom_slider_all" min="0.2" max="2.5" step="0.05" value="0.54" oninput="setZoomAll(this.value, 1)">
        </div>
        <div class="form-group mb-0 mx-2 w-100">
            <label for="zoom_slider_m" class="form-label-sm mb-0" style="font-size: 0.7rem;">Zoom Matt.</label>
            <input type="range" class="form-range" id="zoom_slider_m" min="0.2" max="2.5" step="0.05" value="0.54" oninput="setZoomM(this.value, 1)">
        </div>
        <div class="form-group mb-0 mx-2 w-100">
            <label for="zoom_slider_p" class="form-label-sm mb-0" style="font-size: 0.7rem;">Zoom Pom.</label>
            <input type="range" class="form-range" id="zoom_slider_p" min="0.2" max="2.5" step="0.05" value="0.54" oninput="setZoomP(this.value, 1)">
        </div>
    </div>
@endsection

@section('notifiche')
   <span id='credit' >
	<center>Sviluppo prototipale</center>
   </span>
@endsection

@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style='background-color:white'>

    <!-- Main content -->
    <div class="content" id='div_tbx'>
    
      <div class="container-fluid mt-4">
		<form method='post' action="{{ route('makeapp') }}" id='frm_appalti' name='frm_appalti' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>	
			<input type="hidden" value="{{url('/')}}" id="url" name="url">
            <input type='hidden' name='id_giorno_appalto' id='id_giorno_appalto' value='{{$id_giorno_appalto}}'>
            
                <?php
                $all_servizi="";
                foreach($servizi as $servizio) {
                    if (strlen($all_servizi)>0) $all_servizi.="|";
                    $da_moltiplicare = $servizio->da_moltiplicare ?? 0;
                    $all_servizi.=$servizio->id.";".$servizio->descrizione.";".$da_moltiplicare;
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
				<div class="container-fluid" id='div_all'>

					<div class="row">					

					<div class="col-md-1" id='side_list'>

                        <div class="card-body">
                            <div class="accordion">

                                <!--accordion persone !-->
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwox" aria-expanded="true" aria-controls="flush-collapseTwox" >
                                        Persone
                                    </button>
                                    </h2>
                                    <div id="flush-collapseTwox" class="accordion-collapse collapse show" >
                                        <div class="accordion-body" draggable="true" ondrop="dropHandlerPers(event)" ondragover="dragoverHandlerPers(event)" >
 
                                            <!--testo persone!-->
                                            <input type='text' class='form-control input-sm' id='cerca_nome' placeholder='Cerca nome' style="width: 100%;">
                                            <div id="div_lav" class='mt-2'>
                                                <div class="d-grid gap-1">
                                                
                                                    <?php $elenco_lav=""; ?>
                                                    @foreach ($lavoratori as $lavoratore)
                                                        <?php
                                                            if (strlen($elenco_lav)!=0) $elenco_lav.="|";
                                                            $elenco_lav.=$lavoratore->id.";".$lavoratore->nominativo;
                                                            $tipo_contratto=$lavoratore->tipo_contratto;
                                                            $color="info";
                                                            if ($tipo_contratto==1) $color="danger";
                                                            if ($tipo_contratto==2) $color="primary";
                                                            if ($tipo_contratto==3) $color="success";
                                                            if ($tipo_contratto==5) $color="secondary";

                                                        ?>
                                                        <div style='line-height:0.9;' id='spanlav{{$lavoratore->id}}' class='allnomi'data-nome='{{$lavoratore->nominativo}}' ><font size='1rem'>
                                                            <a href="javascript:void(0)" class="link-{{$color}} link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"  id='btnlav{{$lavoratore->id}}' data-color='{{$color}}' data-idlav='{{$lavoratore->id}}' onclick='impegnalav({{$lavoratore->id}})' draggable="true" ondragstart="dragstartHandler(event)" >
                                                            <b>{{$lavoratore->cognome}}</b>
                                                            </a>
                                                            </font>
                                                        </div>
                                                        <div style='display:none' id='unlock{{$lavoratore->id}}'>
                                                            <a href='#' onclick="unlock({{$lavoratore->id}})">
                                                                <i class="fa-solid fa-unlock"></i>
                                                            </a>   
                                                            <hr>
                                                        </div>
                                                    @endforeach	
                                                    <input type='hidden' name='elenco_lav' id='elenco_lav' value='{{$elenco_lav}}'>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- fine accordion persone !-->                            
                                <!--accordion ditte !-->
                                <div class="accordion-item noprint">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Ditte
                                    </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" >
                                        <div class="accordion-body">
                                            <!--testo ditte !-->
                                            <input type='text' class='form-control mt-2' id='cerca_ditta' placeholder='Cerca Ditta' style="width: 100%;">
                                            <div id="div_dit" class='mt-2'>
                                                <div class="d-grid gap-1">
                                                    <?php $alld=""; ?>
                                                    @foreach ($ditte as $ditta)
                                                        <?php
                                                            if (strlen($alld)!=0) $alld.="|";
                                                            $alld.=$ditta->id.";".$ditta->denominazione.";".$ditta->alias;
                                                        ?>
                                                        <div style='line-height:1.2;margin-right:1rem' id='spandit' class='allditte' data-nome='{{$ditta->denominazione}}' ><font size='1rem'>
                                                            <a href="javascript:void(0)" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"  data-alias='{{$ditta->alias}}'  id='btndit{{$ditta->id}}' data-iddit='{{$ditta->id}}'  
                                                            draggable="true" ondragstart="dragstartHandlerDitta(event)" >
                                                            {{$ditta->denominazione}}
                                                            </a>
                                                            </font>
                                                        </div>                                                        
                                                    @endforeach	
                                                    <input type='hidden' name='alld' id='alld' value='{{$alld}}'>
                                                </div>
                                            </div>          
                                        </div>
                                    </div>
                                </div>
                                <!-- fine accordion ditte !-->

                                <!--accordion mezzi !-->
                                <div class="accordion-item noprint">
                                    <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                        Mezzi
                                    </button>
                                    </h2>
                                    <div id="flush-collapseThree" class="accordion-collapse collapse" >
                                        <div class="accordion-body">
                                            <!--testo mezzi!-->
                                                <input type='text' class='form-control mt-2' id='cerca_mezzo' placeholder='Cerca Mezzo' style="width: 100%;">
                                                <div id="div_mezzi" class='mt-2'>
                                                    <div class="d-grid gap-1">
                                                        <?php 
                                                            $infomezzi="";
                                                            $all_alias_m="";
                                                        ?>
                                                        @foreach($inventario as $flotta)
                                                            <?php
                                                                $mezzo=$flotta->targa;
                                                                $aliasm=$flotta->alias;
                                                                  
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
                                                                if (strlen($all_alias_m)!=0) $all_alias_m.="|";
                                                                $all_alias_m.=$aliasm.";".$targa;
                                                            ?>        
                                                        <div style='line-height:1.2;margin-right:1rem' id='spanmez' class='allmezzi' data-nome='{{$mezzo}}'><font size='1rem'>
                                                            <a href="javascript:void(0)" class="link-primary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"  id='btnmezzo{{$flotta->id}}' data-idmezzo='{{$flotta->id}}' 
                                                                data-targa='{{$flotta->targa}}' data-mezzo='{{$mezzo}}'
                                                                draggable="true" ondragstart="dragstartHandlerMezzi(event)"  >
                                                            <?php
                                                                if (strlen($aliasm)==0) 
                                                                    echo $mezzo;
                                                                else
                                                                    echo $aliasm;    
                                                            ?>  
                                                            </a>
                                                            </font>
                                                        </div>     
                                                            <?php
                                                                if (strlen($infomezzi)!=0) $infomezzi.=";";
                                                                $infomezzi.="$targa-$marca-$modello";
                                                            ?>
                                                        @endforeach	
                                                        <input type='hidden' name='all_alias_m' id='all_alias_m' value='{{$all_alias_m}}'>                                                            
                                                    </div>
                                                </div>       
                                        </div>
                                    </div>
                                </div>
                                <input type='hidden' name='infomezzi' id='infomezzi' value='{{$infomezzi}}'>
                                <!--fine accordion mezzi !-->
                            </div>
                        </div>

					</div>
					<!-- /.col -->
                    
					<div class="col-md-11">
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

                        

						<div style='text-align:center;background-color:rgb(30, 139, 255);color:rgb(255, 255, 30)'>
							<div style='padding:10px'>
								<a href='#' onclick="newapp('M','man');$('.collapse').collapse('hide')" class="link-light float-start">
									Aggiungi appalto
								</a>
								APPALTI DELLA MATTINA
							</div>
						</div>
						<div id="zoom_wrapper_m" style="overflow-x: auto;">
							<div id="div_tb_m">
								<table id='tbAppM' class='table' style="width:100%">
									<tbody>
										<tr class="d-flex">
											<!-- Colonne popolate dinamicamente -->
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<hr>

						<div style='text-align:center;background-color:rgb(30, 139, 255);color:rgb(255, 255, 30)'>
							<div style='padding:10px'>
								<a href='#' onclick="newapp('P','man');$('.collapse').collapse('hide')" class="link-light float-start">
									Aggiungi appalto
								</a>
								APPALTI DEL POMERIGGIO
							</div>
						</div>
						<div id="zoom_wrapper_p" style="overflow-x: auto;">
							<div id="div_tb_p">
								<table id='tbAppP' class='table' style="width:100%">
									<tbody>
										<tr class="d-flex">
											<!-- Colonne popolate dinamicamente -->
										</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div id='div_print' style='display: flex;justify-content: space-between;'>
							<!-- div utilizzato dalla stampa -->
						</div>

                        <div class="container-fluid" style="display:none" id='div_urg'>
                            <h4>Urgenze</h4>
                            <a href='javascript:void(0)' onclick="urgenze('New')">
                                <h5><i class="fas fa-calendar-plus"></i> Nuova urgenza</h5>
                            </a>    
                        </div>      
                             
                        <ul class="list-group list-group-horizontal-md" id='div_lista_urgenze' style="flex-wrap: wrap;">
                            
                        </ul>

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
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>
	<!-- Bootstrap 5 -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<!-- jQuery UI -->
	<script src="{{ URL::asset('/') }}plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>

	<!-- SweetAlert2 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        const numBox = {{ $config['numBox'] ?? 20 }};
        const elemBox = {{ $config['elemBox'] ?? 6 }};
        const elemRep = {{ $config['elemRep'] ?? 15 }};
        const elemAss = {{ $config['elemAss'] ?? 15 }};
    </script>
	<script src="{{ URL::asset('/') }}dist/js/makeapp.js?ver=<?php echo time(); ?>"></script>
	<script src="{{ URL::asset('/') }}dist/js/pdf_builder.js?ver=<?php echo time(); ?>"></script>


    <script>
        $(document).ready(function() {
            const backButtonLink = $('#id_back a');
            if (backButtonLink.length > 0) {
                backButtonLink.attr('href', '{{ route("listnewapp") }}');
                backButtonLink.removeAttr('onclick');
            }
        });
    </script>

@endsection
