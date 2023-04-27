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

	
} );




function new_giust() {
	$('#frm_giust1')[0].reset();
	$("#lavoratori").val('').trigger('change')
	$('#edit_elem').val('');
	$('#div_definition').hide()
	$('#div_definition').show(150)
}

function popola_cap_pro(value) {
	$("#cap").val('');$("#provincia").val('')
	$("#cap").val(value.split("|")[0])
	$("#provincia").val(value.split("|")[1])
}

function edit_elem(obj) {
	$('#pw_first').attr('placeholder','Non indicare se si lascia la stessa');
	$('#pw_ripeti').attr('placeholder','Non indicare se si lascia la stessa');
	$('#div_definition').show()
	$("#email").val($(obj).data("mail"))
	$("#nome").val($(obj).data("nominativo"))
	$("#ruolo").val($(obj).data("idrole"))
	$("#edit_elem").val($(obj).data("id_elem"))
	
	
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