<!DOCTYPE html>
<html>
<head>
    <title>Invito a fatturare</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:200px;
        height:60px;        
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>

<?php 
if (file_exists("allegati/sezionali/".$sezionale.".jpg")) {?>
	<div>
		<center>
			<img src='allegati/sezionali/{{$sezionale}}.jpg' width=300px>
		</center>
	</div>
<?php } ?>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Invito a fatturare</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">ID fattura <span class="gray-color">#{{$id_doc}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Data  <span class="gray-color">{{$data_invito}}</span></p>
    </div>
    <!--
	<div class="w-50 float-left logo mt-10">
        <img src="https://techsolutionstuff.com/frontTheme/assets/img/logo_200_60_dark.png" alt="Logo">
    </div>
	!-->
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Azienda di Proprietà</th>
            <th class="w-50">Cliente</th>
        </tr>
        <tr>
            <td>
		
                <div class="box-text">
                    <p><b>{{$azienda_prop}}</b></p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    <p><b>{{$denominazione}}</b></p>
					<p>P.iva {{$piva}} CF {{$cf}}</p>
					<p>{{$cap}} {{$comune}} {{$provincia}}</p>
                    <p>{{$indirizzo}}</p>
					 <?php
						$altro="";
						if(strlen($sdi)!=0) 
							$altro="SDI $sdi";
						if(strlen($pec)!=0) 
							$altro=" PEC $pec";

					 ?>	
					<?php
					if (strlen($sdi)!=0 || strlen($pec)!=0) {?>
						<p>{{$altro}}</p>
					<?php } ?>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-100">Modalità di pagamento</th>
			<!--
            <th class="w-50">Shipping Method</th>
			!-->
        </tr>

		<?php
		
			$id_group=0;

			for ($sca=0;$sca<=count($elenco_pagamenti_presenti)-1;$sca++) {


				$descr="";									
				$tipo=$elenco_pagamenti_presenti[$sca]['tipo_pagamento'];
				
				if ($tipo=="1") $descr="Contanti";
				if ($tipo=="2") $descr="Bancomat";
				if ($tipo=="3") $descr="Assegno";
				if ($tipo=="4") $descr="Bonifico";

				
			?>

			<tr>
				<td class='w-100'>
					<b>{{$descr}}</b><br>
					Importo: <?php 
						if (strlen($elenco_pagamenti_presenti[$sca]['importo'])!=0)
							echo number_format($elenco_pagamenti_presenti[$sca]['importo'],2)." €"
						?></b> - 
					Data Scadenza:
					<?php
						if (strlen($elenco_pagamenti_presenti[$sca]['data_scadenza'])!=0) {
							$date=date_create($elenco_pagamenti_presenti[$sca]['data_scadenza']);
							echo date_format($date,"d/m/Y");
						} 	
						
						if ($tipo=="1") {
							echo "<br>";
							echo "Persona che riscuote: ";
							echo $elenco_pagamenti_presenti[$sca]['persona'];
						}
					?>
					<?php
						if ($tipo=="4") {
							echo "<br>";
							echo "Coordinate Bancarie: ";
							echo $elenco_pagamenti_presenti[$sca]['coordinate'];
						}
					?>
				</td>
				
			</tr>

		<?php } ?>
		<!-- fine caricamento !-->
			
		
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">Codice</th>
            <th class="w-50">Prodotto</th>
            <th class="w-50">Quantità</th>
            <th class="w-50">U.M.</th>
            <th class="w-50">Prezzo Unitario</th>
            <th class="w-50">Aliquota</th>
            <th class="w-50">Subtotale</th>
        </tr>
		@php ($tot_imp=0)
		@php ($tot_iva=0)
		@php ($tot_f=0)
		@foreach($articoli_fattura as $articolo)
		<?php
			$subtotale=$articolo->subtotale; //importo già ivato
			
			$iva=0;
			if (isset($arr_aliquota[$articolo->aliquota])) {
				$aliquota=$arr_aliquota[$articolo->aliquota];
				$iva=$articolo->quantita*$articolo->prezzo_unitario*($aliquota/100);
			}
			$tot_iva+=$iva;
			$tot_imp+=$subtotale-$iva;
			//$tot_f+=$subtotale+$iva;
			$tot_f+=$subtotale;
		?>
			<tr align="center">
				<td>{{$articolo->codice}}</td>
				<td>
                    {{$articolo->descrizione}}
                    <?php
                        if ($articolo->testo_libero_appalti!=null)
                            echo " - ".$articolo->testo_libero_appalti;
                    ?>
                </td>
				<td>{{$articolo->quantita}}</td>
				<td>{{$articolo->um}}</td>
				<td><?php echo number_format($articolo->prezzo_unitario,2)." €"?></td>
				<td>
					@if (isset($arr_aliquota[$articolo->aliquota]))
						{{$arr_aliquota[$articolo->aliquota]}}%
					@endif
				</td>
				<td><?php echo number_format($subtotale,2)." €"?></td>
			</tr>
		@endforeach



        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Imponibile</p>
                        <p>Iva</p>
                        <p>Totale Fattura</p>
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p><?php echo number_format($tot_imp,2)." €"?></p>
                        <p><?php echo number_format($tot_iva,2)." €"?></p>
                        <p><?php echo number_format($tot_f,2)." €"?></p>
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>

    <br>

</div>
</html>