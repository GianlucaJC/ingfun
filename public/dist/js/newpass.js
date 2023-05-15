$(document).ready( function () {
});

function showp() {

  var x = document.getElementById("pw_first");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }

  var x = document.getElementById("pw_ripeti");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}


function change_p() {
	pw_first=$("#pw_first").val();
	pw_ripeti=$("#pw_ripeti").val();
	

	if (pw_first.length<8 || pw_ripeti.length<8 )  {
		event.preventDefault();
		alert("La password deve essere di almeno 8 caratteri!");
	}		
	else if (pw_first!=pw_ripeti) {
		event.preventDefault();
		alert("Le due password non coincidono!");
	}
	
	
	
}
