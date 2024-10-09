
var app = Vue.component('Rimb',{
	template: 
		`<div class='container-fluid' id='div_rimb' v-if='view_root==true'>
			
			
				<p v-if="mezzi">
					<button type="button" class="btn btn-primary" onclick="$('#rif').hide(); $('#app').show();">Torna su elenco appalti</button>
                   
					<div v-show='id_edit_rimborso==0'>
						<h5>DEFINIZIONE NUOVO RIMBORSO</h5>
					</div>

					<div v-show='id_edit_rimborso!=0'>
						<h5>MODIFICA RIMBORSO COME DA RICHIESTA DI RETTIFICA</h5>
					</div>

					
					<div class="form-group">
						<label for='tipo_rimborso' >Scegli tipologia*</label>
						
						<!-- v-on:change="change_mezzo($event)"!-->

						<select class="form-control"  v-model="tipo_rimborso"  @change="check_obbligo($event)">
							<option value=''>Select...</option>
							<option v-for="(tipo) in tipo_rimborsi" :value="tipo.id_ref">
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


					<div class="form-group" v-if="obbligo_foto==1">
						<label>Scegli un file o scatta foto</label>
						<div class="input-group mb-3">
							<input type="file" id='fileInput' class="form-control" @change="uploadFile($event)">
				  		</div>
					</div>


					<div>
						<button v-show="!sendreal" type="button" class="btn btn-success" :disabled="sendko || wait_send" @click='send_new_rimb()'>Salva richiesta</button>
											
						<button v-show="!sendreal && id_edit_rimborso==0" type="button" class="btn btn-secondary"  onclick="$('#div_servizi').show(150);rimborsi.view_root=false;rimborsi.new_form_rimb()">Esci</button>

						<button v-show="sendreal && id_rimborso==0" type="button" class="btn btn-secondary" onclick="location.reload(); ">Esci (con refresh)</button>

						<span class='ml-3' v-show="sendko"><i class="fas fa-spinner fa-spin"></i></span>

						<div v-show="sendreal" class="alert alert-success mt-2" role="alert">
							Segnalazione rimborso inviata con successo!<hr>
							<div v-show="id_rimborso==0">
								<button type="button" class="btn btn-primary" @click='new_form_rimb()'>Nuovo rimborso</button>
							</div>
					  	</div>						
						

					</div>
				</p>
			
		</div>
	`,
	
	data() {
		let id_rimborso=0;
		let id_edit_rimborso=0;
		let obbligo_foto=0;
		let view_root=false;
		let mezzi= null; 
		let tipo_rimborsi=null
		let tipo_rimborso=null
		let wait_send=false;
		let sendko=false;
		let sendreal=false;
		let data_ora=null
		let importo=null;
		let file=null
		
		return {
			id_rimborso,
			id_edit_rimborso,
			obbligo_foto,
			view_root,
			mezzi,
			tipo_rimborsi,
			tipo_rimborso,
			file,
			wait_send,
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
	watch:{
		id_edit_rimborso(newval,oldval) {
			if (newval!="0") {
				/*
					modifica rimborso come da rettifica richiesta
					id_edit_rimborso viene triggerato in misapp.js
					il tutto proviene da una mail che contiene l'id nella URL
					Quindi con una chiamata fetch recupero le info legate all'id e precarico la scheda del rimborso
				*/
				
				this.rettifica(newval)

			}
		}
	},	


	methods: {

		/*
		change_mezzo: function(e){
			//var id = e.target.value;
			mezzo = e.target.options[e.target.options.selectedIndex].text;
			this.mezzo=mezzo
		},
		*/

		rettifica(id_rimborso) {
			this.id_rimborso=id_rimborso
			this.wait_send=true
			base_path = $("#url").val();
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch(base_path+"/load_rimborso/"+id_rimborso, {
			  method: 'POST',
			  headers: {
				//"Content-type": "multipart/form-data",
				"X-CSRF-Token": csrf
			  },
			  body: "id_rimborso="+id_rimborso,
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
					this.wait_send=false

					//this.tipo_rimborso=response[0].id_rimborso
					this.importo=response[0].importo
					this.data_ora=response[0].dataora
					//this.tipo_rimborsi=response
				}
			})
			.catch(status, err => {
				return console.log(status, err);
			})			
			
		},
		check_obbligo(event) {
			info=event.target.value
			if (info.length>0) {
				this.obbligo_foto=info.split("|")[1]
			}
		},

		check_ins() {
			file=this.file
			tipo_rimborso=this.tipo_rimborso
			importo=this.importo
			data_ora=this.data_ora
			
			if (!importo || !tipo_rimborso || !importo || !data_ora) return false
			if (this.obbligo_foto==1) {
				if (file==null) return "nofile"
			}	
		},
		
		elenco_rimborsi() {
			base_path = $("#url").val();
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch(base_path+"/elenco_rimborsi", {
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
			
			tipo_r=this.tipo_rimborso.split("|")[0]
			
			data.append('id_rimborso', this.id_rimborso)
			data.append('tipo_rimborso', tipo_r)
			data.append('data_ora', this.data_ora)
			data.append('importo', this.importo)
			data.append('file', file)
			data.append('obbligo_foto', this.obbligo_foto)
			base_path = $("#url").val();
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			
			fetch(base_path+'/send_rimborso', {
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