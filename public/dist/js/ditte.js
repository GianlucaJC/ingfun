$(document).ready( function () {
    $('#tbl_list_ditte tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_ditte').DataTable({
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
            zeroRecords: 'Nessuna Ditta trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Ditte',
            infoFiltered: '(Filtrati da _MAX_ Ditte totali)',
        },

		
    });
	
} );

function new_ditta() {
	$('#frm_ditte1')[0].reset();	
	$('#edit_elem').val('');
	$('#denominazione').val('');
	$('#div_definition').hide()
	$('#div_definition').show(150)
}

function popola_cap_pro(value) {
	$("#cap").val('');$("#provincia").val('')
	$("#cap").val(value.split("|")[0])
	$("#provincia").val(value.split("|")[1])
}

function edit_elem(id_ditta) {	
	$('#div_definition').hide()
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/getditta",
		data: {_token: CSRF_TOKEN, id_ditta:id_ditta},
		success: function (data) {
			
			info=JSON.parse(data)
			$("#azienda_prop").val(info[0].id_azienda_prop)
			$("#descr_contr").val(info[0].denominazione)
			$("#comune").val(info[0].comune)
			$("#cap").val(info[0].cap)
			$("#provincia").val(info[0].provincia)
			$("#piva").val(info[0].piva)
			$("#cf").val(info[0].cf)
			$("#email").val(info[0].email)
			$("#pec").val(info[0].pec)
			$("#telefono").val(info[0].telefono)
			$("#fax").val(info[0].fax)
			$("#edit_elem").val(id_ditta)
			$('#div_definition').show(150)

		}
	});		
	
	
	
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