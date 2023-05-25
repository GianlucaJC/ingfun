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
	$('#lavoratori').select2().attr('required');

	$("#sub_newcand").click(function(){
		 
	});
} );

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
			$.each(JSON.parse(data), function (i, item) {
				
				$('#servizi').append('<option value="' + item.id_servizio + '">' + item.descrizione + '</option>');
						
			});

		}
	});}


