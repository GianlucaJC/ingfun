// Example starter JavaScript for disabling form submissions if there are invalid fields

(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
		  alert("Controllare i campi obbligatori richiesti!")
          event.preventDefault()
          event.stopPropagation()
        }
		/*
		var cf=$("#codfisc").val()
		var valida=validaCodiceFiscale(cf);
		if (valida==false) {
		  $("#codfisc").removeClass('is-valid').addClass('is-invalid');
          event.preventDefault()
          event.stopPropagation()
		} else $("#codfisc").removeClass('is-invalid').addClass('is-valid');
		*/
        form.classList.add('was-validated')
      }, false)
    })
})()

$(document).ready( function () {
	$('body').addClass("sidebar-collapse");
	$('.select2').select2()
	
	
	$('#lavoratoria').select2().attr('required');

    $('#azienda_proprieta').on('click', function(){
       $("#azienda_proprieta").trigger('change')
    })

	$("#sub_newcand").click(function(){
	});

	$('#variazione').on("change keyup paste", function(){
		$("#bell").html("<i class='fas fa-bell' style='color: #df0000;'></i>")
		$("#flag_variazione").val("1")
	})
} );


function set_bell() {
	if( typeof set_bell.value == 'undefined' ) set_bell.value=0
	if (set_bell.value==0) set_bell.value=1
	else set_bell.value=0
	if (set_bell.value==1){
		$("#flag_variazione").val("1")
		$("#bell").html("<i class='fas fa-bell' style='color: #df0000;'></i>")
	}
	else{
		$("#flag_variazione").val("")
		$("#bell").html("<i class='far fa-bell-slash'></i>")
	}
}


function check_save() {
	if( typeof check_save.lav_from_list == 'undefined' ) 
		check_save.lav_from_list=false
	
	if (check_save.lav_from_list==true) {
		lavoratori=""
		$('.btn_lav').each(function () {
			if ($( this ).hasClass('sele')) {
				if (lavoratori.length!=0) lavoratori+=";"
				lavoratori+=$(this).attr('data-id_lav');
			}
		});	
		$("#lavoratori").val(lavoratori)
	} else lavoratori=$("#lavoratori").val();
	
	

	servizi=$("#servizi").val()
	if (servizi.length==0) {
		event.preventDefault()
		alert("Definire almeno un servizio associato alla ditta!")
		return false;
	}
	
	
	
	if (lavoratori.length==0) {
		event.preventDefault()
		alert("Definire almeno un lavoratore nella squadra!")
	}
	
}

function refresh_servizi() {
	$("#servizi")
	.find('option')
	.remove()
	.end();	
	
	id_ditta=$("#ditta").val();
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/refresh_servizi_ditte",
		data: {_token: CSRF_TOKEN,id_ditta:id_ditta},
		success: function (data) {
			$("#div_up_servizi").hide(150)

			$.each(JSON.parse(data), function (i, item) {
				
				$('#servizi').append('<option value="' + item.id_servizio + '">' + item.descrizione + '</option>');
						
			});
		}
	});		
}

function popola_servizi(id_ditta) {
	$("#servizi")
	.find('option')
	.remove()
	.end();	
	if (id_ditta.length==0) {
		return false;
	}
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/popola_servizi",
		data: {_token: CSRF_TOKEN, id_ditta:id_ditta},
		success: function (data) {
			ent=false;
			$.each(JSON.parse(data), function (i, item) {
				$('#servizi').append('<option value="' + item.id_servizio + '">' + item.descrizione + '</option>');
				ent=true
			});
			if (ent==false) {
				alert("Non risultano servizi associati a questa ditta!")
			}

		}
	});
	base_path = $("#url").val();
	href=base_path+"/servizi/"+id_ditta;
	$("#a_serv").attr('href', href)	
}

function btnlav(curr) {
	//init
	if( typeof btnlav.lavoratore == 'undefined' ) {
		btnlav.lavoratore=""
		btnlav.descr_t=""
		btnlav.flx=0
		btnlav.id_lav=0
		btnlav.closediv==false
		btnlav.opendiv==false
		btnlav.changetipo==false
		btnlav.tipo_contratto=""
	}	
	
	tipo_contratto="ALTRO";
	if (btnlav.tipo_contratto==1) tipo_contratto="FULL"
	if (btnlav.tipo_contratto==2) tipo_contratto="PART"
	if (btnlav.tipo_contratto==3) tipo_contratto="INTER"
	

	html="";
	
	if (btnlav.changetipo==false) {
		if (btnlav.closediv==true) html+=`</div>`
		if (btnlav.opendiv==true) html+=`<div class="row mb-3">`
	}

	if (btnlav.changetipo==true) {
		if (btnlav.flx==1) html+=`</div>`
		html+=`
			<div class="alert alert-secondary mt-3" role="alert" id='`+tipo_contratto+`'>
			  `+btnlav.descr_t
			  
			if (tipo_contratto!="FULL") {
				html+=`<span class='ml-3'>
			  			<a href='#div_ditta'>Torna su</a>
			  		</span>`
			}
			html+="</div>"
		
		html+=`<div class="row mb-3">`
	}
		
	html+=
	`	
		<div class="col-sm-3">
			<button style='height:80px;' type="button" class="btn btn-lg btn-block  btn_lav `+tipo_contratto+`" data-id_lav="`+btnlav.id_lav+`" data-lavoratore="`+btnlav.lavoratore+`">
				`+btnlav.lavoratore+`
			</button>
		</div>
	`	

	return html
}

function filtro(from) {
	$(".btn_filtro").removeClass("btn-primary")
	$(".btn_filtro").addClass("btn-outline-primary")

	if (from!=1) {el = document.getElementById("filtro1");el.ariaPressed = "false";}
	if (from!=2) {el = document.getElementById("filtro2");el.ariaPressed = "false";}
	if (from!=3) {el = document.getElementById("filtro3");el.ariaPressed = "false";}

	let el_sel = document.getElementById("filtro"+from);
	id_ref="filtro"+from
	
	$('.btn_lav').hide()
	if ($("#"+id_ref).attr('aria-pressed')=="false") {
		el_sel.ariaPressed = "true";
		$("#"+id_ref).removeClass("btn-outline-primary")
		$("#"+id_ref).addClass("btn-primary")
		$('.btn_lav').hide();
		if (from=="1") $('.FULL').show()
		if (from=="2") $('.PART').show()
		if (from=="3") $('.INTER').show()
	} else {
		el_sel.ariaPressed = "false";
		$("#"+id_ref).removeClass("btn-primary")
		$("#"+id_ref).addClass("btn-outline-primary")
		$('.btn_lav').show();	
	}
	
}
function lista_lavoratori(id_sezionale) {
	data_app=$("#data_app").val();
	ora_app=$("#ora_app").val();
	if (data_app.length==0) {
		alert("Valorizzare la data del servizio")
		return false;
	}
	if (ora_app.length==0) {
		alert("Valorizzare l'orario del servizio")
		return false;
	}
	check_save.lav_from_list=true;
	$("#div_lav_sel").hide();
	/*
	if (id_sezionale.length==0) {
		$("#lavoratori").val('')
		$("#div_lavoratori").empty()
		return false;
	}
	*/
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	elenco_lav=$("#lavoratoria").val()
	
	
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/lavoratori_sezionali",
		data: {_token: CSRF_TOKEN, id_sezionale:id_sezionale,data_app:data_app,ora_app:ora_app},
		success: function (data) {
			html=""
			curr=0;
			flx=0
			old_t="";
			btnlav.opendiv=false
			btnlav.closediv=false
			dati=JSON.parse(data)
			lavoratori=dati.lavoratori
			impegnati=dati.impegnati
			impegni_att=new Array()
			impegni_acc=new Array()
			impegni_rif=new Array()
			
			$.each(impegnati, function (i,item) {
				id_a=item.id
				id_l=item.id_lav_ref
				if (item.status=="0") impegni_att.push(id_l)
				if (item.status=="1") impegni_acc.push(id_l)
				if (item.status=="2") impegni_rif.push(id_l)

			})
			
			reperibilita=dati.reperibili
			const reperibili=[]
			$.each(reperibilita, function (i,item) {
				id_r=item.id_user
				console.warn("id_r",id_r)
				reperibili.push(id_r)
			})	
			console.warn("reperibili",reperibili)
			
			html+=`
				<a href='#FULL'>
					<button type='button' class='btn btn-outline-secondary'>FULL TIME</button>
				</a>
				<a href='#PART'>
					<button type='button' class='btn btn-outline-secondary'>PART TIME</button>
				</a>
				<a href='#INTER'>
					<button type='button' class='btn btn-outline-secondary'>INTERMITTENTI</button>
				</a>
				<a href='#ALTRO'>
					<button type='button' class='btn btn-outline-secondary'>ALTRO</button>
				</a>
				<div style="display:inline" class='ml-3'>
					<div class="ml-3 alert alert-success" role="alert" style="display:inline">
						Accettati
					</div>					
					<div class="ml-3 alert alert-warning" role="alert" style="display:inline">
						In attesa
					</div>					
					<div class="ml-3 alert alert-danger" role="alert" style="display:inline">
						Rifiutati
					</div>					
					<div class="ml-3 alert alert-primary" role="alert" style="display:inline">
						Reperibili
					</div>					
				</div>	
			
			`;



			
			$.each(lavoratori, function (i, item) {	
				curr++
				if (flx==0) btnlav.opendiv=true
				else {
					btnlav.opendiv=false
					btnlav.closediv=false
				}
				if ((curr)/5==parseInt((curr)/5)) {
					curr=1
					if (flx==1) btnlav.closediv=true
					btnlav.opendiv=true
				}
				
				btnlav.flx=flx
				btnlav.id_lav=item.id
				btnlav.lavoratore=item.nominativo
				tipo_contratto=item.tipo_contratto
				tipo_contr=item.tipo_contr
				//ref_tipo=tipo_contr+tipo_contratto;
				descr_t="ALTRO";
				if (tipo_contratto==1) descr_t="FULL TIME";
				if(tipo_contratto==2) descr_t="PARTIME";
				if(tipo_contratto==3) descr_t="INTERMITTENTI";
				ref_tipo=descr_t


				btnlav.changetipo=false		
				btnlav.descr_t="";
				btnlav.flx=flx
				btnlav.tipo_contratto=tipo_contratto
				if (old_t!=ref_tipo) {
					btnlav.changetipo=true
					btnlav.descr_t=descr_t					
					curr=1
				}
				flx=1
				old_t=ref_tipo;	
				
				html+=btnlav(curr)
			});
			if (curr>0) html+="</div>";
			$("#div_lavoratori").html(html)

			
			const arr_l=[]
			arr_ref=$("#lavoratori").val().split(";")
			for (sca=0;sca<=arr_ref.length-1;sca++) {
				arr_l.push(parseInt(arr_ref[sca]))
			}

			all_class=new Array();
			all_class[0]="btn-outline-info"

			all_class[1]="btn-info"
			all_class[2]="btn-warning"
			all_class[3]="btn-success"
			all_class[4]="btn-danger"
			all_class[5]="btn-primary"

			$('.btn_lav').each(function () {
				ref=$(this).attr('data-id_lav');
				ref=parseInt(ref)
				/*
				check_old=Object.keys(elenco_lav).indexOf(ref)
				console.log("ref",ref,"check_old",check_old,"arr_l",arr_l)
				if (check_old>0 && $( this ).hasClass('btn-outline-info')) {
					$( this ).removeClass('btn-outline-info').addClass('btn-info').addClass('sele')
				}
				*/			
				//check_r=Object.keys(reperibili).indexOf(ref)
				
				
				check_r=reperibili.includes(ref)
				class_btn1="btn-outline-info";class_btn2="btn-outline-info";
				
				if (check_r==true) {
					class_btn1="btn-primary";class_btn2="btn-primary";
				}

				//check_i=Object.keys(impegni_acc).indexOf(ref)
				check_i=impegni_acc.includes(ref)
				if (check_i==true) {
					class_btn1="btn-success";class_btn2="btn-success";
				}
				check_i=impegni_att.includes(ref)
				if (check_i==true) {
					class_btn1="btn-warning";class_btn2="btn-warning";
				}
				check_i=impegni_rif.includes(ref)
				if (check_i==true) {
					class_btn1="btn-danger";class_btn2="btn-danger";
				}



				
				check_pres=arr_l.includes(ref)
				
				if (check_pres==true) {
				//if ($.inArray( ref, arr_l )!== -1) {
					$( this ).addClass(class_btn2).addClass('sele')
				}
				else {
					$( this ).addClass(class_btn1)
				}
			
			});	




			$( ".btn_lav" ).on( "click", function() {
				ref=$(this).attr('data-id_lav');
				ref=parseInt(ref)
				
				check_i=impegni_acc.includes(ref)
				check_r=reperibili.includes(ref)

				if (!$( this ).hasClass('sele')) {
				//if ($( this ).hasClass('btn-outline-info')) {
					if (check_i==true || check_r==true) {
						
						if (!confirm("Sei sicuro? Il lavoratore risulta già reperibile o impegnato in altro appalto")) 
							return false;
					}
					for (sca=0;sca<all_class.length;sca++) {
						$( this ).removeClass(all_class[sca])
					}
					$( this ).addClass('btn-info').addClass('sele')
				}	
				else { 
					for (sca=0;sca<all_class.length;sca++) {
						$( this ).removeClass(all_class[sca])
					}
					$( this ).removeClass('sele').addClass('btn-outline-info')
				}
				
				update_list()

			});

		}
	});	
	
}

function update_list() {
	$("#responsabile_mezzo")
	.find('option')
	.remove()
	.end();		
	lav=""
	$('#responsabile_mezzo').append('<option value="">Select...</option>');

	$('.btn_lav').each(function () {
		if ($( this ).hasClass('sele')) {
			if (lav.length!=0) lav+=";"
			lav=$(this).attr('data-id_lav');
			nome=$(this).attr('data-lavoratore');
			$('#responsabile_mezzo').append('<option value='+lav+'>' + nome + '</option>');
		}
	});
}


