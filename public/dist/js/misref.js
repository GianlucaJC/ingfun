
var app = Vue.component('Rif',{
	template: 
		`<div class='container-fluid' id='div_rif' style='display:none'>
			
			<div v-if='view_root==true'>
				<p v-if="mezzi">
					<button type="button" class="btn btn-primary" onclick="$('#rif').hide(); $('#app').show();">Torna su elenco appalti</button>
                   
					<hr>
					<h5>RIFORNIMENTO PER APPALTO {{appalto_ref}}</h5>
					<div class="form-group">
						<label for="mezzo_ass">Mezzo associato</label>
						<select class="form-control" id="mezzo_ass" v-model="selected">
							<option v-for="mezzo in mezzi" :value="mezzo.targa">
								{{ mezzo.marca }} - {{mezzo.modello}} - {{mezzo.targa}}
							</option>
						</select>

					</div>					
				</p>
			</div>
		</div>
	`,
	
	data() {
		let appalto_ref=null;
		let view_root=true;
		let mezzi= null; 
		let selected=null;

		
		return {
			appalto_ref,
			view_root,
			mezzi,
			selected
		};
	},
    mounted: function () {
        window.rifornimenti=this;
    },	
	methods: {

		loadrif(id_appalto,targa) {
			this.selected=targa
			this.appalto_ref=id_appalto
			//$("#div_servizi").hide(150)
            $("#app").hide();

			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("mezzi", {
				method: 'post',
				headers: {
				  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
				  "X-CSRF-Token": csrf
				},
				body: "id_appalto="+id_appalto,
			})
			.then(response => {
				if (response.ok) {
				   return response.json();
				}
			})
			.then(mezzi=>{

				this.mezzi=mezzi
			})
			.catch(status, err => {
				return console.log(status, err);
			})	
		}		
	}	
});

ex=new Vue ({
	el:"#rif"
});
function loadrif(id_appalto,targa) {
	$("#div_rif").show()
	$('#rif').show();
	window.rifornimenti.loadrif(id_appalto,targa);    
 }
