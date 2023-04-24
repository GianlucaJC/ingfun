$(document).ready( function () {
    $('#tbl_list_presenze tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="" />');
    });	
    var table=$('#tbl_list_presenze').DataTable({

        scrollX:        true,
        scrollCollapse: true,
        "pageLength": 15,
        fixedColumns:   {
            left: 2
        },		
		order: [[ 0, 'asc' ]],
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
            zeroRecords: 'Nessun appalto/lavoratore trovato nel periodo',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti dati da appalti',
            infoFiltered: '(Filtrati da _MAX_ lavoratori/appalti totali)',
        },

		
    });
	
} );

function save_value() {
	console.warn("mese",ins_value.mese,"id_lav",ins_value.id_lav,"id_servizio",ins_value.id_servizio)
	$("#btn_save").prop("disabled",true);
	var formData = new FormData()

	//Each through elements
	let CSRF_TOKEN = $("#token_csrf").val();
	formData.append("_token", CSRF_TOKEN)
	formData.append("periodo",save_value.periodo)
	formData.append("id_lav",save_value.id_lav)
	formData.append("id_servizio",save_value.id_servizio)
	formData.append("giorni",save_value.giorni)

	$('.dati').each(function(index, element) {
		id_importo=(this.id)
		value_importo=(this.value)
		formData.append(id_importo, value_importo)
	})
	
	for (var pair of formData.entries()) {
		console.warn(pair[0] + ', ' + pair[1]);
	}	
	
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	
	//{_token: CSRF_TOKEN, id_lav:save_value.id_lav,id_servizio:save_value.id_servizio,dati:formData}	
	$.ajax({
		type: 'POST',
		url: base_path+"/save_value_presenze",
		processData: false,
		contentType: false,
		data: formData,
		success: function (data) {
			
			info=JSON.parse(data)
			
			if (info.resp=="OK") {
				$("#frm_registro").submit();
				$('#modalvalue').modal('hide')
			}	
			else alert("Errore occorso durante il salvataggio!");
			
			//$("#descr_contr").val(info[0].denominazione)


		}
	});		

}

function ins_value() {
	console.warn("giorni",ins_value.giorni,"mese",ins_value.mese,"id_lav",ins_value.id_lav,"id_servizio",ins_value.id_servizio)

	periodo=ins_value.periodo
	mese=ins_value.mese
	mese_num=ins_value.mese_num
	giorni=ins_value.giorni
	anno=periodo.substr(0,4)
	
	save_value.periodo=ins_value.periodo
	save_value.mese=ins_value.mese
	save_value.mese_num=ins_value.mese_num
	save_value.id_lav=ins_value.id_lav
	save_value.id_servizio=ins_value.id_servizio
	save_value.giorni=ins_value.giorni

	id_servizio=ins_value.id_servizio


	$("#div_save").empty()
		
	html=""

	
		html+="<div class='row'>";
		for (sca=1;sca<=giorni;sca++) {
			if (id_servizio!=1000) {
				if (sca==8 || sca==15 || sca==22  || sca==29) {
					html+="</div>";
					html+="<div class='row mt-2'>";
				}	
			}
			id_ref=ins_value.id_lav+"_"+ins_value.id_servizio+"_"+sca
			value=$("#imp_"+id_ref).html()
			style="color:green";
			if (value.length!=0) style="background-color:#96d3ec!important;color:red";
			d = new Date(anno+"-"+mese_num+"-"+sca);
			
			day = d.getDay()
			
			if (day==1) day_d="Lun";
			if (day==2) day_d="Mar";
			if (day==3) day_d="Mer";
			if (day==4) day_d="Gio";
			if (day==5) day_d="Ven";
			if (day==6) day_d="Sab";
			if (day==0) day_d="Dom";
			max="";on="";
			if (id_servizio==1000)  {
				max="maxlength=100"
				html+="<div class='col-sm-4 mt-2'>";
			}
			else {
				html+="<div class='col-md-1' style='flex:0 0 14.2857%;max-width:14.2857%'>";
			}	
			
			
				html+="<div class='form-floating'>";
					
					html+="<input class='form-control dati' id='dato"+sca+"' type='text' value='"+value+"' "+on+" style='"+style+"' "+max+" />";
					
				html+="<label for='dato"+sca+"' class='form-label'>"+day_d+" "+sca+"</label>"					
				html+="</div>";
			html+="</div>";
				
		}
	html+="</div>";
	
	$("#title_doc").html("Inserimento dati")
	$("#bodyvalue").html(html)
	
	

	html="<button type='button' class='btn btn-primary' onclick='save_value()' id='btn_save'>Salva</button>";
	$("#div_save").html(html)

	$('#modalvalue').modal('show')
	

	
	
}


