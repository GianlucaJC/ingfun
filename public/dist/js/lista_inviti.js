$(document).ready( function () {
    $('#tbl_list_inviti tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_inviti').DataTable({
		"fnDrawCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
  
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
  
            // Total over all pages
            total = api
                .column( 5, { search: 'applied' } )
                .data()
                .reduce( function (a, b) {
					tot= intVal(a) + intVal(b)
                    return tot.toFixed(2);
                }, 0 );
  
            // Update status DIV
            $('#status').html('<b>TOTALE :</b> <u>€ '+ total + '</u>');
        },		
		
		
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
            zeroRecords: 'Nessun invito a fatturare trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Inviti a fatturare',
            infoFiltered: '(Filtrati da _MAX_ inviti a fatturare totali)',
        },

		
    });
	

	
} );



function change_state(id_fatt) {
	$("#modal_body").modal('show');
	$("#title_modal").html("Cambia stato alla fattura")
	html=`
		<div class="row mb-3">
			<div class="col-md-12">
				<div class="form-floating mb-3 mb-md-0">
					<select class="form-select" name="stato_fattura" id="stato_fattura" required>
					<option value=''>Select...</option>
						<option value='3'>Fatturato</option>
						<option value='4'>Non saldato</option>
						<option value='5'>Saldato</option>
					</select>
					<label for="stato_fattura">Imposta stato fattura*</label>
				</div>
			</div>	
		</div>
	`	
	$("#body_modal").html(html)	
	
	html=""
	html+="<button type='submit' class='btn btn-primary' name='btn_change_state' id='btn_change_state' value='change'>Cambia stato</button>"
	
	html+="<input type='hidden' name='id_fatt_change' id='id_fatt_change' value='"+id_fatt+"'>";
	
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