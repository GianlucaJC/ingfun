$(document).ready( function () {
	set_allegati()
} );



csv_send=false
var all_doc=new Array();
var doc=""
$(function(){
 //set_class_allegati(0)
 
});


function set_allegati() {
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  	
 
  base_path = $("#url").val();
  from="cedolini";
  
  $('#drag-and-drop-zone').dmUploader({ //
    url: base_path+'/upload.php',
	extraData: {
      "from":from,
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
	  console.log(dx)
	  
	  
      ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
      ui_add_log('Upload del file #' + id + ' COMPLETATO', 'success');
      ui_multi_update_file_status(id, 'success', 'Upload Completato');
      ui_multi_update_file_progress(id, 100, 'success', false);
	  
	  
	  allegato=data.filename
	  $("#allegato").val(allegato);
	  page_count();

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

function page_count() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	allegato=$("#allegato").val()
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/count_pdf",
		data: {_token: CSRF_TOKEN, allegato:allegato},
		success: function (data) {
			item=JSON.parse(data)
			$("#pagecount").val(item.pagecount)
			$( "#btn_split" ).prop( "disabled", false );
			
		}
	})
}

function split_pdf(page) {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	allegato=$("#allegato").val()
	pagecount=$("#pagecount").val()
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/split_pdf",
		data: {_token: CSRF_TOKEN, allegato:allegato, pagecount:pagecount,page:page},
		success: function (data) {
			item=JSON.parse(data)
			
			
		}
	})	
}
