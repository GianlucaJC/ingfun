$(document).ready( function () {
    $('#tbl_list_preventivi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0) {
			style="";
			if (title=="ID") style='style="max-width:30px;"'
			if (title=="Data") style='style="max-width:120px;"'
			$(this).html('<input '+style+' type="text" placeholder="' + title + '" />');
		}
    });	
    var table=$('#tbl_list_preventivi').DataTable({
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
            zeroRecords: 'Nessun preventivo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti preventivi',
            infoFiltered: '(Filtrati da _MAX_ preventivi totali)',
        },

		
    });
	
} );
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
$(document).on('submit','#needs-validation2a', function(){
	form_val="needs-validation2a"
	valida(form_val)
})	


function refresh_aliquota() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_aliquota",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#aliquota")
			.find('option')
			.remove()
			.end();	
			
			$('#aliquota').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#aliquota').append('<option value="' + item.id + '|'+item.aliquota+'">' + item.aliquota+'% - '+item.descrizione + '</option>');
						
			});
		}
	});		
}

function set_table() {

    var t1=$('#tbl_list_appalti').DataTable({
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

        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun articolo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Articoli',
            infoFiltered: '(Filtrati da _MAX_ articoli totali)',
        },
    });	
	
	
}

function set_service(value) {
	info=value.split("|");
	
	if (info.length>2) {
		id_servizio=info[0]
		descrizione=info[1]
		importo_ditta=info[2]
		aliquota=info[3]
		aliquota_v=info[4]
		aliq=aliquota+"|"+aliquota_v
		
		$("#prodotto").val(descrizione)
		$("#prezzo_unitario").val(importo_ditta)
		$("#aliquota").val(aliq)
	} else {
		$("#prodotto").val('')
		$("#prezzo_unitario").val('')
		$("#aliquota").val('')
	}
	calcolo_riga()
	
}

function calcolo_riga() {
	quantita=$("#quantita").val()
	prezzo_unitario=$("#prezzo_unitario").val()
	infoaliquota=$("#aliquota").val()
	aliquota=infoaliquota.split("|")[1]/100
	if (quantita && prezzo_unitario) {
		subtotale=quantita*prezzo_unitario
		if (aliquota && aliquota!=0) {
			aliquota=aliquota+1
			subtotale=subtotale*aliquota
		}
		subtotale=subtotale.toFixed(2)
		$("#subtotale").val(subtotale)
	}
}

function prepare_to_send(id_fattura) {
	$('#modal_fatt').modal('toggle')
	$("#title_modal_fatt").html("Invia fattura a destinatari")
	$("#body_modal_fatt").html("Caricamento informazioni in corso...")
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/load_contatti_soc",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			item=JSON.parse(data)
			html="";
			html+="<div style='max-height:300px;overflow-y:scroll'  >";
				html+="<ul class='list-group'>";

					$.each(JSON.parse(data), function (i, item) {
						
						html+="<li class='list-group-item'>";
							html+="<input class='form-check-input me-1 mailsend' type='checkbox' value='' id='mailsend"+item.id+"' data-send='"+item.mail_fatture+"'>";
							html+="<label class='form-check-label stretched-link' for='mailsend"+item.id+"' title='"+item.mail_fatture+"'>"+item.descrizione+"("+item.mail_fatture+")</label>";
							html+="<span style='display:none' id='sendm"+item.id+"' class='ml-3'><font color='red'>Invio in corso...</font></span>"
						html+="</li>";
						
					});
				html+="</ul>";
			html+="</div>";

			html+="<div class='row mt-2'>";
				html+="<div class='col-sm-12'>";
					html+="<div class='form-floating'>";					
						html+="<input type='text' class='form-control' placeholder='Email' aria-label='Email' name='altre' id='altre'>";
					html+="<label for='altre'>Eventuali altre email separate da;</label>";
					html+="</div>";
					html+="<span style='display:none' id='sendam' class='ml-3'><font color='red'>Invio in corso...</font></span>"
				html+="</div>";
			html+="</div>";


			html+="<hr><div class='row mt-2'>";
				html+="<div class='col-sm-12'>";
					html+="<div class='form-floating'>";					
						html+="<input type='text' class='form-control'  aria-label='oggetto' name='oggetto' id='oggetto'>";
					html+="<label for='altre'>Oggetto</label>";
					html+="</div>";
				html+="</div>";
			html+="</div>";

			html+="<div class='row mt-2'>";
				html+="<div class='col-sm-12'>";
					html+="<div class='form-floating'>";					
					html+="<textarea class='form-control' id='body_msg' name='body_msg' rows='6' style='height:100px'></textarea>";
					html+="<label for='body_msg'>Corpo del messaggio</label>";
					html+="</div>";
				html+="</div>";
			html+="</div>";
			
			$("#body_modal_fatt").html(html)
			
			
			testo="Invia Fattura";

			html="<button type='button' class='btn btn-primary' id='btn_send_fatt' onclick='send_email("+id_fattura+")'>"+testo+"</button>"
			$("#altri_btn_fatt").html(html)
			
		}
	});
		
	
}

function send_email(id_fattura) {
	sel=false;
	arr_send = {};
	$( ".mailsend" ).each(function() {
		value=$( this ).prop( "checked" );
		if (value==true) {
			sel=true
		}
	});	
	
	if (sel==false) {
		alert("Selezionare almeno un destinatario per la notifica!")
		return false;
	}	
	
	oggetto=$("#oggetto").val();
	body_msg=$("#body_msg").val();
	if (oggetto.length==0) {
		alert("Definire l'oggetto!");
		return false;
	}
	if (body_msg.length==0) {
		alert("Definire il corpo del messaggio!");
		return false;
	}

	altre=$("#altre").val();
	if (altre.length!=0) {
		arr_altri=altre.split(";")
		id_ref="altre";
		$("#sendam").show(50);
		for (sca=0;sca<=arr_altri.length-1;sca++) {						
			mail=arr_altri[sca]
			send_real(id_ref,mail,-1,-1)
		}
		
	}
	
	
	num_elem=0

	fl_send=false
	$( ".mailsend" ).each(function() {		
		value=$( this ).prop( "checked" );
		id_ref_origin=(this.id)
		id_ref=id_ref_origin.substr(8);
		mail=$("#"+id_ref_origin).data("send")
		
		if (value==true) {
			fl_send=true
			arr_send[num_elem] = {};
			arr_send[num_elem]['id_ref'] = id_ref;
			arr_send[num_elem]['mail'] = mail
			num_elem++
		}
	})
	//invia la prima notifica, le altre in callback nella chiamata ajax
	if (fl_send==true) 
		send_real(arr_send[0]['id_ref'],arr_send[0]['mail'],num_elem,0,id_fattura)

}
function send_real(id_ref,email,num_elem,num,id_fattura) {
	html=""
	html+="<div class='spinner-border spinner-border-sm' role='status'>";
		html+="<span class='sr-only'>Loading...</span>";
	html+="</div>";
	if (id_ref=="altre") {
		$("#sendam").html(html)
		$("#sendam").hide(10);
		$("#sendam").show(40);
	}
	else {
		$("#sendm"+id_ref).html(html)
		$("#sendm"+id_ref).show(50);
	}	
	
	id_cand=$("#id_cand").val();
	
	oggetto=$("#oggetto").val();
	body_msg=$("#body_msg").val();
	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/send_mail",
		data: {_token: CSRF_TOKEN, id_cand:id_cand, titolo:oggetto, email:email, body_msg:body_msg,id_fattura:id_fattura},
		success: function (data) {
			
			html="<font color='green'><i class='fa fa-thumbs-up'></i></font>";
			if (id_ref=="altre") {
				$("#sendam").html(html)
				$("#sendam").hide(10);
				$("#sendam").show(40);
			}
			else {
				$("#sendm"+id_ref).html(html)
				$("#sendm"+id_ref).show(50);
			}	
			
			item=JSON.parse(data)
			if (num>=0) {
				new_num=num+1
				
				if (new_num<num_elem) {					
					send_real(arr_send[new_num]['id_ref'],arr_send[new_num]['mail'],num_elem,new_num)
				} else {
					$("#btn_ass").hide(150)
					//ultimo alert tramite setTimeout altrimenti non viene renderizzato l'ultimo check della notifica
					timeout = setTimeout(alertFunc, 1000);
					
				}
			}
				
		}
	});	

}

function alertFunc() {
	alert("Operazione effettuata!");
}

function refresh_servizi() {
	base_path = $("#url").val();
	ditta = $("#ditta").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_servizi",
		data: {_token: CSRF_TOKEN,ditta:ditta},
		success: function (data) {
			$("#service")
			.find('option')
			.remove()
			.end();	
			
			$('#service').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {

			js=`
				<option value="`+item.id_servizio+`|`+item.descrizione+`|`+item.importo_ditta+`|`+item.aliquota+`|`+item.aliquota_v+`">`+item.descrizione +`</option>
			`

			$('#service').append(js);
						
			});
		}
	});		
}

function edit_product(id_riga,last_ordine,id_servizio) {
	
	$(".tipoins").hide()
	$('#prodotto').attr('required', false); 
	$('#service').attr('required', false); 
	if (id_servizio=== undefined || id_servizio=="0") {
		$('#prodotto').attr('required', true);
		$("#div_product").show();
	}
	else {
		$('#service').attr('required', true);
		$("#div_service").show();
	}	
	save_art.id_riga=id_riga
	$("#title_modal").html("Inserimento/Modifica dati riga fattura")
	
	$('#modal_story').modal('toggle')
	//$("#body_modal").html("Caricamento informazioni in corso...")		
	
	if (last_ordine==0) 
		ordine=$("#inforow"+id_riga).data("ordine")
	else
		ordine=last_ordine
	
	if (id_riga!=0) {
		codice=$("#inforow"+id_riga).data("codice")
		descrizione=$("#inforow"+id_riga).data("descrizione")
		quantita=$("#inforow"+id_riga).data("quantita")
		um=$("#inforow"+id_riga).data("um")
		prezzo_unitario=$("#inforow"+id_riga).data("prezzo_unitario")
		prezzo_unitario=prezzo_unitario.toFixed(2)
		
		subtotale=$("#inforow"+id_riga).data("subtotale")
		subtotale=subtotale.toFixed(2)
		aliquota=$("#inforow"+id_riga).data("aliquota")

		$("#codice").val(codice)
		$("#prodotto").val(descrizione)
		$("#quantita").val(quantita)
		$("#um").val(um)
		$("#prezzo_unitario").val(prezzo_unitario)
		$("#subtotale").val(subtotale)
		$("#aliquota").val(aliquota)
		
		
	}
	else
		$("#frm_modal")[0].reset()
	
	$("#ordine").val(ordine)

}

function save_art() {
	var forms = document.getElementsByClassName('needs-validation4');
	var validation = Array.prototype.filter.call(forms, function(form) {
		if (form.checkValidity() === false) {
		  event.preventDefault();
		  event.stopPropagation();
		} else {
			if (save_art.id_riga !== undefined) {
				$("#edit_riga").val(save_art.id_riga)
				/*
				codice=$("#codice").val()
				prodotto=$("#prodotto").val()
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
					data: {_token: CSRF_TOKEN, id_riga:save_art.id_riga,codice:codice},
					success: function (data) {
						$.each(JSON.parse(data), function (i, item) {

						});
					}
				});
*/				
				
			}

			
		}	
		form.classList.add('was-validated');
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
			 html+="Controllare i campi evidenziati in rosso";
		  html+="</div>";
		  
		  $("#div_alert").html(html)
		} else {
		
			if (form_val=="needs-validation2" || form_val=="needs-validation2a") {
				if (form_val=="needs-validation2a") {
					/*
					num_pag=0
					$( ".importi" ).each(function() {
						num_pag++
					})
					if (num_pag==0) {
						event.preventDefault();
						event.stopPropagation();
						alert("Aggiungere almeno una modalità di pagamento!")
					}
					*/
						
				}
			} else {
				event.preventDefault();
				event.stopPropagation();
				
			}
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

function import_prev() {
	$('#modal_prev').modal('toggle')
}

function metodo_ins(value) {
		
	$('.metodi').hide(150);
	if (value==1) {
		edit_product(0,0,0)
		$("#div_lista_articoli").show(100)
		return false;
	}

	
	if (value=="2") $('#div_from_appalti').show(150)
	else if (value=="3") edit_product(0,0,1)
	else if (value=="4") import_prev()
	else if (value=="5")
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


function add_pagamento(tipo) {
	if (add_pagamento.id_group === undefined) add_pagamento.id_group=1000
	add_pagamento.id_group=add_pagamento.id_group+1
	console.log(add_pagamento.id_group)
	html="";
	js="if (!confirm('Sicuri di eliminare il tipo di pagamento?')) return false; else $('#div_p"+add_pagamento.id_group+"').remove()"; 
	btn_dele=`<a href='#' onclick="`+js+`">`
	btn_dele+="<i class='fas fa-trash-alt'></i>"
	btn_dele+="</a>";
	color="";
	disp1="display:none";disp2="display:none";
	req1="";req2="";
	if (tipo=="1") {
		disp1="";
		req1="required";
		descr="Contanti"
		color="primary";
	}
	if (tipo=="2") {
		descr="Bancomat"
		color="secondary";
	}
	if (tipo=="3") {
		descr="Assegno"
		color="info";
	}
	if (tipo=="4") {
		req2="required";
		disp2="";
		descr="Bonifico"
		color="warning";
	}

	html+=`
		<div class='border border-`+color+` p-2 mb-1' id='div_p`+add_pagamento.id_group+`'>
			<input type='hidden' name='tipo_pagamento[]' value='`+tipo+`'>
			<div class='alert alert-`+color+`' role="alert">
				`+btn_dele+` `+descr+`		
			</div>

			<div class='row mb-3'>
				<div class="col-md-4">
					
					<div class="form-floating">
						<input class="form-control dp" 
						name="data_scadenza[]" type="date" required />
						<label for="data_pagamento">Data scadenza*</label>
					</div>
					
				</div>

				<div class="col-md-4">
					<div class="form-floating">
						<input class="form-control importi" name="importo[]" type="text" placeholder="Importo" required />
						<label for="importo" >Importo*</label>
					</div>		
				</div>
		
		
			<div class="col-md-4" style='`+disp1+`'>
				<div class="form-floating">
					<input class="form-control" name="persona[]" type="text" placeholder="" `+req1+` />
					<label for="persona" >Persona che riscuote*</label>
				</div>		
			</div>
		
			<div class="col-md-4" style='`+disp2+`'>
				<div class="form-floating">
					<input class="form-control" name="coordinate[]" type="text" placeholder="" `+req2+` />
					<label for="Coordinate" >Coordinate bancarie*</label>
				</div>		
			</div>
		

		
			</div>
		</div>`
	$("#div_pagamenti").append(html)
}

