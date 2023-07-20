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
} );



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

function refresh_modello() {
	$("#marca").trigger("change");
	$("#div_up_modello").hide(150)
}