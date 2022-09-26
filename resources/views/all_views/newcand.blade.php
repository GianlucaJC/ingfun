@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('extra_style')  
  <!-- Select2 -->
  <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
@endsection

@section('content_main')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">NUOVA Candidatura</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
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
         
			<!-- Left Window !-->
			
			<div class="col-md-6">
				<center><h4>DATI ANAGRAFICI</h4></center>
				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="cognome" name='cognome' type="text" placeholder="Inserisci il tuo cognome" required maxlength=40 onkeyup="this.value = this.value.toUpperCase();"  value=""  />
							<label for="cognome">Cognome*</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-floating mb-3 mb-md-0">
							<input class="form-control" id="nome" name='nome' type="text" placeholder="Inserisci il tuo nome" maxlength=60 required onkeyup="this.value = this.value.toUpperCase();" value=""  />
							<label for="nome">Nome*</label>
						</div>
					</div>
				</div>
				<div class="row mb-3">
					<div class="col-md-12">
						<div class="form-floating">
							<input class="form-control" id="indirizzo" name='indirizzo' type="text" placeholder="Via/Piazza" required maxlength=150 value=""  />
							<label for="cognome">Indirizzo*</label>
						</div>
					</div>
				</div>
				
				<div class="row mb-3">
					<div class="col-md-3">
						<div class="form-floating">
							<input class="form-control" id="cap" name='cap' type="text" placeholder="C.A.P." required maxlength=5 value=""  />
							<label for="cognome">Cap*</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="comune" name='comune' type="text" placeholder="Comune/Località" required maxlength=150 value=""  />
							<label for="comune">Comune*</label>
						</div>
					</div>

					<div class="col-md-3">
						<div class="form-floating">
							<input class="form-control" id="provincia" name='provincia' type="text" placeholder="Provincia" required maxlength=10 value=""  />
							<label for="cognome">Provincia*</label>
						</div>
					</div>
				</div>


				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="codfisc" name='codfisc' type="text" placeholder="C.F." required maxlength=16 value=""  />
							<label for="codfisc">Codice Fiscale*</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="datanasc" name='datanasc' type="date" placeholder="Nato il" required value=""  />
							<label for="datanasc">Data di nascita*</label>
						</div>
					</div>

				</div>


				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="comunenasc" name='comunenasc' type="text" placeholder="Comune/Località" required maxlength=150 value=""  />
							<label for="comune">Comune di Nascita*</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="pro_nasc" name='pro_nasc' type="text" placeholder="Provincia" required maxlength=10 value=""  />
							<label for="pro_nasc">Provincia di Nascita*</label>
						</div>
					</div>

				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="email" name='email' type="email" placeholder="Email" required maxlength=150 value="" onkeyup="this.value = this.value.toLowerCase();" />
							<label for="email">Email Privata*</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="telefono" name='telefono' type="text" placeholder="Telefono" required maxlength=20 value=""  />
							<label for="telefono">Telefono privato*</label>
						</div>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="pec" name='pec' type="email" placeholder="Pec" required maxlength=150 value="" onkeyup="this.value = this.value.toLowerCase();" />
							<label for="email">Pec*</label>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-floating">
							<input class="form-control" id="iban" name='iban' type="text" placeholder="IBAN" maxlength=27 value=""  />
							<label for="telefono">IBAN</label>
						</div>
					</div>
				</div>

				<div class="row mb-3">
					<div class="col-md-12">
					  <label for="curr" class="form-label">Curriculum vitae</label>
					  <input class="form-control" type="file" id="curr" name='curr'>
					</div>
				</div>

				
			</div>
			<!-- end Left Window !-->
			
			
			<!-- Right Window !-->
			<div class="col-md-6">
			
				<center><h4>DATI SPECIFICI</h4></center>
					<div class="row mb-3">							
						<div class="col-lg-4">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="stato_occ" aria-label="Stato Occupazione" name='stato_occ' required>
								<option value=''>Select...</option>
								<option value='1'>Disoccupato</option>
							</select>
							<label for="stato_occ">Stato Occupazione*</label>
							</div>
						</div>

						<div class="col-lg-4">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="rdc" aria-label="Reddido Cittadinanza" name='rdc' required>
								<option value=''>Select...</option>
								<option value='0'>No</option>
								<option value='1'>Sì</option>
							</select>
							<label for="rdc">Reddito di cittadinanza</label>
							</div>
						</div>
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control" id="cat_pro" name='cat_pro' type="text" placeholder="categoria protetta" maxlength=5 value=""  />
							<label for="telefono">Categoria protetta (%)</label>
						</div>
					</div>
						
					</div>
					

					<div class="row mb-3">							
						<div class="col-lg-4">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="titolo_studio" aria-label="Titolo di studio" name='titolo_studio' >
								<option value=''>Select...</option>
								<option value='1'>Licenza Media</option>
								<option value='2'>Diploma Istituto Superiore</option>
								<option value='3'>Laurea</option>
							</select>
							<label for="titolo_studio">Titolo di studio conseguito</label>
							</div>
						</div>

						<div class="col-lg-4">
						  <div class="form-floating mb-3 mb-md-0">
							
							<input class="form-control" id="istituto_conseguimento" name='istituto_conseguimento' type="text" placeholder="Istituto" required maxlength=150 value=""  />
							<label for="istituto_conseguimento">Istituto di conseguimento</label>
							</div>
						</div>
					<div class="col-md-4">
						<div class="form-floating">
							<input class="form-control"  id="anno_mese" name='anno_mese' type="month" placeholder="YYYY-MM" maxlength=7/>
							<label for="anno_mese">Anno e mese</label>
						</div>
					</div>
						
					</div>


					<div class="row mb-3">							
						<div class="col-lg-4">
						  
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select select2" id="patenti" aria-label="Patenti" name='patenti' multiple="multiple" >
								<option value='AM'>AM</option>
								<option value='A1'>A1</option>
								<option value='A2'>A2</option>
								<option value='A'>A</option>
								<option value='B'>B</option>
								<option value='BE'>BE</option>
								<option value='C1'>C1</option>
								<option value='C1E'>C1E</option>
								<option value='C'>C</option>
								<option value='C3'>C3</option>
								<option value='D1'>D1</option>
								<option value='D1E'>D1E</option>
								<option value='D'>D</option>
								<option value='DE'>DE</option>

							</select>
							<b>Patenti</b>
							</div>
						</div>

						<div class="col-lg-8">

							  <div class="form-group">
								
								<input type="range" class="custom-range" id="capacita" name="capacita" value="0" oninput="$('#out').html(this.value)">
								<label for="capacita">Livello Capacità</label> <span id='out'></span>
								
								
							  </div>

						</div>

					</div>	


					<div class="row mb-3">							
						<div class="col-lg-4">
							<div class="form-check ml-1">
							  <input class="form-check-input" type="checkbox" value="" id="libero_p" name="libero_p">
							  <label class="form-check-label" for="libero_p">
								Libero professionista
							  </label>
							</div>

						</div>

						<div class="col-lg-4">

						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="tipo_contratto" aria-label="Tipologia contratto" name='tipo_contratto' >
								<option value=''>Select...</option>
								<option value='1'>DETERMINATO</option>
								<option value='2'>INDETERMINATO</option>
								<option value='3'>TIROCINIO GARANZIA GIOVANI</option>
								<option value='4'>APPRENDISTATO</option>
								<option value='5'>INCARICO PROFESSIONALE</option>
								<option value='6'>TIROCINIO EXTRACURRICULARE</option>
							</select>
							<label for="tipo_contratto">Tipologia contratto</label>
							</div>
						</div>
						
						<div class="col-lg-4">
							<div class="form-floating mb-3 mb-md-0">
								<input type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" class="form-control" name="ore_sett" id="ore_sett"/>		  
								<label for="ore_sett">Ore settimanali</label>
							</div>		
						</div>	
					</div>		

					<div class="row mb-3">							
						<div class="col-lg-6">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="soc_ass" aria-label="Società assunzione" name='soc_ass' required>
								<option value=''>Select...</option>
							</select>
							<label for="soc_ass">Società di assunzione</label>
							<a href="#" class="link-primary" target='_blank'>
								Definisci nuova
							</a>	
							</div>
						</div>

						<div class="col-lg-6">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="divisione" aria-label="Divisione" name='divisione' required>
								<option value=''>Select...</option>
							</select>
							<label for="divisione">Divisione</label>
							<a href="#" target='_blank'>
								Definisci nuova
							</a>
							</div>
						</div>

						
					</div>		

					<div class="row mb-3">							
						<div class="col-lg-6">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="area_impiego" aria-label="Area impiego" name='area_impiego' required>
								<option value=''>Select...</option>
							</select>
							<label for="area_impiego">Area di impiego</label>
							<a href="#" class="link-primary" target='_blank'>
								Definisci nuova
							</a>	
							</div>
						</div>

						<div class="col-lg-6">
						  <div class="form-floating mb-3 mb-md-0">
							
							<select class="form-select" id="mansione" aria-label="Mansione" name='mansione' required>
								<option value=''>Select...</option>
							</select>
							<label for="mansione">Mansione</label>
							<a href="#" target='_blank'>
								Definisci nuova
							</a>
							</div>
						</div>

						
					</div>					
					
			
			</div>
			<!-- End Right Window !-->

         

			
        </div>
        <!-- /.row -->
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
	<script src="{{ URL::asset('/') }}dist/js/newcand.js?ver=1.2"></script>
	<!--select2 !-->
	<script src="plugins/select2/js/select2.full.min.js"></script>
@endsection 