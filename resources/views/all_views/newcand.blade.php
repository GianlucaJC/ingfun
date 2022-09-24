@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

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
	<script src="{{ URL::asset('/') }}dist/js/newcand.js?ver=1.1"></script>

@endsection 