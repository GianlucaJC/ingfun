
var app = Vue.component('Rif',{
	template: 
		`<div class='container-fluid' id='div_rif' style='display:none'>
			
			<div v-if='view_root==true'>
				<p v-if="mezzi">
					<button type="button" class="btn btn-primary" onclick="$('#rif').hide(); $('#app').show();">Torna su elenco appalti</button>
                   
					<hr>
					<h5>RIFORNIMENTO PER APPALTO {{appalto_ref}}</h5>
					<div class="form-group">
						<label >Mezzo associato*</label>
						
						<!-- v-on:change="change_mezzo($event)"!-->

						<select class="form-control"  v-model="targa">
							<option v-for="mezzo in mezzi" :value="mezzo.targa">
								{{mezzo.marca}}-{{mezzo.modello}}-{{mezzo.targa}}
							</option>
						</select>
						

					</div>	

					<div class="form-group">
						<label>Importo*</label>
						<input type="text" class="form-control" v-model='importo' placeholder="Importo">
					</div>					
					<div class="form-group">
						<label>Km*</label>
						<input type="text" class="form-control" v-model='km' placeholder="km">
					</div>					
					<div class="form-group">
						<label>Note</label>
						<input type="text" class="form-control" v-model='note' placeholder="Note">
					</div>					


					<div class="form-group">
						<label>Scegli un file o scatta foto scontrino e targa</label>
						<div class="input-group mb-3">
							<input type="file" class="form-control" @change="uploadFile($event)">
				  		</div>
					</div>
					<div>
						<button v-show="!sendreal" type="button" class="btn btn-success" :disabled="sendko" @click='send_segn()'>Invia segnalazione</button>
						<span class='ml-3' v-show="sendko"><i class="fas fa-spinner fa-spin"></i></span>
						
						<div v-show="sendreal" class="alert alert-success" role="alert">
							Segnalazione inviata con successo!<hr>
							<button type="button" class="btn btn-primary" @click='new_segn()'>Nuova segnalazione</button>
					  	</div>						
						

					</div>
				</p>
			</div>
		</div>
	`,
	
	data() {
		let appalto_ref=null;
		let view_root=true;
		let mezzi= null; 
		let mezzo=null;
		let targa=null;
		let importo=null;
		let km=null;
		let note=null;
		let file=null
		let sendko=false;

		let sendreal=false;
		
		return {
			appalto_ref,
			view_root,
			mezzi,
			targa,
			importo,
			km,
			note,
			file,
			sendko,
			sendreal
		};
	},
    mounted: function () {
        window.rifornimenti=this;
    },	
	methods: {

		/*
		change_mezzo: function(e){
			//var id = e.target.value;
			mezzo = e.target.options[e.target.options.selectedIndex].text;
			this.mezzo=mezzo
		},
		*/

		check_ins() {
			file=this.file
			importo=this.importo
			km=this.km
			targa=this.targa
			if (!importo || !km || !targa) return false
			if (file==null) return "nofile"
		},

		new_segn() {
			this.importo=null;
			this.km=null;
			this.note=null;
			this.file=null;
			this.sendreal=false;
			this.sendko=false;
		},
		send_segn() {
			
			check=this.check_ins()
			if (check=="nofile") {
				alert("Scattare una foto o sceglierne una dalla galleria")	
				return false
			}
			else if (check==false) {
				alert("I campi contrassegnati con * sono obbligatori e devono essere valorizzati")
				return false
			} 
			this.sendko=true
			var data = new FormData()
			data.append('file', file)
			data.append('id_appalto', this.appalto_ref)
			data.append('importo', this.importo)
			data.append('km', this.km)
			data.append('note', this.note)
			data.append('targa', this.targa)

			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			
			fetch('send_foto', {
			  method: 'POST',
			  headers: {
				//"Content-type": "multipart/form-data",
				"X-CSRF-Token": csrf
			  },
			  body: data
			})
			.then(response => {
				if (response.ok) {
				   return response.json();
				}
			})
			.then(response=>{
				if (response.header=="KO") 
					alert (response.message)
				else  {
					this.sendreal=true
					alert("Segnalazione inviata con successo!")
				}	
				this.sendko=false
			})
			.catch(status, err => {
				return console.log(status, err);
			})				

		},

		uploadFile(event) {
			file = event.target.files[0];
			this.file=file
		},
		

		loadrif(id_appalto,targa) {
			this.targa=targa
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
