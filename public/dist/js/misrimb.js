
var app = Vue.component('Rimb',{
	template: 
		`<div class='container-fluid' id='div_rimb' v-if='view_root==true'>
			
			
				<p v-if="mezzi">
					<button type="button" class="btn btn-primary" onclick="$('#rif').hide(); $('#app').show();">Torna su elenco appalti</button>
                   
					<h5>DEFINIZIONE NUOVO RIMBORSO</h5>
					<div class="form-group">
						<label for='tipo_rimborso' >Scegli tipologia*</label>
						
						<!-- v-on:change="change_mezzo($event)"!-->

						<select class="form-control"  v-model="tipo_rimborso">
							<option value=''>Select...</option>
							<option v-for="tipo in tipo_rimborsi" :value="tipo.id">
								{{tipo.descrizione}}
							</option>
						</select>
						

					</div>	

					<div class="form-group">
						<label>Data Ora*</label>
						<input type="datetime-local" class="form-control" v-model='data_ora' placeholder="Data">
					</div>					
					
					<div class="form-group">
						<label>Importo</label>
						<input type="number" @keydown="checkDigit" class="form-control" v-model='importo' placeholder="Importo">
					</div>					


					<div class="form-group">
						<label>Scegli un file o scatta foto</label>
						<div class="input-group mb-3">
							<input type="file" id='fileInput' class="form-control" @change="uploadFile($event)">
				  		</div>
					</div>
					<div>
						<button v-show="!sendreal" type="button" class="btn btn-success" :disabled="sendko" @click='send_new_rimb()'>Salva richiesta</button>
											
						<button type="button" class="btn btn-secondary"  onclick="$('#div_servizi').show(150);rimborsi.view_root=false;rimborsi.new_form_rimb()">Esci</button>
						<span class='ml-3' v-show="sendko"><i class="fas fa-spinner fa-spin"></i></span>

						<div v-show="sendreal" class="alert alert-success mt-2" role="alert">
							Segnalazione rimborso inviata con successo!<hr>
							<button type="button" class="btn btn-primary" @click='new_form_rimb()'>Nuovo rimborso</button>
					  	</div>						
						

					</div>
				</p>
			
		</div>
	`,
	
	data() {
		let view_root=false;
		let mezzi= null; 
		let tipo_rimborsi=null
		let tipo_rimborso=null
		let sendko=false;
		let sendreal=false;
		let data_ora=null
		let importo=null;
		let file=null
		
		return {
			view_root,
			mezzi,
			tipo_rimborsi,
			tipo_rimborso,
			file,
			sendko,
			sendreal,
			data_ora,
			importo
		};
	},
    mounted: function () {
		window.rimborsi=this;
		this.elenco_rimborsi()
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
			tipo_rimborso=this.tipo_rimborso
			importo=this.importo
			data_ora=this.data_ora
			
			if (!importo || !tipo_rimborso || !importo || !data_ora) return false
			if (file==null) return "nofile"
		},

		elenco_rimborsi() {
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			
			fetch('elenco_rimborsi', {
			  method: 'POST',
			  headers: {
				//"Content-type": "multipart/form-data",
				"X-CSRF-Token": csrf
			  },
			  body: "from=rimborsi",
			})
			.then(response => {
				if (response.ok) {
				   return response.json();
				}
			})
			.then(response=>{
				if (!response) {
					
				}
				else {
					this.tipo_rimborsi=response
				}
			})
			.catch(status, err => {
				return console.log(status, err);
			})					
		},

		new_form_rimb() {
			this.tipo_rimborso=null;
			this.data_ora=null;
			this.importo=null;
			this.file=null;
			this.sendreal=false;
			this.sendko=false;
			document.querySelector('#fileInput').value = ''
		},
		
		checkDigit (event) {
			if (event.key!=".") {
				if (event.key.length === 1 && isNaN(Number(event.key))) {
				event.preventDefault();
				}
			}
		},

		send_new_rimb() {
			
			check=this.check_ins()
			if (check=="nofile") {
				alert("Scattare una foto o sceglierne una dalla galleria")	
				return false
			}
			else if (check==false) {
				alert("I campi contrassegnati con * sono obbligatori e devono essere valorizzati")
				return false
			} 
			if (!confirm("Sicuri di inviare la segnalazioni dei rimborso?")) return false;
			this.sendko=true
			var data = new FormData()
			data.append('tipo_rimborso', this.tipo_rimborso)
			data.append('data_ora', this.data_ora)
			data.append('importo', this.importo)
			data.append('file', file)

			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			
			fetch('send_rimborso', {
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
				if (!response) {
					alert ("Errore generico! Assicurati di inviare immagini (no .pdf, .doc, etc.)")
					this.sendko=false
				}
				else {
					if (response.header=="KO") 
						alert (response.message)
					else  {
						this.sendreal=true
						alert("Segnalazione inviata con successo!")
					}	
					this.sendko=false
				}
			})
			.catch(status, err => {
				return console.log(status, err);
			})				

		},

		uploadFile(event) {
			file = event.target.files[0];
			this.file=file
		},
		

		new_rimb() {
		

		}		
	}	
});

rx=new Vue ({
	el:"#rimb"
});

function clickitrimb(from) {
	$("#div_servizi").hide(150)
	if (from=="New") {
		window.rimborsi.view_root=true
	} 
 }