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
    @if ($tipo=="new")
      <u>All'attenzione del Coordinatore</u><br><br>
      la presente per informare che un lavoratore ha richiesto un rimborso (ID: {{$id_richiesta}}):<br>      
      Cliccare sul link per loggarsi e gestire il rimborso 
      <a href='https://217.18.125.177/ingfun/public/rimborsi_coord'>link rimborso</a>              
    @elseif ($tipo=="SR")
      Caro lavoratore, la presente per informarti che un Coordinatore/Responsabile ha richiesto un sollecito di rettifica per la tua richiesta  di rimborso (ID: {{$id_richiesta}}):<br>      
      Clicca sul link per loggarti e modificare il rimborso 
      <a href='https://217.18.125.177/ingfun/public/misapp/0/{{$id_mask}}'>link rimborso</a>      
    @elseif ($tipo=="R")
	  	Richiesta di rettifica per il rimborso <b>{{$id_richiesta}}</b><br>
		  Caro lavoratore, la presente per informarti che un Coordinatore/Responsabile ha richiesto una rettifica per la tua richiesta  di rimborso (ID: {{$id_richiesta}}):<br>
      
      <p><i>{{$testo_rettifica}}</i></p>

      Clicca sul link per loggarti e modificare il rimborso 
      <a href='https://217.18.125.177/ingfun/public/misapp/0/{{$id_mask}}'>link rimborso</a>
    @else
      <p>{{$body_msg}}</p>
    @endif 
</body>
</html>
