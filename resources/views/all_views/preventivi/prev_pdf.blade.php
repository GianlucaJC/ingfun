<!DOCTYPE html>
<html>
<head>
    <title>Preventivo</title>
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
    <h1 class="text-center m-0 p-0">Preventivo</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <p class="m-0 pt-5 text-bold w-100">ID preventivo <span class="gray-color">#{{$id_doc}}</span></p>
        <p class="m-0 pt-5 text-bold w-100">Data  <span class="gray-color">{{$data_preventivo}}</span></p>
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
		@foreach($articoli_preventivo as $articolo)
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
				<td>{{$articolo->descrizione}}</td>
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
                        <p>Totale Preventivo</p>
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
	
	<?php 
		if (strlen($note)!=0) {?>			
			<div>
				<p class='mt-2'><small>Note</small></p>
				<i>{{$note}}</i>
			</div>
	<?php } ?>

	<div style='text-align:right'>
		<br>
		<h3>Firma per accettazione</h3>
	</div>
</div>
</html>