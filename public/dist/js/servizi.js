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
            zeroRecords: 'Nessun Servizio trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Servizi',
            infoFiltered: '(Filtrati da _MAX_ Tipologie di Servizio totali)',
        },

		
    });
	$('.select2').select2()
} );



function check_associa() {
	ditta_ref=$("#ditta_ref").val()
	if (ditta_ref.length==0) {
		alert("Scegliere una ditta di riferimento!");
		return false;
	}	
	if (ditta_ref.length>1) {
		alert("Scegliere SOLO una ditta di riferimento!");
		return false;
	}	
		
	$('#div_set_service').show(150)
	$('#div_set_service').show(150)
	$('#div_table_servizi').hide(150)
}

function edit_elem(id_elem) {	
	ret=check_associa()
	if (ret==false) return false;
	id_servizio=$("#info_s"+id_elem).data("id_servizio")
	id_ditta=$("#info_s"+id_elem).data("id_ditta")
	importo_ditta=$("#info_s"+id_elem).data("importo_ditta")
	aliquota=$("#info_s"+id_elem).data("aliquota")
	importo_lavoratore=$("#info_s"+id_elem).data("importo_lavoratore")
	$( "#ditta_ref" ).prop( "disabled", true );
	$( "#service" ).prop( "disabled", true );
	
	$("#service").val(id_servizio)
	$("#ditta_ref").val(id_ditta)	
	$("#importo").val(importo_ditta)
	$("#aliquota").val(aliquota)
	$("#importo_lavoratore").val(importo_lavoratore)

	$("#edit_elem").val(id_elem)

}

function dele_element(value) {
	ditta_ref=$("#ditta_ref").val()
	$("#ditta_from_frm1").val(ditta_ref)
	if(!confirm('Sicuri di eliminare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#dele_ds').val(value)	

}

function restore_element(value) {
	ditta_ref=$("#ditta_ref").val()
	$("#ditta_from_frm1").val(ditta_ref)
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}