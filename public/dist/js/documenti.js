$(document).ready( function () {
    $('#tbl_list_documenti tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_documenti').DataTable({
		"order": [[ 0, 'desc' ]],
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
            zeroRecords: 'Nessun Documento trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti documenti',
            infoFiltered: '(Filtrati da _MAX_ Documenti totali)',
        },

		
    });
	
} );

function new_doc() {
	id_cand=$("#id_cand").val()
	if (id_cand.length==0) {
		alert("Definire un Lavoratore!");
		return false;
	}
	$('#div_new_doc').toggle(150);	
}

function prepara_mail(id_cand,nome_file) {
	$("#title_modal").html("Scelta dei contatti ai quali inoltrare il documento")
	$('#modal_win').modal('toggle')
	$("#body_modal").html("Caricamento informazioni in corso...")

	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/load_contatti",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			item=JSON.parse(data)
			html="";
			html+="<input type='hidden' id='cand_ref' value='"+id_cand+"'>";
			html+="<input type='hidden' id='file_ref' value='"+nome_file+"'>";
			html+="<div style='max-height:300px;overflow-y:scroll'  >";
				html+="<ul class='list-group'>";

					$.each(JSON.parse(data), function (i, item) {
						
						html+="<li class='list-group-item'>";
							html+="<input class='form-check-input me-1 mailsend' type='checkbox' value='' id='mailsend"+item.id+"' data-send='"+item.mail+"'>";
							html+="<label class='form-check-label stretched-link' for='mailsend"+item.id+"' title='"+item.mail+"'>"+item.descrizione+"</label>";
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
			
			$("#body_modal").html(html)

		}
	});
	
	
	return false;
}

function send_email() {
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
			send_real(id_ref,mail)
		}
		
	}
	$( ".mailsend" ).each(function() {
			value=$( this ).prop( "checked" );
		    id_ref_origin=(this.id)
			id_ref=id_ref_origin.substr(8);
			mail=$("#"+id_ref_origin).data("send")
			if (value==true) {
				$("#sendm"+id_ref).show(50);
				send_real(id_ref,mail)
				console.log(id_ref,mail,value)
			}
	});		
	
}
function send_real(id_ref,email) {
	id_cand=$("#cand_ref").val();
	nome_file=$("#file_ref").val();
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
		data: {_token: CSRF_TOKEN, id_cand:id_cand, nome_file:nome_file, titolo:oggetto, email:email, body_msg:body_msg},
		success: function (data) {
			console.log(data);
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
			console.log(item.status)
		}
	});	

}


function set_sezione(id_cand) {
base_path = $("#url").val();
	
	$(".allegati").empty();
	fetch(base_path+'/class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=refresh_tipo'
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		$("#body_dialog").html(resp);
		$("#body_dialog").show(150);
		set_class_allegati(id_cand); //in demo-config.js
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})
}


function edit_elem(id_elem) {
	descrizione=$("#id_descr"+id_elem).data("descr")
	$("#descr_contr").val(descrizione)
	$("#edit_elem").val(id_elem)
	$('#div_definition').show(150)
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