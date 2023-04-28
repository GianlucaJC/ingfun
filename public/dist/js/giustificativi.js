$(document).ready( function () {
    $('#tbl_list_giustificativi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_giustificativi').DataTable({
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
            zeroRecords: 'Nessun Giustificativo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Giustificativi',
            infoFiltered: '(Filtrati da _MAX_ Giustificativi totali)',
        },

		
    });
	$('.select2').select2()


$('#range_date').daterangepicker({
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Applica",
        "cancelLabel": "Annulla",
        "fromLabel": "Da",
        "toLabel": "A",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        "monthNames": [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
})
	
	/*
	$('form#frm_giust1').submit(function(e) {		
		ore_gg=$("#ore_gg").val()
		value_descr=$("#value_descr").val()
		if (ore_gg.length==0 && value_descr.length==0) {
			e.preventDefault();
			alert("Compilare il campo Ore GG o Descrizione");
		}
		if (ore_gg.length!=0 && value_descr.length!=0) {
			e.preventDefault();
			alert("Compilare solo uno dei due campi tra Ore GG e Descrizione");
		}
	});	
	*/
	
} );



function select_servizi(value) {
	$(".tipi").hide()
	$("#div_newserv").hide(150);
	tipo_dato=$("#servizio_custom").find(':selected').data("tipo_dato");
	
	$("#descrizione").val('');$("#alias_ref").val('');
	$('#descrizione').removeAttr('required');
	$('#alias_ref').removeAttr('required');
	$('#tipo_d').removeAttr('required');
	if (value=="0") {
		$("#div_newserv").show(150);
		$('#descrizione').prop('required',true);
		$('#alias_ref').prop('required',true);
		$('#tipo_d').prop('required',true);
	}
	if (tipo_dato=="0") $("#div_oregg").show(150);
	if (tipo_dato=="1") $("#div_descr").show(150);
}

function set_tipo(tipo_dato) {
	$(".tipi").hide()
	$('#ore_gg').removeAttr('required');
	$('#value_descr').removeAttr('required');
	if (tipo_dato=="0") {
		$('#ore_gg').prop('required',true);
		$("#div_oregg").show(150);
	}	
	if (tipo_dato=="1") {
		$('#value_descr').prop('required',true);
		$("#div_descr").show(150);
	}
}


function new_giust() {
	$('#frm_giust1')[0].reset();
	$("#lavoratori").val('').trigger('change')
	$('#div_definition').hide()
	$('#div_definition').show(150)
}



function dele_element(value) {
	if(!confirm('Sicuri di eliminare l\'utente?')) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}

function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'utente?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}