	var video, canvas, ctx;
	if(navigator && navigator.mediaDevices){
		const options = { audio: false, video: { facingMode: "user", width: 300, height: 300  } }
		navigator.mediaDevices.getUserMedia(options)
		.then(function(stream) {
			video = document.querySelector('video');
			video.srcObject = stream;
			video.onloadedmetadata = function(e) {
			  video.play();
			};
			canvas = document.getElementById("myCanvas");
			ctx = canvas.getContext('2d');
		})
		.catch(function(err) {
			//Handle error here
		});
	}else{
		console.log("camera API is not supported by your browser")
	}


(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
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


} );

	function clickPhoto() {
	   ctx.drawImage(video, 0,0, canvas.width, canvas.height);
	 }	


function save() {
	if (!confirm("Sicuri di confermare il sinistro?"))
		event.preventDefault()
}


