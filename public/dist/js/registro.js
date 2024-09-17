var table
var subm=0

$(document).ready( function () {
    $('#tbl_list_presenze tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="" />');
    });	
	old_cerca=$("#old_cerca").val()
	
    table=$('#tbl_list_presenze').DataTable({
		"search": {
			"search": old_cerca
		},
        scrollX:        false,
        scrollCollapse: true,
        "pageLength": 35,
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

	$('#tbl_list_presenze').on('search.dt', function() {
		var old_cerca = $('.dataTables_filter input').val();
		$(".old_cerca").val(old_cerca)
	}); 

	
	c_page=$("#c_page").val()
	if (!c_page || c_page.length==0) c_page=0
	c_page=parseInt(c_page)
	table.page( c_page ).draw( false )

	value=$("#zoom_tbl").val()
	setZoom(value)
	$('body').addClass("sidebar-collapse");
	$("#div_tb").show(150)
	$('.select2').select2();
	$('#range_date').daterangepicker({
		"locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Applica",
        "cancelLabel": "Annulla",
        "fromLabel": "Da",
        "toLabel": "A",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Dom",
            "Lun",
            "Mar",
            "Mer",
            "Gio",
            "Ven",
            "Sab"
        ],
        "monthNames": [
            "Gennaio",
            "Febbraio",
            "Marzo",
            "Aprile",
            "Maggio",
            "Giugno",
            "Luglio",
            "Agosto",
            "Settembre",
            "Ottobre",
            "Novembre",
            "Dicembre"
        ],
        "firstDay": 1
    }
	})
	$("#range_date").val('')

	reopen_service=$("#reopen_service").val()
	reopen_dati=$("#reopen_dati").val()
	if (reopen_service && reopen_service.length!=0) {
		ins_value.periodo=reopen_dati.split("|")[0]
		ins_value.giorni=reopen_dati.split("|")[1]
		ins_value.mese=reopen_dati.split("|")[2]
		ins_value.mese_num=reopen_dati.split("|")[3]
		ins_value.id_lav=reopen_dati.split("|")[4]
		ins_value.id_servizio=reopen_dati.split("|")[5]
		ins_value.tipo_dato=reopen_dati.split("|")[6]

		$("#reopen_service").val('')
		$("#reopen_dati").val('')

		ins_value(0)
	}
	
} );



function setZoom(value) {
	$('#div_tb').css('transform','scale('+value+')');
	$('#div_tb').css('transformOrigin','left top');
};


function select_servizi(value) {
	
	$("#div_newserv").hide(150);

	$("#descrizione").val('');$("#alias_ref").val('');
	$('#descrizione').removeAttr('required');
	$('#alias_ref').removeAttr('required');
	$('#tipo_d').removeAttr('required');
	if (value=="0") {
		$("#div_newserv").show(150);
		$('#descrizione').prop('required',true);
		$('#alias_ref').prop('required',true);
		$('#tipo_d').prop('required',true);
	}

}


function save_value(refr=0) {
	console.warn("mese",ins_value.mese,"id_lav",ins_value.id_lav,"id_servizio",ins_value.id_servizio)
	subm=1
	$("#btn_save").prop("disabled",true);
	var formData = new FormData()

	//Each through elements
	let CSRF_TOKEN = $("#token_csrf").val();
	formData.append("_token", CSRF_TOKEN)
	formData.append("tipo_dato",save_value.tipo_dato)
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
	//var tbl=$('#tbl_list_presenze').dataTable();
	c_page = table.page();
	$("#c_page").val(c_page)
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
				if (refr==1) {
					$("#frm_registro").submit();
					$('#modalvalue').modal('hide')
				} else $("#btn_save").prop("disabled",false);
			}	
			else alert("Errore occorso durante il salvataggio!");
			
			//$("#descr_contr").val(info[0].denominazione)


		}
	});		

}

function close_reg() {
	if (subm==1) $("#frm_registro").submit();
	$('#modalvalue').modal('hide')
}

function change_serv(value,giorno){
	old_cerca = $('.dataTables_filter input').val();
	$('.old_cerca').val(old_cerca);
	//attenzione! Se si inviano altri parametri, vedere anche costruzione tabella in presenze.blade
	serv=value.split("|")[0]
	$("#reopen_service").val(serv)
	dato=""
	dato+=ins_value.periodo+"|"
	dato+=ins_value.giorni+"|"
	dato+=ins_value.mese+"|"
	dato+=ins_value.mese_num+"|"
	dato+=ins_value.id_lav+"|"
	dato+=serv+"|"
	dato+=ins_value.tipo_dato
	
	$("#reopen_dati").val(dato)

	save_value(1)
}


function ins_value(giorno) {
	console.warn("giorno",giorno,"giorni",ins_value.giorni,"mese",ins_value.mese,"id_lav",ins_value.id_lav,"id_servizio",ins_value.id_servizio)
	servizi_js=$("#servizi_js").val()



	tipo_dato=ins_value.tipo_dato
	periodo=ins_value.periodo
	mese=ins_value.mese
	mese_num=ins_value.mese_num
	giorni=ins_value.giorni
	anno=periodo.substr(0,4)
	
	save_value.tipo_dato=tipo_dato
	save_value.periodo=ins_value.periodo
	save_value.mese=ins_value.mese
	save_value.mese_num=ins_value.mese_num
	save_value.id_lav=ins_value.id_lav
	save_value.id_servizio=ins_value.id_servizio
	save_value.giorni=ins_value.giorni

	id_servizio=ins_value.id_servizio


	$("#div_save").empty()
	def_serv=""	
	html=""
		//se non si stanno inserendo note
		if (id_servizio!="5002") {
			html+=`<div class='mb-2'>
				<label for='scelta_ins'>Scelta servizio</label>
				<select class="form-select form-select-sm" id='scelta_ins' onchange="change_serv(this.value,`+giorno+`);">		
			`
					arr_s=servizi_js.split(";")
					for (sca=0;sca<arr_s.length;sca++)  {
						id_s=arr_s[sca].split("|")[0]
						descrizione=arr_s[sca].split("|")[1]
						acr=arr_s[sca].split("|")[2]
						html+="<option value='"+id_s+"|"+acr+"' "
						if (id_s==id_servizio) {
							html+=" selected "
							def_serv=acr
						}	
						html+=">"+descrizione+"</option>";
					}
				html+=`</select>
			</div>`
		}
	
		html+="<div class='row'>";
		for (sca=1;sca<=giorni;sca++) {
			if (tipo_dato=="0") {
				if (sca==8 || sca==15 || sca==22  || sca==29) {
					html+="</div>";
					html+="<div class='row mt-2'>";
				}	
			}
			
			id_ref=ins_value.id_lav+"_"+ins_value.id_servizio+"_"+sca
			value=$("#imp_"+id_ref).html()
			

			style="color:green";
			if (value.length!=0) style="background-color:#96d3ec!important;color:red";
			
			
			if (giorno==sca) 
				style="background-color:#7fffd4!important;color:blue";
			

			
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
			if (tipo_dato=="1")  {
				max="maxlength=100"
				html+="<div class='col-sm-4 mt-2'>";
			}
			else {
				html+="<div class='col-md-1' style='flex:0 0 14.2857%;max-width:14.2857%'>";
			}	
			
			
				html+="<div class='form-floating'>";
					
					html+="<input class='form-control dati' id='dato"+sca+"' type='text' value='"+value+"' "+on+" style='"+style+"' "+max+"/>";
					
					html+="<label for='dato"+sca+"' class='form-label'>"+day_d+" "+sca+"</label>"					

					if (tipo_dato!="1")  {
						html+=`
							<button type='button' id='btn_serv`+sca+`'
							class='mt-1 btnserv btn btn-outline-primary btn-sm' 
							onclick="$('#dato`+sca+`').val('`+def_serv+`')" 
							>
							`+def_serv+
							`</button>`
					}	
				html+="</div>";
				
			html+="</div>";
				
		}
	html+="</div>";
	
	$("#title_doc").html("Inserimento dati")
	$("#bodyvalue").html(html)
	
	
	

	html="<button type='button' class='btn btn-primary' onclick='save_value(0)' id='btn_save'>Salva</button>";
	$("#div_save").html(html)

	$('#modalvalue').modal('show')
	$('#modalvalue').on('shown.bs.modal', function() {
		if (giorno!="0") $("#dato"+giorno).focus();
		else $("#dato1").focus();
	})	
	
	
	
	
}


