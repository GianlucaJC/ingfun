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
        } else {
			$("#btn_save").html('Attendere...');
			$('#btn_save').prop('readonly', true);
			
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


} );

function def_sinistro(id_appalto) {
	if (id_appalto.length==0) return false;
	base_path = $("#url").val();
	url=base_path+"/sinistri/"+id_appalto+"/1/0";
	window.location = url
}

function save() {
	
	if (!confirm("Sicuri di confermare il sinistro?"))
		event.preventDefault()
}

function init_allegati(id_sinistro) {
	//function set_class_allegati() in demo-config.js
	set_class_allegati.id_sinistro=id_sinistro
	set_class_allegati();
	$('#div_allegati').toggle(120)
}

function dele_fx(id_foto) {
	if (!confirm("Sicuri di eliminare la foto?")) {
		return false;
	}
	$("#delefoto").val(id_foto)
	$("#frm_sinistro").submit();
}

function dele_cid(id_sinistro) {
	if (!confirm("Sicuri di eliminare il cid?")) {
		return false;
	}
	$("#dele_cid").val(id_sinistro)
	$("#frm_sinistro").submit();
}
