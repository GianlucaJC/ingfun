$(document).ready( function () {
    $('#tbl_menu tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });	
    var table=$('#tbl_menu').DataTable({
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
            zeroRecords: 'Nessuna voce di menu trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti voci di menu',
            infoFiltered: '(Filtrati da _MAX_ voci totali)',
        },

		
    });
	$('.select2').select2()

	$( ".btn_schema" ).on( "click", function() {
		id_ref=this.id
		parent_id=$("#"+id_ref).data("parent_id")
		ordine_origine=$("#"+id_ref).data("ordine_origine")
		$("#parent_id_dest").val(parent_id)
		$("#ordine_origine").val(ordine_origine)

		
		$(".btn_schema").removeClass('btn-primary')
		$("#"+id_ref).removeClass('btn-outline-primary').addClass('btn-primary')
	});
	
	id_get=$("#id_get").val()
	parent_get=$("#parent_get").val()
	if (id_get.length>0 && id_get!=0) schema(id_get,parent_get)
		
	
	$( ".bottoni" ).on( "click", function() {
		init_bottoni();
		set_bottoni("",this.id)
		txt=this.id
		result = txt.replace("btn_", "");
		$("#button_color").val(result)
		
	})
	
	
} );


function open_menu(id_ref) {
	$("#mnu"+id_ref).toggle(50)
	if ($("#fold"+id_ref).hasClass("fas fa-folder-plus"))
		$("#fold"+id_ref).removeClass("fas fa-folder-plus").addClass('fas fa-folder-minus')
	else	
		$("#fold"+id_ref).removeClass("fas fa-folder-minus").addClass('fas fa-folder-plus')
}

function check_sposta() {
	dest=$("#parent_id_dest").val()
	if (dest.length==0) {
		event.preventDefault()
		alert("E' necessario indicare la voce di menu di riferimento dove sarà inserita la voce selezionata")
	}
}

function edit_elem(id_elem) {
	tipo_view=$("#tipo_view").val()
	$("#tipo_view_bis").val(tipo_view)
	voce=$("#id_descr"+id_elem).data("voce")
	note=$("#id_descr"+id_elem).data("note")
	visible=$("#id_descr"+id_elem).data("visible")
	disable=$("#id_descr"+id_elem).data("disable")
	permissions=$("#id_descr"+id_elem).data("permissions")
	roles=$("#id_descr"+id_elem).data("roles")
	
	$("#id_mod").val(id_elem)
	$("#voce_edit").val(voce)
	$("#note").val(note)
	$("#btn_visible").val(visible)
	$("#btn_disable").val(disable)
	arr=roles.split("|")
	$("#ruolo").val(arr).trigger('change');
	arr=permissions.split("|")
	$("#permesso").val(arr).trigger('change');
	
	$("#div_mnu_admin").hide(20)
	$("#div_mnu_admin").show(150)
	
	class_btn_action=$("#id_descr"+id_elem).data("class_btn_action")	
	init_bottoni()
	set_bottoni(class_btn_action,2)
	result = class_btn_action.replace("btn_", "");
	$("#button_color").val(result)
	
	
}

function init_bottoni() {
	$(".bottoni").each(function(){
		if (this.id=="btn_primary")
			$("#"+this.id).removeClass('btn-primary').removeClass('btn-outline-primary').addClass('btn-outline-primary')
		if (this.id=="btn_secondary") 
			$("#"+this.id).removeClass('btn-secondary').removeClass('btn-outline-secondary').addClass('btn-outline-secondary')
		if (this.id=="btn_success")
			$("#"+this.id).removeClass('btn-success').removeClass('btn-outline-success').addClass('btn-outline-success')
		if (this.id=="btn_danger")
			$("#"+this.id).removeClass('btn-danger').removeClass('btn-outline-danger').addClass('btn-outline-danger')
		if (this.id=="btn_warning")
			$("#"+this.id).removeClass('btn-warning').removeClass('btn-outline-warning').addClass('btn-outline-warning')
		if (this.id=="btn_info")
			$("#"+this.id).removeClass('btn-info').removeClass('btn-outline-info').addClass('btn-outline-info')
		if (this.id=="btn_dark")
			$("#"+this.id).removeClass('btn-dark').removeClass('btn-outline-dark').addClass('btn-outline-dark')
	})	
}

function set_bottoni(class_btn_action,value) {
	$(".bottoni").each(function(){
		if (this.id=="btn_primary" && (class_btn_action=="primary" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-primary').removeClass('btn-primary').addClass('btn-primary')
		
		if (this.id=="btn_secondary" && (class_btn_action=="secondary" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-secondary').removeClass('btn-secondary').addClass('btn-secondary')
		
		if (this.id=="btn_success" && (class_btn_action=="success" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-success').removeClass('btn-success').addClass('btn-success')

		if (this.id=="btn_danger" && (class_btn_action=="danger" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-danger').removeClass('btn-danger').addClass('btn-danger')	
				
		if (this.id=="btn_warning" && (class_btn_action=="warning" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-warning').removeClass('btn-warning').addClass('btn-warning')
		
		if (this.id=="btn_info" && (class_btn_action=="info" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-info').removeClass('btn-info').addClass('btn-info')	
		
		if (this.id=="btn_dark" && (class_btn_action=="dark" || this.id==value))
			$("#"+this.id).removeClass('btn-outline-dark').removeClass('btn-dark').addClass('btn-dark')	
	})	
}

function schema(has_c,id,parent_id_origin) {
	$("#btn_sposta").show();
	if (id=="0") $("#btn_sposta").hide()
	child=$("#id_btn"+id).data("child")

		
	$(".btn_schema").removeClass('btn-secondary').addClass('btn-outline-primary')

	$(".btn_schema").removeClass('btn-primary').addClass('btn-outline-primary')
	
	$("#id_btn"+id).removeClass('btn-outline-primary').addClass('btn-secondary')
	


	$(".btn_schema").prop('disabled',false)
	$("#id_btn"+id).prop('disabled',true)


	/*
	if (child=="child") {
		$(".btn_schema").prop('disabled',true)
		$(".child").prop('disabled',false)
		$("#id_btn"+id).prop('disabled',true)
	}
	*/
	
	$("#id_up_schema").val(id)
	$("#parent_id_origin").val(parent_id_origin)
	if (has_c="1") {
		$("#fold"+id).removeClass("fas fa-folder-minus").addClass('fas fa-folder-plus')
		$("#mnu"+id).hide(50)
	}

	
	$('#div_schema').show(120)
}
