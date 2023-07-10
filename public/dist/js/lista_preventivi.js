$(document).ready( function () {
    $('#tbl_list_preventivi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_preventivi').DataTable({
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
            zeroRecords: 'Nessun preventivo trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti preventivi',
            infoFiltered: '(Filtrati da _MAX_ preventivi totali)',
        },

		
    });
	
} );


function change_state(id_prev) {
	$("#modal_body").modal('show');
	$("#title_modal").html("Cambia stato al preventivo")
	html=`
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="stato_prev" id="stato_prev" required>
					<option value=''>Select...</option>
						<option value='3'>Elaborato</option>
						<option value='4'>Accettato</option>
						<option value='5'>Fatturato</option>
					</select>
					<label for="stato_prev">Imposta stato preventivo*</label>
				</div>
			</div>	
		</div>
	`	
	$("#body_modal").html(html)	
	
	html=""
	html+="<button type='submit' class='btn btn-primary' name='btn_change_state' id='btn_change_state' value='change'>Cambia stato</button>"
	
	html+="<input type='hidden' name='id_prev_change' id='id_prev_change' value='"+id_prev+"'>";
	
	$("#altri_btn").html(html)	
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