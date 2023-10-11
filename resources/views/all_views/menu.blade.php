<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>
@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')

@section('operazioni')
<!-- Pannello gestione utenza !-->
	<div class="p-3">
		<h5>Impostazioni globali</h5>
		<p>
			<form method='post' action="{{ route('menu') }}" id='frm_global' name='frm_global' autocomplete="off" class="needs-validation" autocomplete="off">
				<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			
				<div class="form-group">
					<label for="email_parco">Email parco auto</label>
					<input type="email" class="form-control" id="email_parco" name="email_parco" aria-describedby="email_parco" placeholder="E-mail"  maxlength=150 onkeyup="this.value = this.value.toLowerCase();" value="{{$email_parco ?? ''}}" >
				</div>
				<div class="form-group">
					<label for="email_acquisti">Email ufficio acquisti</label>
					<input type="email" class="form-control" id="email_acquisti" name="email_acquisti" aria-describedby="email_acquisti" placeholder="E-mail"  maxlength=150 onkeyup="this.value = this.value.toLowerCase();" value="{{$email_acquisti ?? ''}}" >
				</div>

			<button type="submit" id="btn_save" name="btn_save" class="btn btn-primary">Salva impostazioni</button>
				
			</form>	
		</p>
    </div>
	
	
@endsection


@section('notifiche') 
 
@endsection

@section('content_main')
  <input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <input type="hidden" value="{{url('/')}}" id="url" name="url">
  <!-- Content Wrapper. Contains page content -->


  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Divisione FUNEBRE | DASHBOARD</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('menu') }}">Home</a></li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

		@foreach($voci as $voce)

	
			@php ($indice=$voce->id)
			@if ($voce->visible==1)
				<div class="row">
					<div id='divrow' class="{{$voce->class_divrow}}">
					@if ($voce->disable!="disabled")
						@if ($voce->route && $voce->route!="-")
							@php ($params=$voce->params_route)
							<a href="{{route($voce->route,$params)}}">
						@else
							<a href="{{ route('menu',['parent_id'=>$indice]) }}">
						@endif
					@endif
					<?php
						$act=$voce->class_btn_action;
						$btn_cl="btn btn-";
						if ($act=="primary") $btn_cl.="primary";
						if ($act=="secondary") $btn_cl.="secondary";
						if ($act=="success") $btn_cl.="success";
						if ($act=="danger") $btn_cl.="danger";
						if ($act=="warning") $btn_cl.="warning";
						if ($act=="info") $btn_cl.="info";
						if ($act=="dark") $btn_cl.="dark";
	
						
						$btn_dis=$voce->disable;
						if ($btn_dis=="-") $btn_dis="";
					?>
					<div class="d-grid gap-2 mt-2">
						  <button id='btn_action' class="{{$btn_cl}}" type="button" {{$btn_dis}} >
						  
						  <i id='icon' class="{{$voce->class_icon}}" style='{{$voce->style_icon}}'></i><br>
							{{$voce->voce}}
						  </button>
						</div>
					@if ($voce->disable!="disabled")
					</a>
					@endif
					</div>
				</div>
			@endif
		@endforeach
		<hr>

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
	
	<script src="{{ URL::asset('/') }}dist/js/dash.js?ver=1.05"></script>
	
@endsection
