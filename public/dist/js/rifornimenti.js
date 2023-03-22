$(document).ready( function () {
    $('#tbl_list_rif tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_list_rif').DataTable({
		order: [[0, 'desc']],
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
            zeroRecords: 'Nessun Rifornimento trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Rifornimenti',
            infoFiltered: '(Filtrati da _MAX_ Rifornimenti totali)',
        },

		
    });
		
} );

function zoom(id_foto) {
	filename=$("#id_foto"+id_foto).data("foto")	
	html="";
	html+="<div>";
		html+="<img src='dist/upload/rifornimenti/thumbnail/medium/"+filename+"'>";
	html+="</div>"
	html+="<hr>";
	html+="<a href='dist/upload/rifornimenti/"+filename+"' target='_blank'>";
		html+="<button type='button' class='btn btn-primary'>Zoom</button>";
	html+="</a>"
	$('#modal_img').modal('show')
	$("#body_modal").html(html)	
}

function edit_elem(id_elem) {	
	descrizione=$("#id_descr"+id_elem).data("descr")
	importo=$("#id_importo"+id_elem).data("importo")
	alias=$("#id_alias"+id_elem).data("alias")
	$("#descr_contr").val(descrizione)
	$("#importo").val(importo)
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