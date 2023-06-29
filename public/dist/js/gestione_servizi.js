$(document).ready( function () {
    $('#tbl_list_servizi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_servizi').DataTable({
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
            zeroRecords: 'Nessun servizio trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Servizi',
            infoFiltered: '(Filtrati da _MAX_ servizi totali)',
        },

		
    });
	
} );


function new_serv() {
	$("#edit_elem").val('')
	$("#descr_contr").val('');
	$('#div_definition').hide()
	$('#div_definition').show(150)
}
function edit_elem(id_elem) {	
	descr_servizio=$("#info_s"+id_elem).data("descr_servizio")
	acronimo=$("#info_s"+id_elem).data("acronimo")
	
	$("#descr_contr").val(descr_servizio);
	$("#acronimo").val(acronimo);
	$('#div_definition').show(150)
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