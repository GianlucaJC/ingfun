
// Example starter JavaScript for disabling form submissions if there are invalid fields



$(document).ready( function () {
	//$('body').addClass("sidebar-collapse");
	$('.select2').select2()
	set_table()
	set_step($("#step_active").val())
});


$(document).on('submit','#needs-validation2', function(){
	form_val="needs-validation2"
	valida(form_val)
})	


function set_table() {

    var table=$('#tbl_list_appalti').DataTable({
		"pageLength": 100,
        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun appalto trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Appalti',
            infoFiltered: '(Filtrati da _MAX_ appalti totali)',
        },
    });	
	



    $('#tbl_list_articoli tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_articoli').DataTable({
		/*
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf'
		],
		*/		
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
            zeroRecords: 'Nessun articolo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Articoli',
            infoFiltered: '(Filtrati da _MAX_ articoli totali)',
        },
    });	
	
	
}

function edit_product(id_riga) {
	$("#title_modal").html("Inserimento/Modifica dati riga fattura")
	
	$('#modal_story').modal('toggle')
	//$("#body_modal").html("Caricamento informazioni in corso...")		
	
	codice=$("#inforow"+id_riga).data("codice")
	descrizione=$("#inforow"+id_riga).data("descrizione")
	quantita=$("#inforow"+id_riga).data("quantita")
	um=$("#inforow"+id_riga).data("um")
	prezzo_unitario=$("#inforow"+id_riga).data("prezzo_unitario")
	subtotale=$("#inforow"+id_riga).data("subtotale")
	aliquota=$("#inforow"+id_riga).data("aliquota")
	$("#codice").val(codice)
	$("#prodotto").val(descrizione)
	$("#quantita").val(quantita)
	$("#um").val(um)
	$("#prezzo_unitario").val(prezzo_unitario)
	$("#subtotale").val(subtotale)
	$("#aliquota").val(aliquota)
	
	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/edit_row_fattura",
		data: {_token: CSRF_TOKEN, id_riga:id_riga},
		success: function (data) {
			$.each(JSON.parse(data), function (i, item) {

			});
		}
	});	
}

function dele_product() {
	if (!confirm("Sicuri di cancellare la riga dell'articolo?")) {
		  event.preventDefault();
		  event.stopPropagation();
		  return false;
	}	  
		
}

function valida(form_val) {
	$("#div_alert").empty()
	var forms = document.getElementsByClassName(form_val);
	var validation = Array.prototype.filter.call(forms, function(form) {
		if (form.checkValidity() === false) {
		  event.preventDefault();
		  event.stopPropagation();
		  html=""
    	  html+="<div class='alert alert-warning' role='alert'>";
			if (form_val=="needs-validation2") html+="Controllare i campi evidenziati in rosso";
		  html+="</div>";
		  $("#div_alert").html(html)
		} else {
		
			if (form_val=="needs-validation2") {
				
			} else {
				event.preventDefault();
				event.stopPropagation();
				
			}
			save_sez()
		}	
		form.classList.add('was-validated');
	});
	
}


function set_step(step_active) {
	$(".sezioni").hide()

	$("#div_sez_"+step_active).show()
	
	$(".steps").removeClass('btn-primary');
	$('.steps').addClass('btn-secondary');
	$('#btn_step_'+step_active).removeClass('btn-secondary');
	$('#btn_step_'+step_active).addClass('btn-primary');
}

function save_sez() {
	step_active=$("#step_active").val()
	step_active++;	
	$(".sezioni").hide(80)
	$("#div_sez"+step_active).show(150)

}


function metodo_ins(value) {
	$('.metodi').hide(150);
	if (value=="2") $('#div_from_appalti').show(150)
	ditta=$("#ditta").val()
	if (value=="3") {
		popola_servizi(ditta)
		$('#div_from_servizi').show(150)
	}
	if (value=="4")
		$("#div_lista_articoli").show(100)
	else
		$("#div_lista_articoli").hide(100)
}

function check_save() {
	servizi=$("#servizi").val()
	if (servizi.length==0) {
		event.preventDefault()
		alert("Definire almeno un servizio associato alla ditta!")
		return false;
	}
	lavoratori=$("#lavoratori").val()
	if (lavoratori.length==0) {
		event.preventDefault()
		alert("Definire almeno un lavoratore nella squadra!")
	}
}

function popola_servizi(id_ditta) {
	$("#servizi")
	.find('option')
	.remove()
	.end();	
	if (id_ditta.length==0) {
		return false;
	}
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/popola_servizi",
		data: {_token: CSRF_TOKEN, id_ditta:id_ditta},
		success: function (data) {
			ent=false;
			$.each(JSON.parse(data), function (i, item) {
				$('#servizi').append('<option value="' + item.id_servizio + '">' + item.descrizione + '</option>');
				ent=true
			});
			if (ent==false) {
				alert("Non risultano servizi associati a questa ditta!")
			}

		}
	});
}


