break_s=false

$(document).ready( function () {
} );



csv_send=false
var all_doc=new Array();
var doc=""
$(function(){
 //set_class_allegati(0)
	mese_busta=$("#mese_busta").val()
	if (mese_busta.length!=0) set_allegati();
});


function canc_pdf() {
	if (!confirm("Sicuri di eliminare i file presenti nel periodo prescelto?"))
		return false;
	$('#dele_pdf').val(1);
	$('#frm_cedoliniup').submit();
}

function set_allegati() {
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  	
 
  base_path = $("#url").val();
  from="cedolini";
  mese_busta=$("#mese_busta").val()
  anno_busta=$("#anno_busta").val()
  periodo=mese_busta+anno_busta
  
  $('#drag-and-drop-zone').dmUploader({ //
    url: base_path+'/upload.php',
	extraData: {
      "from":from,"periodo":periodo
	},
	
	extFilter: ["pdf"],
	
    maxFileSize: 80000000, // 8 Megs 
    onDragEnter: function(){
      // Happens when dragging something over the DnD area
      this.addClass('active');
    },
    onDragLeave: function(){
      // Happens when dragging something OUT of the DnD area
      this.removeClass('active');
    },
    onInit: function(){
      // Plugin is ready to use
      ui_add_log('Plugin Avviato :)', 'info');
    },
    onComplete: function(){
      // All files in the queue are processed (success or error)
      ui_add_log('Tutti i trasferimenti in sospeso sono terminati');
    },
    onNewFile: function(id, file){
      // When a new file is added using the file selector or the DnD area
      ui_add_log('Nuovo file aggiunto #' + id);
      ui_multi_add_file(id, file);
    },
    onBeforeUpload: function(id){
	  $("#div_img").empty();
      // about tho start uploading a file
      ui_add_log('Inizio upload di #' + id);
      ui_multi_update_file_status(id, 'uploading', 'Uploading...');
      ui_multi_update_file_progress(id, 0, '', true);
    },
    onUploadCanceled: function(id) {
      // Happens when a file is directly canceled by the user.
      ui_multi_update_file_status(id, 'warning', 'Cancellato da utente');
      ui_multi_update_file_progress(id, 0, 'warning', false);
    },
    onUploadProgress: function(id, percent){
      // Updating file progress
      ui_multi_update_file_progress(id, percent);
    },
    onUploadSuccess: function(id, data){
      // A file was successfully uploaded
	  
	  fx=data.path
	  
	  dx=JSON.stringify(data)
	  
	  
	  
      ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Upload del file #' + id + ' COMPLETATO', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Completato');
      ui_multi_update_file_progress(id, 100, 'success', false);
	  
	  
	  
	  
	  
	  page_count();
	  $("#div_analisi").empty();
	  $("#div_progr").empty();
	  $("#btn_split").show(150)
    },
    onUploadError: function(id, xhr, status, message){
      ui_multi_update_file_status(id, 'danger', message);
      ui_multi_update_file_progress(id, 0, 'danger', false);  
    },
    onFallbackMode: function(){
      // When the browser doesn't support this plugin :(
      ui_add_log('Il plug-in non può essere utilizzato qui', 'danger');
    },
    onFileSizeError: function(file){
      ui_add_log('Il File \'' + file.name + '\' Non può essere aggiunto: Limite dimensione superato', 'danger');
    }
  });	
}
function set_step() {
	mese_busta=$("#mese_busta").val()
	anno_busta=$("#anno_busta").val()
	$("#div_allegati").hide()
	$( "#btn_step" ).prop( "disabled", true );
	$("#div_azioni").hide();
	$("#div_alert_exist").hide();
	if (mese_busta.length!=0 && anno_busta.length!=0) {
		$( "#btn_step" ).prop( "disabled", false );
		
	}	

}

function page_count() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	mese_busta=$("#mese_busta").val()
	anno_busta=$("#anno_busta").val()
	periodo=mese_busta+anno_busta	

	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		async:false,
		url: base_path+"/count_pdf",
		data: {_token: CSRF_TOKEN, periodo:periodo},
		success: function (data) {
			item=JSON.parse(data)
			$("#pagecount").val(item.pagecount)
			$("#btn_analisi" ).prop( "disabled", false );			
		}
	})
}

function split_pdf(page,from) {
	if ($("#pagecount").val().length==0) page_count();
	
	if (break_s==true) {
		$("#btn_split").text("Procedi con la suddivisione") 
		$("#btn_split" ).prop( "disabled", false );
		$("#div_progr").hide(150)
		break_s=false;
		return false
	}	

	if (from==0) {
		if ($("#btn_split").text()=="Procedi con la suddivisione") 
			$("#btn_split").text('Operazione in corso. Cliccare per interrompere')
		else {
			$("#btn_split").text("Attendere. Interruzione in corso...")
			$("#btn_split" ).prop( "disabled", true );
			break_s=true
			return false;
		}	
	}
	
	mese_busta=$("#mese_busta").val()
	anno_busta=$("#anno_busta").val()
	periodo=mese_busta+anno_busta	
	
	pagecount=$("#pagecount").val()
	perc=100
	if (page=="1") $("#div_progr").show(150)
	if (pagecount!=0)
		perc=parseInt((100/pagecount)*page)

	html="<div class='progress-bar progress-bar-striped' role='progressbar' style='width: "+perc+"%' aria-valuenow='"+perc+"' aria-valuemin='0' aria-valuemax='100'></div>";
	$("#div_progr").html(html)
	$('html, body').animate({
		scrollTop: $("#div_progr").offset().top
	}, 1500);		


	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	
	$.ajax({
		type: 'POST',
		url: base_path+"/split_pdf",
		data: {_token: CSRF_TOKEN, pagecount:pagecount,page:page,periodo:periodo},
		success: function (data) {
			item=JSON.parse(data)

			page++;
			if (page<=pagecount) split_pdf(page,1)
			else {
				$("#btn_split").text("Procedi con la suddivisione") 
				$("#btn_split" ).prop( "disabled", false );
				$("#div_progr").hide(150)
				$("#distr").show(150)
				analisi_pdf();
			}	
			
			
		}
	})	
}


function analisi_pdf() {
	
	$("#div_analisi").empty();
	mese_busta=$("#mese_busta").val()
	anno_busta=$("#anno_busta").val()
	periodo=mese_busta+anno_busta	
	
	pagecount=$("#pagecount").val()
	$('#div_wait').show(150);
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/analisi_pdf",
		data: {_token: CSRF_TOKEN,  pagecount:pagecount,periodo:periodo},
		success: function (data) {
			item=JSON.parse(data)
			if (item.message[0].length==0) {
				$('#div_wait').hide(150);
				alert("Nessun codice fiscale individuato nel file!");
				return false
			}
			allcf=item.allcf
			
			html="";
			html+="<table id='tbl_analisi' class='display'>";
				html+="<thead>";
					html+="<tr>";
						html+="<th>#</th>";
						html+="<th>Codice Fiscale</th>";
						html+="<th>Nominativo associato</th>";
					html+="</tr>";
				html+="</thead>";
				html+="<tbody>";
					cf_old="?"
					for (sca=0;sca<=item.message[0].length-1;sca++) {
						cf=item.message[0][sca]
						url="allegati/cedolini/"+periodo+"/"+cf+".pdf";
						c_url=false
						
						if(check_url(url)=="OK") c_url=true
						if (cf_old!=cf) {
							cf_old=cf
							html+="<tr>";
								html+="<td style='text-align:center'>"+(sca+1)+"</td>";
								html+="<td style='text-align:center'>"
								if (c_url==true) {
									html+="<a href='"+url+"' target='_blank'>";
										html+="<button type='button' class='btn btn-info' alt='Edit'><i class='far fa-file-pdf'></i> "+cf+"</button>";
									html+="</a>";	
								} else html+=cf
								html+="</td>";
								html+="<td>";
									resp=in_array(cf,allcf);
									if (resp.length!=0) html+=resp
									else html+="<font color='red'><i>In sospeso</i></font>";
								html+="</td>";
							html+="</tr>";
						}
					}
				html+="</tbody>";
				html+="<tfoot>";
					html+="<tr>";
					html+="<th>#</th>";
					html+="<th>Codfisc</th>";
					html+="<th>Nominativo</th>";
					html+="</tr>";
				html+="</tfoot>";
			html+="</table><hr>";
			$("#div_analisi").html(html);
			render_tb();

			
			$('#div_wait').hide(150);
			$( "#btn_split" ).prop( "disabled", false );
		}
	})	
}


function render_tb() {
    $('#tbl_analisi tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });		
    $('#tbl_analisi').DataTable({
		pageLength: 10,
		lengthMenu: [10, 15, 20, 50, 100, 200, 500],

		pagingType: 'full_numbers',	
		//dom: 'Bfrtip',
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
            zeroRecords: 'Nessun codice fiscale trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti codici fiscali',
            infoFiltered: '(Filtrati da _MAX_ codici fiscali totali)',
        },
    });		
}

function in_array(needle, haystack){
  var found = 0;
  for (var i=0, len=haystack.length;i<len;i++) {
	value=haystack[i].codfisc
    if (value.toUpperCase().trim() == needle.toUpperCase().trim()) return haystack[i].nominativo;
      found++;
  }
  return "";
}

function check_url(url) {
	let CSRF_TOKEN = $("#token_csrf").val();
	
	resp=$.ajax({
		type: 'POST',
		async: false,
		url: base_path+"/check_url",
		data: {_token: CSRF_TOKEN, url:url},		
	}).responseText;
	
	return resp;
}
