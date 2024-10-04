$(document).ready( function () {
    $('#tbl_rimborsi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_rimborsi').DataTable({
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
            zeroRecords: 'Nessun Rimborso trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti Rimborsi',
            infoFiltered: '(Filtrati da _MAX_ Rimborsi totali)',
        },

		
    });
	
} );

function zoom(id_foto) {
	url=$("#url").val()
	filename=$("#id_foto"+id_foto).data("foto")	
	html="";
	html+="<div>";
		html+="<a href='"+url+"/dist/upload/rimborsi/"+filename+"' 	target='_blank'>";
			html+="<img src='"+url+"/dist/upload/rimborsi/thumbnail/medium/"+filename+"'>";
		html+="</a>";	
	html+="</div>"
	html+="<hr>";
	html+="<a href='"+url+"/dist/upload/rimborsi/"+filename+"' target='_blank'>";
		html+="<button type='button' class='btn btn-primary'>Zoom</button>";
	html+="</a>"
	$('#modal_img').modal('show')
	$("#body_modal").html(html)	
}