// Example starter JavaScript for disabling form submissions if there are invalid fields
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
} );


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
			$("#tipo_contratto")
			.find('option')
			.remove()
			.end();	
			
			$('#tipo_contratto').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#tipo_contratto').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
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

function set_sezione() {
base_path = $("#url").val();


	if ($("#body_dialog").is(":visible")) {
		$("#body_dialog").hide(150);
		return false;
	}
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
		set_class_allegati("",""); //in demo-config.js
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})
}
