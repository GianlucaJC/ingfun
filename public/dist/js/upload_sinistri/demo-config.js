csv_send=false
var all_doc=new Array();
var doc=""
$(function(){
 //set_class_allegati(0)
 
});


function set_class_allegati() {
  /*
   * For the sake keeping the code clean and the examples simple this file
   * contains only the plugin configuration & callbacks.
   * 
   * UI functions ui_* can be located in: demo-ui.js
   */
  
  id_sinistro=set_class_allegati.id_sinistro  
 
  base_path = $("#url").val();
  from="sinistri";
  
  $('#drag-and-drop-zone').dmUploader({ //
    url: base_path+'/upload.php',
	extraData: {
      "from":from,
	  "id_sinistro":id_sinistro
	},
	
	extFilter: ["pdf","doc","docx","jpg","jpeg","png"],
	
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
	  
	  tipo_allegato=set_class_allegati.tipo_allegato
	  allegato=data.filename
	  saveinfo.filename=data.filename
	  saveinfo.id_sinistro=id_sinistro
	  saveinfo.tipo_allegato=tipo_allegato
	  saveinfo()

	  
	  $("#allegato").val(allegato)

	  $("#body_dialog").hide(150);
	  $("#btn_save_doc").show(150)

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

function saveinfo() {
	base_path = $("#url").val();
	let CSRF_TOKEN = $("#token_csrf").val();
	
	html="<span role='status' aria-hidden='true' class='spinner-border spinner-border-sm'></span> Attendere...";

	$("#div_save").html(html);
	setTimeout(function(){
	
		fetch(base_path+'/update_doc_sinistro', {
			method: 'post',
			//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
			headers: {
			  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
			},
			body: '_token='+ CSRF_TOKEN+'&filename='+saveinfo.filename+"&id_sinistro="+saveinfo.id_sinistro+"&tipo_allegato="+saveinfo.tipo_allegato
		})
		.then(response => {
			if (response.ok) {
			   return response.json();
			}
		})
		.then(resp=>{
			$("#div_save").empty();			
			if (resp.status=="KO") {
				alert("Problemi occorsi durante il salvataggio.\n\nDettagli:\n"+resp.message);
				return false;
			}
		})
		.catch(status, err => {
			return console.log(status, err);
		})	
	
	
	},1000);	
}