@extends('all_views.viewmaster.index')

@section('title', 'IngFUN')
@section('extra_style') 
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Voci di Menu</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Voci di menu</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<input type='hidden' id='id_get' value='{{$id_get}}'>
		<input type='hidden' id='parent_get' value='{{$parent_get}}'>

			
		<form method='post' action="{{ route('adminmenu') }}" id='frm_mnu' name='frm_mnu' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>

			<input type='hidden' name='id_mod' id='id_mod'>
			<div class="container-fluid p-3" style='border:1px 	dotted;display:none' id='div_mnu_admin'>
			<p><a class="link-opacity-30" href="https://fontawesome.com/v5/search?o=r&m=free" target="_blank">Visiona Loghi</a></p>
			
			<input type='hidden' name='tipo_view' id='tipo_view_bis'>
				
				<div class="row mb-2">
					<div class="col-md-12">
						<div class="form-floating">
							<input class="form-control" id="voce_edit" name='voce_edit' type="text" placeholder="Voce di menu"  required />
							<label for="voce_edit">Voce*</label>
						</div>
					</div>
				</div>

				<div class="row mb-2">
					<div class="col-md-12">
						<div class="form-floating">
							<textarea class="form-control" id="note" name="note" rows="3" placeholder="Dettaglio della voce"></textarea>
							<label for="note">Note</label>
						</div>
					</div>
				</div>
				
				<div class="row mb-2">
					<div class="col-md-6">
						<select class="form-select select2" name="ruolo[]" id="ruolo" aria-label="Ruolo" multiple required>
							
							@foreach ($roles as $role) 
								<option value="{{$role->name}}" 
								>{{$role->name}}</option>
							@endforeach
						</select>
						<label for="ruolo">Ruolo*</label>
					</div>
					<div class="col-md-6">
						<select class="form-select select2" name="permesso[]" id="permesso" aria-label="Permessi" multiple>
							
							@foreach ($permissions as $permission) 
								<option value="{{$permission->name}}" 
								>{{$permission->name}}</option>
							@endforeach
						</select>
						<label for="ruolo">Permessi</label>
					</div>	
				
				</div>	
				<input type='hidden' id='button_color' name='button_color'>
				
				
				<div class="row mb-2">
					<div class="col-md-6">				
						<button type="button" class="bottoni btn" id="btn_primary">Primary</button>
						<button type="button" class="bottoni btn"  id="btn_secondary">Secondary</button>
						<button type="button" class="bottoni btn" id="btn_success">Success</button>
						<button type="button" class="bottoni btn" id="btn_danger">Danger</button>
						<button type="button" class="bottoni btn" id="btn_warning">Warning</button>
						<button type="button" class="bottoni btn" id="btn_info">Info</button>
						<button type="button" class="bottoni btn" id="btn_dark">Dark</button>
						<br>						
						<label>Stile del bottone*</label>
					</div>
					
					<div class="col-md-3">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="btn_visible" id="btn_visible" required >
							<option value=''>Select...</option>
							<option value='1'>SI
							</option>
							<option value='0'>NO
							</option>
							</select>
							<label for="btn_visible">Voce Visibile*</label>
						</div>
					</div>						

					<div class="col-md-3">
						<div class="form-floating mb-3 mb-md-0">
							<select class="form-select" name="btn_disable" id="btn_disable" required >
							<option value=''>Select...</option>
							<option value='disabled'>SI
							</option>
							<option value='-'>NO
							</option>
							</select>
							<label for="btn_disable">Voce Disabilitata*</label>
						</div>
					</div>	
				</div>	
				
				<br><hr>
				<button type="submit" name="btn_save" class="btn btn-primary" value="save">Salva</button>
				
				<button type="button" class="btn btn-secondary" onclick="$('#div_mnu_admin').hide(120)">Chiudi</button>
			</div>
		</form>
		
		
		<div class="container-fluid p-3 mb-3" style='border:1px 	dotted;display:none' id='div_schema'>
			<form method='post' action="{{ route('adminmenu',['id_get'=>$id_get,'parent_get'=>$parent_get]) }}" id='frm_sposta' name='frm_sposta' autocomplete="off">
				<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
				
					<input type='hidden' name='id_up_schema' id='id_up_schema'>
					
					<input type='hidden' name='parent_id_dest' id='parent_id_dest'>

					<input type='hidden' name='parent_id_origin' id='parent_id_origin'>
					
					<input type='hidden' name='ordine_origine' id='ordine_origine'>

					<?php
						$render=build_select($arr_menu);	
						echo $render;
					?>
				
					<br><hr>
					<button type="submit" name="btn_sposta" id="btn_sposta" class="btn btn-primary" onclick='check_sposta()' value="save">Salva</button>
					
					<a href="{{ route('adminmenu') }}">
					<button type="button" class="btn btn-secondary">Chiudi</button>
					</a>

			</div>
		</div>

		
		<form method='post' action="{{ route('adminmenu') }}" id='frm_menu' name='frm_menu' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type="submit" style='display:none' id="refresh_tipo" value="refresh tipo">
			
        <div class="row mb-2">
			<div class="col-md-6">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="tipo_view" id="tipo_view" required onchange="$('input[type=submit]#refresh_tipo').click();">
					<option value=''></option>
					@foreach ($roles as $role) 
						<option value="{{$role->name}}" 
						@if ($tipo_view==$role->name)
							selected 
						@endif
						>{{$role->name}}</option>
						
					@endforeach
					</select>
					<label for="tipo_view">Tipo di visione*</label>
				</div>
			</div>
			<div class="col-md-6">
				<a href='#' onclick="schema(0,0,0)">
					<button type="button" class="btn btn-warning"><i class="fas fa-bars"></i></button>	
				</a>
			</div>
		</div>	
        <div class="row">
		
          <div class="col-md-12">
		  
				<table id='tbl_menu' class="display">
					<thead>
						<tr>
							<th>ID</th>
							<th>Menu riferimento</th>
							<th>Voce/Menu</th>
							<th>Note</th>
							<th>Permessi</th>
							<th>Ruoli</th>
							<th>Colore Bottone</th>
							<th>Logo Bottone</th>
							<th>Visibile</th>
							<th>Disabilitato</th>
							<th>Operazioni</th>
						</tr>
					</thead>
					<tbody>
						@foreach($voci_menu as $voce)
							<tr>

								<td>
									{{ $voce->parent_id }} - 
									{{ $voce->id }}
									<?php
										$has_c=0;
										if (has_child($arr_menu,$voce->id)) $has_c=1;
									?>	
								</td>


								<td>
									<font color='red'>
									@if (isset($info_menu[$voce->parent_id]))
										{{$info_menu[$voce->parent_id]}}
									@endif
									@if ($voce->parent_id==0)
										{{ $voce->voce }}	
									@endif
									</font>
								</td>					
									
								<td>
									@if (strlen($voce->route)==0)
										<font color='red'>
									@endif
										@if ($voce->parent_id!=0)
											{{ $voce->voce }}	
										@endif
									@if (strlen($voce->route)==0)
										</font>
									@endif

								
									<span style='display:none' id='id_descr{{$voce->id}}' data-voce='{{ $voce->voce }}' 
									data-note='{{ $voce->note }}'		
									data-class_btn_action='{{$voce->class_btn_action}}'
									data-visible='{{$voce->visible}}'
									data-disable='{{$voce->disable}}'
									data-roles='{{$voce->roles}}'
									data-permissions='{{$voce->permissions}}'
									>
									
									
									</span>
								</td>

								<td>
									{{ $voce->note }}
								</td>


								<td>
									{{ $voce->permissions }}
								</td>


								<td>
									{{ $voce->roles }}
								</td>

								<td style="text-align:center">
									<span class="btn btn-{{ $voce->class_btn_action }}"></span>
								</td>	

								<td style="text-align:center">
									<i class="{{ $voce->class_icon }}"></i>
								</td>								
								
								<td style="text-align:center">
									@if ($voce->visible==1)
										Si
									@else
										No
									@endif
								</td>								
								<td>

									@if ($voce->disable=="disabled")
										Si
									@endif
								</td>								
								
								
								<td>

									

								<a href='#' onclick="edit_elem({{$voce->id}})">
									<button type="button" class="btn btn-info" alt='Edit'><i class="fas fa-edit"></i></button>
								</a>
								<a href='#' onclick="schema({{$has_c}},{{$voce->id}},{{$voce->parent_id}})">
									<button type="button" class="btn btn-warning"><i class="fas fa-bars"></i></button>	
								</a>
	

									
								</td>	
							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Menu riferimento</th>
							<th>Voce/menu</th>
							<th>Note</th>
							<th>Permessi</th>
							<th>Ruoli</th>
							<th>Colore Bottone</th>
							<th>Logo Bottone</th>
							<th>Visibile</th>
							<th>Disabilitato</th>
							<th></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
				
				<hr>


				<hr>
				<button type="submit" name="btn_save_menu" value="save" class="btn btn-primary">Salva struttura attuale menu</button>
				<button type="submit" name="btn_ripr_menu" value="ripr" class="btn btn-secondary">Ripristina ultima struttura menu</button>
          </div>
        </div>
		</form>
        <!-- /.row -->
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
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/menuadmin.js?ver=1.253"></script>

@endsection

<?php
	function has_child($rows,$id) {
	  foreach ($rows as $row) {
		if ($row['parent_id'] == $id) {
		  return true;
		}
	  }
	  return false;
	}
	function build_select($rows,$parent=0){  
		$result="";
		
		$result.="<ul class='nav nav-treeview'>";
		foreach ($rows as $row){
			if ($row['parent_id'] == $parent && $row['visible'] == 1){
				
				$dis=$row['disable'];

				$result.="<li class='nav-item menu'>";
				
				
					$url="#";
					if (strlen($row['route'])!=0 && $row['route']!="-") {
						$href=$row['route'];							$params=$row['params_route'];
						$url=route($row['route'],$params);
					}	
					$child="";
					if (has_child($rows,$row['id'])) {
						$child="child";
					}	
						
						if ($child=="child") {
						  $result.="<a href='#' onclick='open_menu({$row['id']})'>";
						    $result.="<font color='green'>
							<i class='fas fa-folder-minus' id='fold{$row['id']}'></i>
							</font> ";
						  $result.="</a> ";
						}	
					$result.="<a class='nav-link $dis'>";

						$result.="<button type='button' class='btn  btn-outline-primary btn_schema $child' id='id_btn{$row['id']}' data-ordine_origine='{$row['ordine']}' data-parent_id='{$row['parent_id']}' data-child='$child'>";
						$result.="<i class='nav-icon {$row['class_icon']}'></i>";
						$result.= "<span class=''>{$row['title']}</span>";
						$result.="</button>";
					$result.="</a>";				
					
					if (has_child($rows,$row['id'])) {
						
						$result.="<ul class='nav nav-treeview ml-5' id='mnu{$row['id']}'>";
						$result.="<li class='nav-item'>";
							$result.= build_select($rows,$row['id']);	
						$result.="</li>";
						$result.="</ul>";
					}
				
				$result.="</li>";

					 
			}
		}
		$result.="</ul>";
		
	  return $result;
	}
?>
