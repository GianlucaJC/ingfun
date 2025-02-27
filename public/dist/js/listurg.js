$(document).ready( function () {
    $('#tbl_list_rep tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });		
    $('#tbl_list_rep').DataTable({
		pageLength: 10,
		lengthMenu: [10, 15, 20, 50, 100, 200, 500],

		pagingType: 'full_numbers',
		//dom: 'Bfrtip',
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
            zeroRecords: 'Nessuna urgenza trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono disponibili urgenze',
            infoFiltered: '(Filtrati da _MAX_ urgenze totali)',
        },
    });	
	
} );


function dele_element(value) {
	if(!confirm('Sicuri di eliminare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#dele_cand').val(value)	
}

function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_cand').val(value)	
}

function push_urg(value) {
	if(!confirm("Sicuri di sollecitare il lavoratore per l'urgenza?")) 
		event.preventDefault() 
	else 
		$('#push_urgenza').val(value)	
}