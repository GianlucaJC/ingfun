$(document).ready( function () {
    $('#tbl_rimborsi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_rimborsi').DataTable({
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf'
		],		

        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun Rimborso trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Rimborsi',
            infoFiltered: '(Filtrati da _MAX_ Rimborsi totali)',
        },

		
    });
	
} );

function zoom(id_foto) {
	url=$("#url").val()
    $("#title_modal").html("Foto inviata")
    $("#altri_btn").empty();
	filename=$("#id_foto"+id_foto).data("foto")	
	html="";
	html+="<div>";
		html+="<a href='"+url+"/dist/upload/rimborsi/"+filename+"' 	target='_blank'>";
			html+="<img src='"+url+"/dist/upload/rimborsi/thumbnail/medium/"+filename+"'>";
		html+="</a>";	
	html+="</div>"
	html+="<hr>";
	html+="<a href='"+url+"/dist/upload/rimborsi/"+filename+"' target='_blank'>";
		html+="<button type='button' class='btn btn-primary'>Zoom</button>";
	html+="</a>"
	$('#modal_img').modal('show')
	$("#body_modal").html(html)	
}


function save_rettifica(id_ref){
    testo_rettifica=$("#testo_rettifica").val()
    if (testo_rettifica.length==0) {
        alert("Definire correttamente il testo da inviare!")
        return false
    }
    //<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
    const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
    const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
    $("#altri_btn").html("<i class='fas fa-sync fa-spin'></i> Attendere...");
    
    fetch('save_rettifica', {
      method: 'POST',
      headers: {
        //"Content-type": "multipart/form-data",
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
      },
      body: 'testo_rettifica='+testo_rettifica+'&id_ref='+id_ref
    })
    .then(response => {
        if (response.ok) {
           return response.json();
        }
    })
    .then(response=>{
        if (response.header=="KO") 
            alert (response.message)
        else  {
            html=""
            testo="In attesa rettifica";back='secondary'
            html+=`<div class="alert alert-`+back+`" role="alert">
                <center>`+testo+`</center>
            </div>`            
            $("#td_status"+id_ref).html(html)
            $("#azioni"+id_ref).empty();
            $('#modal_img').modal('hide')

        }	
        this.sendko=false
    })
    .catch(status, err => {
        return console.log(status, err);
    })     
}

function sollecito_rettifica(id_ref){
    
}
function rettifica(id_ref) {
    $("#title_modal").html("Richiesta di rettifica")
    html=`
        <button type="button" class="btn btn-success" onclick='save_rettifica(`+id_ref+`)'>Salva ed invia rettifica</button>
    `;
    $("#altri_btn").html(html);

    html=""
	html+=`<h3>Rettifica rimborso ID:`+id_ref+`</h3><hr>
        <div class="form-group">
            <label for="testo_rettifica">Testo rettifica</label>
            <textarea class="form-control" id="testo_rettifica" rows="3"></textarea>
        </div>
    `;

    $('#modal_img').modal('show')
	$("#body_modal").html(html)	    
}

function azione(value,id_ref,obj,importo,dataora){
    testo=""
    if (value=="A") testo="Sicuri di accettare il rimborso?\n(L'operazione è irreversibile)"
    if (value=="S") testo="Sicuri di scartare il rimborso?\n(L'operazione è irreversibile)"
    if (value=="R") {
        rettifica(id_ref)
        return false
    }
    if (value=="SR") testo="Sicuri di sollecitare la rettifica?"
    if (!confirm(testo)) return false;

    html="<center><i class='fas fa-spinner fa-spin'></i></center>"
    $( obj ).prop( "disabled", true );
    $( obj).text("...");
    $("#td_status"+id_ref).html(html)


    //<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
    const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
    const csrf = metaElements.length > 0 ? metaElements[0].content : "";			

    
    fetch('risposta_rimborso', {
      method: 'POST',
      headers: {
        //"Content-type": "multipart/form-data",
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
      },
      body: 'value='+value+'&id_ref='+id_ref+'&importo='+importo+'&dataora='+dataora,
    })
    .then(response => {
        if (response.ok) {
           return response.json();
        }
    })
    .then(response=>{
        if (response.header=="KO") 
            alert (response.message)
        else  {
            $( obj ).prop( "disabled", false );
            v_text=value
            if (value=="SR") v_text="Sollecito rettifica"
            $( obj).text(v_text);
            html=""
            testo="";back=""
            if (value=="R") {testo="In attesa";back='warning'}
            if (value=="A") {testo="Accettato";back='success'}
            if (value=="S") {testo="Scartato";back='danger'}
            if (value=="SR") {testo="In attesa rettifica";back='secondary'}
            html+=`<div class="alert alert-`+back+`" role="alert">
                <center>`+testo+`</center>
            </div>`            
            $("#td_status"+id_ref).html(html)
            if (value!="R" && value!="SR") $("#azioni"+id_ref).empty();
            if (value=="SR") alert("Sollecito inviato!")

        }	
        this.sendko=false
    })
    .catch(status, err => {
        return console.log(status, err);
    })    
}