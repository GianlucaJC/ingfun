// Example starter JavaScript for disabling form submissions if there are invalid fields

(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
		$("#targa").prop("disabled",false);
		id_mezzo=$("#id_mezzo").val()
		$("#div_sub_noleggio :input").attr("disabled", false);

        if (!form.checkValidity()) {
			event.preventDefault()
			event.stopPropagation()
			if (id_mezzo!="0" && id_mezzo.length>0) {
				$("#targa").prop("disabled",true);
				$("#div_sub_noleggio :input").attr("disabled", true);
			}
		}
		/*
		var cf=$("#codfisc").val()
		var valida=validaCodiceFiscale(cf);
		if (valida==false) {
		  $("#codfisc").removeClass('is-valid').addClass('is-invalid');
          event.preventDefault()
          event.stopPropagation()
		} else $("#codfisc").removeClass('is-invalid').addClass('is-valid');
		*/
        form.classList.add('was-validated')
      }, false)
    })
})()

$(document).ready( function () {
	$('body').addClass("sidebar-collapse");
	$('.select2').select2()
	proprieta=$("#proprieta").val()
	check_noleggio(proprieta)
	id_mezzo=$("#id_mezzo").val()
	if (id_mezzo!="0" && id_mezzo.length>0) 
		$("#div_sub_noleggio :input").attr("disabled", true);
} );




function check_noleggio(value) {
	$("#div_noleggio").hide();
	$('#da_data_n').attr('required', false); 
	$('#tipo_durata_noleggio').attr('required', false);
	$('#durata_noleggio').attr('required', false);
	
	$('#importo_noleggio').attr('required', false); 
	$('#km_noleggio').attr('required', false); 
	if (value==1) {
		$("#div_noleggio").show(150);
		$('#tipo_durata_noleggio').attr('required', true); 
		$('#durata_noleggio').attr('required', true);
		
		$('#km_noleggio').attr('required', true); 
		$('#importo_noleggio').attr('required', true); 
	}	
}

function popola_modelli(id_marca) {
	$("#modello")
	.find('option')
	.remove()
	.end();	
	if (id_marca.length==0) {
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
		url: base_path+"/popola_modelli",
		data: {_token: CSRF_TOKEN, id_marca:id_marca},
		success: function (data) {
			ent=false;
			
			$('#modello').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				$('#modello').append('<option value="' + item.id_modello + '">' + item.modello + '</option>');
				ent=true
			});
			if (ent==false) {
				alert("Non risultano modelli associati a questa marca!")
			}

		}
	});
}

function refresh_servizi_noleggio() {
	old_value=$("#servizi_noleggio").val();
	$("#servizi_noleggio")
	.find('option')
	.remove()
	.end();	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_servizi_noleggio",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_servizi").hide(150)
			$("#servizi_noleggio")
			.find('option')
			.remove()
			.end();	
			
			$('#servizi_noleggio').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#servizi_noleggio').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
			$("#servizi_noleggio").val(old_value)
		}
	});		
}


function refresh_marca() {
	$("#modello")
	.find('option')
	.remove()
	.end();	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_marca",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_marca").hide(150)
			$("#marca")
			.find('option')
			.remove()
			.end();	
			
			$('#marca').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#marca').append('<option value="' + item.id + '">' + item.marca + '</option>');
						
			});
		}
	});		
}

function refresh_carta() {
	$("#carta_carburante")
	.find('option')
	.remove()
	.end();	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_carta",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_carta").hide(150)
			$("#carta_carburante")
			.find('option')
			.remove()
			.end();	
			
			$('#carta_carburante').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#carta_carburante').append('<option value="' + item.id + '">' + item.id_carta + '</option>');
						
			});
		}
	});		
}

function refresh_badge() {
	$("#badge_cisterna")
	.find('option')
	.remove()
	.end();	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_badge",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_badge").hide(150)
			$("#badge_cisterna")
			.find('option')
			.remove()
			.end();	
			
			$('#badge_cisterna').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#badge_cisterna').append('<option value="' + item.id + '">' + item.id_badge + '</option>');
						
			});
		}
	});		
}

function refresh_telepass() {
	$("#telepass")
	.find('option')
	.remove()
	.end();	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_telepass",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_telepass").hide(150)
			$("#telepass")
			.find('option')
			.remove()
			.end();	
			
			$('#telepass').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#telepass').append('<option value="' + item.id + '">' + item.id_telepass + '</option>');
						
			});
		}
	});		
}
function refresh_modello() {
	$("#marca").trigger("change");
	$("#div_up_modello").hide(150)
}