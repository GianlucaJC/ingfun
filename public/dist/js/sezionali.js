$(document).ready( function () {
    $('#tbl_list_aziende tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_aziende').DataTable({
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
            zeroRecords: 'Nessuna Azienda trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Aziende',
            infoFiltered: '(Filtrati da _MAX_ Aziende totali)',
        },

		
    });
	
	$('#tipo_pagamento').select2();
	
} );

function set_sezione(id_sezionale) {

	$("#div_allegati").empty()
	if (id_sezionale.length==0) return false;
	base_path = $("#url").val();
	from="sezionali"

	fetch(base_path+'/class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=allegato_sezionale&from='+from+'&id_sezionale='+id_sezionale
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		$("#div_allegati").html(resp);
		$("#div_allegati").show(150);
		set_class_allegati(from,id_sezionale); //in demo-config.js
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})
}

function new_azienda() {
	$("#id_sezionale").val('')
	$('#frm_sezionali1')[0].reset();	
	$('#edit_elem').val('');
	$('#denominazione').val('');
	$('#div_definition').hide()
	$('#div_definition').show(150)
}

function popola_cap_pro(value) {
	$("#cap").val('');$("#provincia").val('')
	$("#cap").val(value.split("|")[0])
	$("#provincia").val(value.split("|")[1])
}

function edit_elem(id_elem) {
	d=Date.now()

	html="<a href='allegati/sezionali/"+id_elem+".jpg?v="+d+"'   target='_blank'>Vedi logo</a>";
	
	$("#div_logo").html(html)	
	descrizione=$("#id_descr"+id_elem).data("descr")
	mail_scadenze=$("#id_descr"+id_elem).data("mail_scadenze")
	mail_fatture=$("#id_descr"+id_elem).data("mail_fatture")
	mail_azienda=$("#id_descr"+id_elem).data("mail_azienda")
	mail_pec=$("#id_descr"+id_elem).data("mail_pec")
	telefono=$("#id_descr"+id_elem).data("telefono")
	
	$("#descr_contr").val(descrizione)
	$("#mail_scadenze").val(mail_scadenze)
	$("#mail_fatture").val(mail_fatture)
	$("#mail_azienda").val(mail_azienda)
	$("#mail_pec").val(mail_pec)
	$("#telefono").val(telefono)
	
	$("#edit_elem").val(id_elem)
	$('#div_definition').show(150)
	$("#id_sezionale").val(id_elem)
}

function dele_element(value) {
	if(!confirm('Sicuri di eliminare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}

function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}