<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width">
  <title></title>
  <style></style>
</head>

<body>
    <h1>{{ $title }}</h1>
    <p>Appalto ID: <b>{{$appalto->id}}</b></p>
    <p>Lavoratore per il quale si richiede la disponibilità: <b>{{$appalto->nominativo}}</b></p>
    <hr>
    Per prendere visione dell'appalto <a href='https://217.18.125.177/ingfun/public/misapp'>clicca quì</a>
  
    <h4>Informazioni sull'appalto</h4>
    <?php
        $st="style='width:200px'";$st1="style='text-align:left'";
    ?>
    <table role="presentation" border="1" cellspacing="0" width="100%">
        <tr>
            <td {{$st}}>Luogo Incontro</td>
            <th {{$st1}}>{{$appalto->luogo_incontro}}</th>
        </tr>    
        <tr>
            <td {{$st}}>Orario Incontro</td>
            <th {{$st1}}>{{$appalto->orario_incontro}}</th>
        </tr>          
        <tr>
            <td {{$st}}>Chiesa</td>
            <th {{$st1}}>{{$appalto->chiesa}}</th>
        </tr>
        <tr>
            <td {{$st}}>Data</td>
            <th {{$st1}}>{{ date('d-m-Y', strtotime($appalto->data_ref)) }}</th>
        </tr>          
        <tr>
            <td {{$st}}>Ditta</td>
            <th {{$st1}}>{{ $appalto->ditta_ref }}</th>
        </tr>
        <tr>
            <td {{$st}}>Squadra</td>
            <?php
                    $sq="";
                    $ids_lav=$appalto->ids_lav;
                    for ($sca=0;$sca<=count($ids_lav)-1;$sca++){
                        if (isset($appalto->lav_id[$ids_lav[$sca]])) {
                            if (strlen($sq)>0) $sq.=", ";
                            $sq.=$appalto->lav_id[$ids_lav[$sca]];
                        }
                    }
            ?>
            <th {{$st1}}>{{$sq}}</th>
        </tr>
        <tr>
            <td {{$st}}>Responsabile mezzo</td>
            <?php
                $responsabile="";
                if (isset($appalto->lav_id[$appalto->responsabile_mezzo]))
                    $responsabile=$appalto->lav_id[$appalto->responsabile_mezzo];
            ?>
            <th {{$st1}}>{{$responsabile}}</th>
        </tr>

    </table>
</body>
</html>
