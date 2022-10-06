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



function refresh_tipoc() {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: "refresh_tipoc",
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


