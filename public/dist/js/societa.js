$(document).ready( function () {
    $('#tbl_list_societa tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_societa').DataTable({
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
            zeroRecords: 'Nessuna società trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti società',
            infoFiltered: '(Filtrati da _MAX_ società totali)',
        },

		
    });
	
} );


function edit_elem(id_elem) {
	descrizione=$("#id_descr"+id_elem).data("descr")
	$("#descr_contr").val(descrizione)
	mail_scadenze=$("#id_descr"+id_elem).data("mail")
	mail_fatture=$("#id_descr"+id_elem).data("mail_fatture")
	$("#mail_scadenze").val(mail_scadenze)
	$("#mail_fatture").val(mail_fatture)

	$("#edit_elem").val(id_elem)
	$('#div_definition').show(150)
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