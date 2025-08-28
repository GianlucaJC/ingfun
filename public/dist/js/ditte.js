$(document).ready( function () {
    $('#tbl_list_ditte tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_ditte').DataTable({
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
            zeroRecords: 'Nessuna Ditta trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Ditte',
            infoFiltered: '(Filtrati da _MAX_ Ditte totali)',
        },

		
    });
	
	$('#tipo_pagamento').select2();
	refr=$("#refr").val()
	//serve per far aprire automaticamente la scheda della ditta dopo un //refresh dovuto all'invio di un allegato
	if (refr.length!=0) edit_elem(refr)
	
} );

function set_sezione() {
	
	descr_file=$("#descr_file").val()
	if (descr_file.length==0) {
		alert("Definire correttamente la descrizione da associare al file da inviare!");
		return false
	}
	$("#descr_file" ).prop( "disabled", true );
	$("#div_fx").hide()
	base_path = $("#url").val();
	from="ditte"
	id_cand=$("#edit_elem").val()
	if (id_cand.length==0) return false;

	fetch(base_path+'/class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=refresh_tipo&from='+from+'&id_cand='+id_cand+'&descr_file='+descr_file
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		$("#div_allegati").html(resp);
		$("#div_allegati").show(150);
		set_class_allegati(from,id_cand); //in demo-config.js
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})
}

function check_save() {
	r2=$("#r2").prop("checked");

	if (r2==true) {
		$('#descr_contr').attr('required', true);
		$('#piva').attr('required', true);
		$('#cognome').attr('required', false);
		$('#nome').attr('required', false);
		pec=$("#pec").val()
		sdi=$("#sdi").val()
		if (pec.length==0 && sdi.length==0) {
			event.preventDefault(); 
			alert("Attenzione!\nTrattandosi di Partita IVA è necessario valorizzare anche PEC o SDI");
		}
	} else {
		$('#descr_contr').attr('required', false);
		$('#piva').attr('required', false);
		$('#cognome').attr('required', true);
		$('#nome').attr('required', true);
	}
}

function new_ditta() {
	$("#div_doc").html('')
	$("#div_allega").hide()
	$('#frm_ditte1')[0].reset();	
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


function remove_doc_ditta(nomefile,id_ditta,id_doc) {
	if (!confirm("Sicuri di rimuovere il documento selezionato?"))
		return false;
	
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		
		type: 'POST',
		url: base_path+"/remove_doc_ditta",
		data: {_token: CSRF_TOKEN, nomefile:nomefile,id_ditta:id_ditta,id_doc:id_doc},
		success: function (data) {
			$("#doc"+id_doc).remove()
		}
	})		
	
}
function edit_elem(id_ditta) {
	$("#div_allega").show()
	$('#div_definition').hide()
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	piva="";pf_pi=1
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/getditta",
		data: {_token: CSRF_TOKEN, id_ditta:id_ditta},
		success: function (data) {
			
			info=JSON.parse(data)
			pf_pi=info[0].pf_pi
			piva=info[0].piva
			if (pf_pi==0) $("#r2").prop("checked",true);
			else $("#r1").prop("checked",true);
			$("#cognome").val(info[0].cognome)
			$("#nome").val(info[0].nome)
			$("#descr_contr").val(info[0].denominazione)
			$("#alias").val(info[0].alias)
			$("#comune").val(info[0].comune)
			$("#cap").val(info[0].cap)
			$("#provincia").val(info[0].provincia)
			$("#indirizzo").val(info[0].indirizzo)
			$("#piva").val(info[0].piva)
			$("#cf").val(info[0].cf)
			$("#email").val(info[0].email)
			$("#pec").val(info[0].pec)
			$("#telefono").val(info[0].telefono)
			$("#fax").val(info[0].fax)
			$("#sdi").val(info[0].sdi)
			var selectedValues = new Array();
			
			tipo_pagamento=info[0].tipo_pagamento
			if (tipo_pagamento) values = tipo_pagamento.split(';');
			$("#tipo_pagamento").val('');
			if (tipo_pagamento && tipo_pagamento.length>0)
				$("#tipo_pagamento").val(values).change();

			$("#edit_elem").val(id_ditta)
			$('#div_definition').show(150)
			

			$("#div_doc").html("Verifica documenti associati in corso...")
			$.ajax({
				
				type: 'POST',
				url: base_path+"/get_doc_ditta",
				data: {_token: CSRF_TOKEN, id_ditta:id_ditta},
				success: function (data) {
				
					html="";
					num_elem=0
					html+=`<div class="row mb-3">
							<div class="col-md-12">
							<table class='table' id='tb_doc'>
								<thead>
									<tr>
										
										<th>Nome documento</th>
										<th>Azioni</th>
									</tr>
								</thead>
								<tbody>`
							
								$.each(JSON.parse(data), function (i, item) {
									num_elem++
									id_doc=item.id
									nomefile=item.nomefile
									descr_file=item.descr_file
									html+=`
										<tr id='doc`+id_doc+`'>
											<td>
												<a href="allegati/ditte/`+id_ditta+`/`+nomefile+`" target='_blank'>`+
												descr_file+`</a>
											</td>
												
											<td>


												<a href="javascript:void(0)" onclick="remove_doc_ditta('`+nomefile+`',`+id_ditta+`,`+id_doc+`)">
													<button type='button' class='btn btn-warning btn-sm' ><i class='fas fa-trash' title='rimuovi allegato'></i></button>
												</a>
											</td>

										</tr>
										`
								})
						html+=`</tbody>
							</table>				
						</div>
					</div>`
					if (num_elem==0) html="";
					if (num_elem==0 && pf_pi==0) {
						html=`
							<div class="alert alert-warning" role="alert">
								<b>Attenzione!</b> Trattandosi di una partita iva è obbligatorio allegare un documento
							</div>			
						`		
					}
					else html="<hr>"+html
					$("#div_doc").html(html)
				}
			})			

		}
	});	

			

	
	
	
	
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