$(document).ready( function () {
    $('#tbl_list_pers tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0) {
			w="200px"
			if (title=="Stato") w="60px"
			if (title=="ID") w="40px"
			$(this).html('<input style="width:'+w+'" type="text" placeholder="' + title + '" />');
		}	
    });		
    $('#tbl_list_pers').DataTable({
		pageLength: 10,
		lengthMenu: [10, 15, 20, 50, 100, 200, 500],

		pagingType: 'full_numbers',
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
            zeroRecords: 'Nessun appalto trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono disponibili appalti',
            infoFiltered: '(Filtrati da _MAX_ appalti totali)',
        },
    });	
	
} );

function check_100() {
    if ($('#last_100').val()=="1") 
        $('#last_100').val('0');
    else 
        $('#last_100').val('1');

    $('#frm_appalti').submit()
}
function send_notif() {
    if (!confirm("Sicuri di inviare le notifiche? (Gli appalti coinvolti passeranno dallo stato Bozza a Inviato)")) {
        event.preventDefault() 
        return false;
    }
    $("#frm_appalti").submit();
}

function dele_element(value) {
	if(!confirm('Sicuri di eliminare l\'elemento?')) 
		event.preventDefault() 
	else {
		$('#dele_cand').val(value)	
        $("#frm_appalti").submit();        
    }

    
}

function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else {
		$('#restore_cand').val(value)	
        $("#frm_appalti").submit();        
    }
}

function push_appalti(value) {
	if(!confirm("Sicuri di sollecitare tutti i lavoratori dell'appalto (che non hanno risposto)?")) 
		event.preventDefault() 
	else {
		$('#push_appalti').val(value)	
        $("#frm_appalti").submit();        
    }
}