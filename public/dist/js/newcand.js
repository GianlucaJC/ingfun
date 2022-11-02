// Example starter JavaScript for disabling form submissions if there are invalid fields
var arr_send = {};
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
		var cf=$("#codfisc").val()
		var valida=validaCodiceFiscale(cf);
		if (valida==false) {
		  $("#codfisc").removeClass('is-valid').addClass('is-invalid');
          event.preventDefault()
          event.stopPropagation()
		} else $("#codfisc").removeClass('is-invalid').addClass('is-valid');
		
        form.classList.add('was-validated')
      }, false)
    })
})()

$(document).ready( function () {
	$('body').addClass("sidebar-collapse");
	$('.select2').select2()

	$("#sub_newcand").click(function(){
		 $('#data_inizio').attr('required', false); 
	});



} );

function popola_sotto_tipo(tipodoc) {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/sottotipo",
		data: {_token: CSRF_TOKEN, tipodoc:tipodoc},
		success: function (data) {
			$("#sotto_tipo_doc")
			.find('option')
			.remove()
			.end();	
			
			$('#sotto_tipo_doc').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#sotto_tipo_doc').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function dele_curr(file_curr,id_cand) {
	if (!confirm("Sicuri di eliminare il Curriculum?")) return false;
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	fetch(base_path+'/dele_curr', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: '_token='+ CSRF_TOKEN+'&id_cand='+id_cand+'&file_curr='+file_curr
	})
	.then(response => {
		if (response.ok) {			
		   return response.text();
		}
		
	})
	.then(resp=>{
		$("#fx_curr").val('')
		$("#div_view_curr").empty(150);

	})
	.catch(status, err => {
		
		return console.log(status, err);
	})	
}

function refresh_tipoc() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_tipoc",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up1").hide(150)
			$("#tipo_contr")
			.find('option')
			.remove()
			.end();	
			
			$('#tipo_contr').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#tipo_contr').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function refresh_soc() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_soc",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up3").hide(150)
			$("#soc_ass")
			.find('option')
			.remove()
			.end();	
			
			$('#soc_ass').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#soc_ass').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}
function refresh_area() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_area",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up5").hide(150)
			$("#area_impiego")
			.find('option')
			.remove()
			.end();	
			
			$('#area_impiego').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#area_impiego').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function refresh_costo() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_costo",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up4").hide(150)
			$("#centro_costo")
			.find('option')
			.remove()
			.end();	
			
			$('#centro_costo').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#centro_costo').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function refresh_mansione() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_mansione",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up6").hide(150)
			$("#mansione")
			.find('option')
			.remove()
			.end();	
			
			$('#mansione').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#mansione').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}
function refresh_sotto_tipo_doc(tipo_doc) {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_sotto_tipo_doc",
		data: {_token: CSRF_TOKEN,tipo_doc:tipo_doc},
		success: function (data) {
			$("#div_upl2").hide(150)
			$("#sotto_tipo_doc")
			.find('option')
			.remove()
			.end();	
			
			$('#sotto_tipo_doc').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#sotto_tipo_doc').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function refresh_tipo_doc() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_tipo_doc",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_upl1").hide(150)
			$("#tipo_doc")
			.find('option')
			.remove()
			.end();	
			
			$('#tipo_doc').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#tipo_doc').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}
function refresh_ccnl() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_ccnl",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up7").hide(150)
			$("#contratto")
			.find('option')
			.remove()
			.end();	
			
			$('#contratto').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#contratto').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function refresh_tipologia_contr() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_tipologia_contr",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up8").hide(150)
			$("#tipologia_contr")
			.find('option')
			.remove()
			.end();	
			
			$('#tipologia_contr').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#tipologia_contr').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}


function validaCodiceFiscale(cf){
          var validi, i, s, set1, set2, setpari, setdisp;
          if( cf == '' )  return '';
          cf = cf.toUpperCase();
          if( cf.length != 16 )
              return false;
          validi = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          for( i = 0; i < 16; i++ ){
              if( validi.indexOf( cf.charAt(i) ) == -1 )
                  return false;
          }
          set1 = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          set2 = "ABCDEFGHIJABCDEFGHIJKLMNOPQRSTUVWXYZ";
          setpari = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
          setdisp = "BAKPLCQDREVOSFTGUHMINJWZYX";
          s = 0;
          for( i = 1; i <= 13; i += 2 )
              s += setpari.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
          for( i = 0; i <= 14; i += 2 )
              s += setdisp.indexOf( set2.charAt( set1.indexOf( cf.charAt(i) )));
          if( s%26 != cf.charCodeAt(15)-'A'.charCodeAt(0) )
              return false;
          return true;
}

function popola_province(id_regione) {
$("#provincia")
    .find('option')
    .remove()
    .end();	
$("#comune")
    .find('option')
    .remove()
    .end();		
$("#cap").val('');	
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
		
	let CSRF_TOKEN = $("#token_csrf").val();

	$.ajax({
		type: 'POST',
		url: "lista_province",
		data: {id_regione: id_regione, _token: CSRF_TOKEN},
		success: function (data) {
			let result_tag = "";
			$('#provincia').append('<option value="">Select...</option>');
			  $.each(JSON.parse(data), function (i, item) {
				$('#provincia').append('<option value="' + item.sigla + '">' + item.provincia + '</option>');
			 });	
			
		}
	});
}

function popola_comuni(sigla,comune_search) {
if (comune_search=="0") {
	$("#comune")
		.find('option')
		.remove()
		.end();	
	$("#cap").val('');
}

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: "lista_comuni",
		data: {sigla: sigla, comune_search:comune_search, _token: CSRF_TOKEN},
		success: function (data) {
			if (comune_search=="0") {
				let result_tag = "";
				$('#comune').append('<option value="">Select...</option>');
				  $.each(JSON.parse(data), function (i, item) {
					$('#comune').append('<option value="' + item.istat + '">' + item.comune + '</option>');
				 });
			}
			
		}
	});	
}

function popola_cap_pro(value) {
	$("#cap").val('');$("#provincia").val('')
	$("#cap").val(value.split("|")[0])
	$("#provincia").val(value.split("|")[1])
}
function popola_pronasc(value) {
	$("#pro_nasc").val('')
	$("#pro_nasc").val(value.split("|")[1])
}


function popola_cap(istat) {
$("#cap").val('');

	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: "lista_cap",
		data: {istat: istat, _token: CSRF_TOKEN},
		success: function (data) {
			rec=JSON.parse(data)
			if (rec.length>0) $("#cap").val(rec[0].cap)
			
		}
	});	
}

function set_sezione(from,id_cand) {
base_path = $("#url").val();



	if ($("#body_dialog").is(":visible")) {
		$("#body_dialog").hide(150);
		return false;
	}
	if (from=="2") {
		tipo_doc=$("#tipo_doc").val();
		sotto_tipo_doc=$("#sotto_tipo_doc").val();
		scadenza=$("#scadenza").val();
		if (tipo_doc.length==0) {
			alert("Il Tipo Documento è obbligatorio")
			return false;
		}

		$('html, body').animate({
			scrollTop: $("#div_allega").offset().top
		}, 1500);		
	}	
	
	$(".allegati").empty();
	fetch(base_path+'/class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=refresh_tipo&from='+from+'&id_cand='+id_cand
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		$("#body_dialog").html(resp);
		$("#body_dialog").show(150);
		set_class_allegati(from,id_cand); //in demo-config.js
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})
}


function storia(id_campo,id_cand) {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$("#altri_btn").html('')
	$("#title_modal").html("Informazioni storiche sul campo")
	$('#modal_story').modal('toggle')
	$("#body_modal").html("Caricamento informazioni in corso...")			
	
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/storia_campo",
		data: {id_campo: id_campo, id_cand:id_cand, _token: CSRF_TOKEN},
		success: function (data) {
			html="";
			html+="<table class='display' id='tbl_story'>";
				html+="<thead>";
					html+="<tr>";
						html+="<th>Creato il</th>";
						html+="<th>Descrizione</th>";

					html+="</tr>";
				html+="</thead>";	
				html+="<tbody>";
					json=JSON.parse(data);
					candidato=json["candidato"][0]
					story=json["story"]
					$.each(story, function (i, item) {
						
						html+="<tr>";
							html+="<td>";
								html+=item.created_at
							html+="</td>";
							html+="<td>";
								html+=item.value
							html+="</td>";
						html+="</tr>";
					});
				html+="</tbody>";
			html+="</table>";	
							  
			$("#body_modal").html(html)	
			init_table();
			
		}
	});
}

function init_table() {
	
    $('#tbl_story').DataTable({
		dom: 'Bfrtip',
		buttons: [
			'excel', 'pdf',
		],		
        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessuna voce trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono disponibili voci storiche',
            infoFiltered: '(Filtrati da _MAX_ voci totali)',
        },
    });		
}

function assunzione() {
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
	$( "#btn_ass" ).prop( "disabled", true );
	

	id_cand=$("#id_cand").val();
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/assunzione",
		data: {_token: CSRF_TOKEN, id_cand:id_cand},
		success: function (data) {
			console.log(data);
			item=JSON.parse(data)
			//elimino tasto di inoltra candidatura perchè ora è assunto
			$("#btn_inoltra").hide(150);
			//tendina assunzione impostata su assunzione
			$("#status_candidatura")
			.find('option')
			.remove()
			.end();	
			$('#status_candidatura').append('<option value="3">ASSUNZIONE</option>');
			$("#status_candidatura" ).prop( "disabled", true );
			
			send_email();
			
		}
	});	

}

function prepara_mail() {

	if ($("#data_inizio").val().length==0) {
		 alert("Valorizzare la data assunzione");
		 return false;
	}
	candidato=$("#cognome").val()
	candidato+=" "+$("#nome").val()
	oggetto="Assunzione relativa al candidato "+candidato
	body_msg="Con la presente si comunica che il candidato "+candidato+" è stato assunto";
	
	$("#title_modal").html("Scelta dei contatti ai quali inoltrare la notifica")
	$('#modal_story').modal('toggle')
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
						html+="<input type='text' class='form-control'  aria-label='oggetto' name='oggetto' id='oggetto' value=\""+oggetto+"\">";
					html+="<label for='altre'>Oggetto</label>";
					html+="</div>";
				html+="</div>";
			html+="</div>";

			html+="<div class='row mt-2'>";
				html+="<div class='col-sm-12'>";
					html+="<div class='form-floating'>";					
					html+="<textarea class='form-control' id='body_msg' name='body_msg' rows='6' style='height:100px'>"+body_msg+"</textarea>";
					html+="<label for='body_msg'>Corpo del messaggio</label>";
					html+="</div>";
				html+="</div>";
			html+="</div>";
			
			$("#body_modal").html(html)
			
			html="<button type='button' class='btn btn-primary' id='btn_ass' onclick='assunzione()'>Inoltra candidatura ed invia notifica</button>"
			$("#altri_btn").html(html)
			
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
		send_real(arr_send[0]['id_ref'],arr_send[0]['mail'],num_elem,0)

}
function send_real(id_ref,email,num_elem,num) {
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
		data: {_token: CSRF_TOKEN, id_cand:id_cand, titolo:oggetto, email:email, body_msg:body_msg},
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
	alert("Inoltro candidatura e notifica mail effettuati!");
}					

