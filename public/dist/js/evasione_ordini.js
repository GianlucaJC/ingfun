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
	

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation1')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add('was-validated')
      }, false)
    })	
	
	
	
	
})()
$(document).ready( function () {
	$('.select2').select2({
		dropdownParent: $('#modal_story')
	})

    /*
	$('.tfoot1 th').each(function () {
        var title = $(this).text();
		if (title.length!=0) {
			style="";
			if (title=="ID") style='style="max-width:30px;"'
			if (title=="Data") style='style="max-width:120px;"'
			$(this).html('<input '+style+' type="text" placeholder="' + title + '" />');
		}
    });	
	*/
    var table=$('#tbl_prodotti_ordine').DataTable({
		pageLength: 100,
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
            zeroRecords: 'Nessun articolo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti articoli in questo ordine',
            infoFiltered: '(Filtrati da _MAX_ articoli totali)',
        },

		
    });
	
} );


function evasione() {
	ctrl=false
	$('.ctrl_qta').each(function () {
		value=$(this).val()
		qta_ord=value.split("-")[0]
		qta_eva=value.split("-")[1]
		id_ref_articolo=$(this).data('id_ref_articolo');
		value_ins=$("#"+id_ref_articolo).val()
		console.log("id_ref_articolo",id_ref_articolo,"value_ins",$("#"+id_ref_articolo).val(),"qta_ord",qta_ord,"qta_eva",qta_eva)
		if ((parseInt(value_ins)+parseInt(qta_eva))>parseInt(qta_ord)) ctrl=true
	})
	if (ctrl==true) {
		event.preventDefault()
		alert("Attenzione. Impossibile evadere quantità superiori a quelle ordinate (tenendo conto anche di quelle già evase)!");
		return false
	} else	
		$('.qta_e').removeAttr('disabled');	
}