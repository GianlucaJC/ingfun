function azione(tipo) {
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
		url: base_path+"/azione",
		data: {_token: CSRF_TOKEN, id_cand:id_cand,tipo:tipo},
		success: function (data) {
			console.log(data);
			item=JSON.parse(data)
			//elimino i tasti di azione

			if (tipo!="2") {
				$("#btn_inoltra").hide(150);
				$("#btn_dim").hide(150);
				$("#btn_lic").hide(150);
				$("#btn_scad").hide(150);
				//tendina assunzione impostata su assunzione
				$("#status_candidatura" ).prop( "disabled", true );
				$("#status_candidatura")
				.find('option')
				.remove()
				.end();	
			}
			
			if (tipo=="3")
				$('#status_candidatura').append('<option value="3">ASSUNZIONE</option>');
			if (tipo=="4")
				$('#status_candidatura').append('<option value="4">DIMISSIONI</option>');
			if (tipo=="5")
				$('#status_candidatura').append('<option value="5">LICENZIAMENTO</option>');
			if (tipo=="6")
				$('#status_candidatura').append('<option value="5">SCADENZA NATURALE</option>');			
			
			send_email();
			
		}
	});	

}

function prepara_mail(tipo) {
	if (tipo=="2" || tipo=="3") {
		btn="btn_comunica"
		if (tipo=="2") btn="btn_inoltra"
		if ($("#soc_ass").val().length==0) {
 			$( "#"+btn ).prop( "disabled", true );			
			$( "#"+btn ).text("Valorizzare la società di assunzione e salvare")
			 return false;
		}
		if ($("#data_inizio").val().length==0) {
 			$( "#"+btn ).prop( "disabled", true );			
			$( "#"+btn ).text("Valorizzare la data assunzione e salvare")
			 return false;
		}
	}
	if (tipo=="4" || tipo=="5" || tipo=="6") {
		if ($("#data_fine").val().length==0) {
			if (tipo=="4") {
				$( "#btn_dim" ).prop( "disabled", true );			
				$( "#btn_dim" ).text("Valorizzare la data fine e salvare")
			}
			if (tipo=="5") {
				$( "#btn_lic" ).prop( "disabled", true );			
				$( "#btn_lic" ).text("Valorizzare la data fine e salvare")
			}
			if (tipo=="6") {
				$( "#btn_scad" ).prop( "disabled", true );			
				$( "#btn_scad" ).text("Valorizzare la data fine e salvare")
			}

			 return false;
		}
	}

	candidato=$("#cognome").val()
	candidato+=" "+$("#nome").val()

	data_inizio=$("#data_inizio").val()
	data_fine=$("#data_fine").val()
	mansione=$("#mansione option:selected" ).text();
	mans_val=$("#mansione" ).val();
	soc_ass=$("#soc_ass option:selected" ).text();
	
	data_ass="--";data_f="--";
	email_lav=$("#email").val()
	telefono=$("#telefono").val()
	affiancamento=$("#affiancamento").val()
	zona_lavoro=$("#zona_lavoro").val()
	
	if (data_inizio.length>=10)
		data_ass=data_inizio.substr(8,2)+"-"+data_inizio.substr(5,2)+"-"+data_inizio.substr(0,4)
	if (data_fine.length>=10)
		data_f=data_fine.substr(8,2)+"-"+data_fine.substr(5,2)+"-"+data_fine.substr(0,4)	
	
	oggetto="";
	if (tipo=="2" || tipo=="3")
		oggetto="Inizio attività lavorativa nuova risorsa"
	if (tipo=="4")
		oggetto="Dimissioni attività lavorativa risorsa"
	if (tipo=="5")
		oggetto="Licenziamento attività lavorativa risorsa"
	if (tipo=="6")
		oggetto="Scadenza naturale attività lavorativa risorsa"
	
	if (tipo=="2" || tipo=="3") {
		body_msg="Ti informiamo che il giorno "+data_ass;
		body_msg+=" sarà inserita presso l'azienda "+soc_ass+" una nuova risorsa "+candidato 
		body_msg+=" (Mail:"+email_lav+" Tel: "+telefono+") "
		if (mans_val.length!=0) body_msg+=" con mansione di: "+mansione+"."; 
		else body_msg+=".";
		if (affiancamento.length!=0) body_msg+=" E' previsto affiancamento con "+affiancamento+".";
		if (zona_lavoro.length!=0) body_msg+=" La risorsa lavora nella zona di "+zona_lavoro+""
	}	
	if (tipo=="4") {
		body_msg="Ti informiamo che il giorno "+data_f;
		body_msg+=" la risorsa "+candidato+" ha presentato le dimissioni";
	}	
	if (tipo=="5") {
		body_msg="Ti informiamo che il giorno "+data_f;
		body_msg+=" la risorsa "+candidato+" è stata licenziata";
	}	
	if (tipo=="6") {
		body_msg="Ti informiamo che dal giorno "+data_f;
		body_msg+=" la risorsa "+candidato+" non sarà più in organico";		
	}		
	
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
			
			testo="";
			if (tipo=="2") testo="Invia notifica di assunzione";
			if (tipo=="3") testo="Inoltra candidatura ed invia notifica";
			if (tipo=="4") testo="Inoltra dimissioni ed invia notifica";
			if (tipo=="5") testo="Inoltra licenziamento ed invia notifica";
			if (tipo=="6") testo="Inoltra scadenza naturale ed invia notifica";
			html="<button type='button' class='btn btn-primary' id='btn_ass' onclick='azione("+tipo+")'>"+testo+"</button>"
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
	alert("Operazione effettuata!");
}					
