$(document).ready( function () {
    $('#tbl_list_pers tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });		
    $('#tbl_list_pers').DataTable({
		pageLength: 10,
		lengthMenu: [10, 15, 20, 50, 100, 200, 500],

		pagingType: 'full_numbers',
		dom: 'Bfrtip',
		buttons: [
			'excel'
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
            zeroRecords: 'Nessuna candidatura trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono disponibili candidature',
            infoFiltered: '(Filtrati da _MAX_ candidature totali)',
        },
    });	
	
} );

function hide_appalti(id_ref,stato){
    if (stato==1){
        if (!confirm("Sicuri di nascondere il lavoratore dalla formazione degli appalti?")) 
            return false
    } 
    else {
        if (!confirm("Sicuri di mostrare il lavoratore dalla formazione degli appalti?")) 
            return false
    } 
    base_path = $("#url").val();
    //<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
    const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
    const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
    fetch(base_path+"/hide_appalti", {
      method: 'POST',
      headers: {
        "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
      },
      body: "id_ref="+id_ref+"&stato="+stato,
    })
    .then(response => {
        if (response.ok) {
           return response.json();
        }
    })
    .then(response=>{
        if (!response) {

        }
        else {
            console.log(response)
            if (stato==0) {
                $("#hide_a"+id_ref).removeClass("btn btn-warning")
                $("#hide_a"+id_ref).addClass("btn btn-outline-warning")
            }            
            if (stato==1) {
                $("#hide_a"+id_ref).removeClass("btn btn-outline-warning")
                $("#hide_a"+id_ref).addClass("btn btn-warning")
            }
            alert("Impostazione effettuata!")
        }
    })
    .catch(status, err => {
        return console.log(status, err);
    })			    

}
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