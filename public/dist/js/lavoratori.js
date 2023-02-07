$(document).ready( function () {
    $('#tbl_list_lav tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_lav').DataTable({
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
            zeroRecords: 'Nessun Lavoratore trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Lavoratori',
            infoFiltered: '(Filtrati da _MAX_ Lavoratori totali)',
        },

		
    });
	
} );

function new_ditta() {
	ditta=$("#ditta").val()
	if (ditta.length==0) {
		alert("Selezionare la ditta di riferimento!")
		return false;
	}
	$("#old_ditta").val(ditta)

	$('#frm_lav1')[0].reset();	
	$('#edit_elem').val('');
	
	$('#div_definition').hide()
	$('#div_definition').show(150)
}

function popola_cap_pro(value) {
	$("#cap").val('');$("#provincia").val('')
	$("#cap").val(value.split("|")[0])
	$("#provincia").val(value.split("|")[1])
}

function edit_elem(id_elem) {	
	ditta=$("#ditta").val()
	$("#old_ditta").val(ditta)
	
	cognome=$("#ref_cognome"+id_elem).data("cognome")
	nome=$("#ref_nome"+id_elem).data("nome")
	$("#cognome").val(cognome)
	$("#nome").val(nome)
	
	$("#div_definition").show(150)
	$("#edit_elem").val(id_elem)

	
}

function dele_element(value) {
	if(!confirm('Sicuri di eliminare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}

function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}