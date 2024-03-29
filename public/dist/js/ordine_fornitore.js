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
	stato=$("#stato").val()
	if (stato=="2") {
		$("#frm_ordini :input").attr("disabled", "disabled");
		$("#frm_ordini1 :input").attr("disabled", "disabled");
		$("#btn_elenco").prop("disabled", false);
	}	
	
} );

function refresh_forn() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_forn",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#div_up_forn").hide(150)
			$("#id_fornitore")
			.find('option')
			.remove()
			.end();	
			
			$('#id_fornitore').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#id_fornitore').append('<option value="' + item.id + '">' + item.ragione_sociale + '</option>');
						
			});
		}
	});		
}

function dele_element(id_dele) {
	if (!confirm("Sicuri di cancellare la riga?")) {
		event.preventDefault();
		return false
	}
	$("#dele_riga").val(id_dele)
}

function edit_product(id_riga) {
	$("#title_modal").html("Inserimento/Modifica articolo")
	$('#modal_story').modal('toggle')
	//$("#body_modal").html("Caricamento informazioni in corso...")		

	if (id_riga!=0) {
		id_fornitore=$("#inforow"+id_riga).data("id_fornitore")
		codice=$("#inforow"+id_riga).data("codice")
		
		quantita=$("#inforow"+id_riga).data("quantita")
		prezzo_unitario=$("#inforow"+id_riga).data("prezzo_unitario")
		prezzo_unitario=prezzo_unitario.toFixed(2)
		
		subtotale=$("#inforow"+id_riga).data("subtotale")
		subtotale=subtotale.toFixed(2)
		
		aliquota=$("#inforow"+id_riga).data("aliquota")
	
		$("#id_fornitore").val(id_fornitore)
		$("#quantita").val(quantita)
		
		$("#prezzo_unitario").val(prezzo_unitario)
		$("#subtotale").val(subtotale)
		$("#aliquota").val(aliquota)
		$("#codice").val(codice).trigger('change');
		$("#id_riga").val(id_riga)

	}
	else {
		$("#frm_ordini1")[0].reset()
		$("#codice").val('').trigger('change');
	}	
	
	

}


function refresh_prodotti() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_prodotti",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#codice")
			.find('option')
			.remove()
			.end();	
			
			$('#codice').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#codice').append('<option value="' + item.id + '">' + item.descrizione + '</option>');
						
			});
		}
	});	
}

function refresh_aliquota() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_aliquota",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			$("#aliquota")
			.find('option')
			.remove()
			.end();	
			
			$('#aliquota').append("<option value=''>Select...</option>");
			$.each(JSON.parse(data), function (i, item) {
				
				$('#aliquota').append('<option value="' + item.id + '|'+item.aliquota+'">' + item.aliquota+'% - '+item.descrizione + '</option>');
						
			});
		}
	});		
}

function calcolo_riga() {
	quantita=$("#quantita").val()
	prezzo_unitario=$("#prezzo_unitario").val()
	infoaliquota=$("#aliquota").val()
	aliquota=infoaliquota.split("|")[1]/100


	if (quantita && prezzo_unitario) {
		subtotale=quantita*prezzo_unitario
		if (aliquota && aliquota!=0) {
			aliquota=aliquota+1
			subtotale=subtotale*aliquota
		}
		subtotale=subtotale.toFixed(2)
		$("#subtotale").val(subtotale)
	}
}

function art_cancel() {
	motivazione_canc=$("#motivazione_canc").val()
	if (motivazione_canc.length==0) {
		event.preventDefault()
		event.stopPropagation()

		alert("Definire correttamente una motivazione per l'annullamento dell'ordine relativo all'articolo");
		return false;
	}
}