send_i=new Array()
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

function new_invito() {
    lista=send_i.join("|")
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/set_invio_invito",
		data: {_token: CSRF_TOKEN,lista:lista},
		success: function (data) {
        console.log(data)
           window.location.href = "invito";
		}
	});	    
    
}

function removeItem(arr, value) {
  var index = arr.indexOf(value);
  if (index > -1) {
    arr.splice(index, 1);
  }
  return arr;
}
function set_send(element) {
    sel=$("#sendi"+element).is(':checked')
    var index = send_i.indexOf(element);
    if (index==-1 && sel==true) send_i.push(element);
    if (index>-1 && sel==false) send_i=removeItem(send_i,element);
    $("#div_send_invito").hide()
    if (send_i.length>0) $("#div_send_invito").show(200)
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