
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
		"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tutti"]],
		"pageLength": 25, // Imposta il valore predefinito

		pagingType: 'full_numbers',
		dom: 'Bflrtip', // Aggiunto 'l' per mostrare il menu di selezione
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


function new_app() {
    data_app=$("#data_app").val();
    html=`
        <div class="form-group">
            <label for="data_appalto">Data appalto</label>
            <input type="date" class="form-control" id="data_appalto" name='data_appalto' value='`+data_app+`' required>
        </div>
        <button type="submit" name='newapp' class="btn btn-primary">Crea appalto</button>        
    `
    $("#body_content").html(html)
    $('#modalinfo').modal('show')
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
