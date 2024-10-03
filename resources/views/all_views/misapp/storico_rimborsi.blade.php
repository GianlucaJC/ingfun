<div id="div_lista_rimborsi" style='display:none'>
	<div class="row">
		<div class="col-md-12">
			<table id='tbl_rimborsi' class="display">
				<thead>
					<tr>
						<th>ID</th>
						<th>Descrizione</th>
						<th>Data</th>
						<th>Importo</th>
                        <th>Foto</th>
						<th>Stato</th>
					</tr>
				</thead>
				<tbody>
                    @foreach($elenco_rimborsi as $rimborso)    
                        <tr>
                            <td>{{$rimborso->id}}</td>
                            <td>{{$rimborso->descrizione}}</td>
                            <td>{{$rimborso->dataora}}</td>
                            <td>{{$rimborso->importo}}</td>
							<?php
								$stato_rimb=$rimborso->stato;
								$stato_view="In Attesa";$back="yellow";$colo="black";
								if ($stato_rimb=="0") {$stato_view="In Attesa";$back="yellow";$colo="black";}
								if ($stato_rimb=="1") {$stato_view="Approvato";$back="green";$colo="white";}
								if ($stato_rimb=="2") {$stato_view="Scartato";$back="red";$colo="white";}
							?>
                        
                            
                            <td style='width:100px'>
                                @if ($rimborso->filename!=null && strlen($rimborso->filename)!=0)
                                    <span id='id_foto{{$rimborso->id}}' data-foto='{{$rimborso->filename}}'>
                                    <a href='javascript:void(0)' onclick=''>
                                        <img class="rounded float-left img-fluid img-thumbnail"  src='{{ URL::asset('/') }}dist/upload/rimborsi/thumbnail/small/{{$rimborso->filename}}'>
                                    </a>
                                @endif
                            </td>                                
                        
							<td style="background-color:{{$back}}">
								<font color='{{$colo}}'>{{$stato_view}}</font>
							</td>
                        </tr>
                    @endforeach
                </tbody>
			</table>						
		</div>
	</div>
	<button type="button" class="btn btn-secondary"  onclick="$('#div_servizi').show(150);$('#div_lista_rimborsi').hide()">Esci</button>
</div>