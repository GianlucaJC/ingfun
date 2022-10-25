$(document).ready( function () {
});

function azzera_notif() {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/azzera_notif",
		data: {_token: CSRF_TOKEN},
		success: function (data) {
			
		}
	});		
}

