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
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },
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

function azione(value,id_ref,obj,importo,dataora){
    testo=""
    if (value=="R") testo="Sicuri di inviare una richiesta di rettifica?"
    if (value=="A") testo="Sicuri di accettare il rimborso?\n(L'operazione è irreversibile)"
    if (value=="S") testo="Sicuri di scartare il rimborso?\n(L'operazione è irreversibile)"
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
            $( obj).text(value);
            html=""
            testo="";back=""
            if (value=="R") {testo="In attesa";back='warning'}
            if (value=="A") {testo="Accettato";back='success'}
            if (value=="S") {testo="Scartato";back='danger'}
            html+=`<div class="alert alert-`+back+`" role="alert">
                <center>`+testo+`</center>
            </div>`            
            $("#td_status"+id_ref).html(html)
            if (value!="R") $("#azioni"+id_ref).empty();

        }	
        this.sendko=false
    })
    .catch(status, err => {
        return console.log(status, err);
    })    
}