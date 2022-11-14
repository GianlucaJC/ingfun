$(document).ready( function () {
    $('#tbl_list_sottotipodoc tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_sottotipodoc').DataTable({
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
            zeroRecords: 'Nessuna Sotto Tipologia documento trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Sotto Tipologie di documento',
            infoFiltered: '(Filtrati da _MAX_ Sotto Tipologie di Documento totali)',
        },

		
    });
	
} );


function edit_elem(id_elem) {
	descrizione=$("#id_descr"+id_elem).data("descr")
	alias=$("#id_alias"+id_elem).data("alias")
	$("#descr_contr").val(descrizione)
	$("#alias").val(alias)
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