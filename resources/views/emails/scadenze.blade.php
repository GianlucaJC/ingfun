<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p>{{ $body }}</p>
		<table style="width:70%;border: 1px solid gray">
			<tr>
				<th>Nominativo</th>
				<th>Data Inizio</th> 
				<th>Data Fine</th>
			</tr>
			
			<?php
				
					
					
				for($sca=0;$sca<count($scadenza);$sca++) {
					$data_inizio=date_create($scadenza[$sca]['data_inizio']);
					$data_fine=date_create($scadenza[$sca]['data_fine']);
					
					$nominativo=$scadenza[$sca]['nominativo'];
					$d1=date_format($data_inizio,"d/m/Y");
					$d2=date_format($data_fine,"d/m/Y");
					
					echo "<tr>";
						echo "<td>$nominativo</td>";
						echo "<td>$d1</td>";
						echo "<td>$d2</td>";
					echo "</tr>";
					
				}	
			/*
			@php ($data_inizio=date_create($scadenza->data_inizio))
					@php ($data_fine=date_create($scadenza->data_fine))
					
				<tr>
					<td>{{$scadenza->nominativo}}</td>
					<td>{{date_format($data_inizio,"d/m/Y");}}</td>
					<td>{{date_format($data_fine,"d/m/Y");}}</td>
				</tr>
			@endforeach
			*/


			?>	
			
			
		</table>	
		
</body>
</html>
