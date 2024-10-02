$(document).ready( function () {
	$("#credit_top").hide()
	$('.notif').each(function () {
		value=$(this).html()
		if (value.trim()==0) $(this).hide()
	})	
	$("#id_back").hide();
} );



var app = Vue.component('App',{
	template: 
		`<div class='container-fluid'>
			<div v-if='view_root==true'>
				<p v-if="refr_page==false && resp">
					<button type="button" class="btn btn-primary" onclick="$('#div_servizi').show();appalti.resp=null">Home servizi</button>
				</p>	

				<p v-if="refr_page==true">
					<a href='misapp'>
						<button type="button" class="btn btn-warning">Home servizi <i class="fas fa-sync-alt"></i></button>
					</a>
				</p>	

				<p v-if="resp!=null">
					<box-appalto :key="l.id" v-for="l in resp['lavori']"  v-bind:item='l' v-bind:rsp='resp'></box-appalto>
				</p>
			</div>

			<Detail></Detail>
		</div>
	`,
	data() {
		
		let refr_page=false;
		let view_root=true
		let resp= null; 
		let tipo_ricerca=0;
		
		return {
			view_root,
			resp,
			refr_page,
			tipo_ricerca
		};
	},
    mounted: function () {
		window.appalti=this;

    },	
	methods: {

		events(from) {
			
			$("#div_servizi").hide(150)
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("lavori", {
				method: 'post',
				headers: {
				  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
				  "X-CSRF-Token": csrf
				},
				body: "from="+from,
			})
			.then(response => {
				if (response.ok) {
				   return response.json();
				}
			})
			.then(resp=>{
				this.tipo_ricerca=from
				this.resp=resp
			})
			.catch(status, err => {
				return console.log(status, err);
			})	
		}		
	}	
});



Vue.component("box-appalto",{
	
    mounted: function () {
        window.appalto=this;
    },		
	methods: {
		view_dettaglio(id_appalto) {
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("infoappalti", {
				method: 'post',
				headers: {
				  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
				  "X-CSRF-Token": csrf
				},
				body: "id_ref_a="+id_appalto,
			})
			.then(response => {
				if (response.ok) {
				   return response.json();
				}
			})
			.then(infoappalto=>{
				detail.infoappalto=infoappalto
				detail.dettaglio=true;
				appalti.view_root=false				
			})
			.catch(status, err => {
				return console.log(status, err);
			})	

		},
		request_risp(obj,id_appalto,sn,ind_resp) {
			
			let st=1
			acc="accettare"
			if (sn=="N") {st=2;acc="rifiutare"}
			if (!confirm("Sicuri di "+acc+" la richiesta?")) return false;
			this.btnDis=true
			appalti.refr_page=true
			//<meta name="csrf-token" content="{{{ csrf_token() }}}"> //da inserire in html
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("risposta_user", {
				method: 'post',
				headers: {
				  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
				  "X-CSRF-Token": csrf
				},
				body: "id_appalto="+id_appalto+"&sn="+sn,
			})
			.then(response => {
				if (response.ok) {
				   obj['lavori'][ind_resp].status=st
				   resp=obj
				   appalti.resp=resp
				   return response.json();
				}
			})
			.then(risposta=>{		
				resp=Array()
			})
			.catch(status, err => {
				return console.log(status, err);
			})				

		},
		appsel(id_appalto,targa) {
			loadrif(id_appalto,targa)
		}
	},	
	data() {
		let btnDis=false;
		return {
			btnDis
		};
	},

	template:`
		<div class="card">
			<div class="card-header">
				<div v-if="item.status==0" >
					<h5><font color='yellow'><i class="fas fa-circle"></i></font></h5>
				</div>
				<div v-if="item.status==1">
					<h5><font color='success'><i class="fas fa-circle"></i></font></h5>
				</div>
				<div v-if="item.status==2">
					<h5><font color='red'><i class="fas fa-circle"></i></font></h5>
				</div>
			
				<p class="d-inline-flex gap-1" v-if="item.status==0">
					<button type="button" :disabled=btnDis v-on:click="request_risp(rsp,item.id,'S',item.indice)" class="btn btn-outline-success">Accetta</button>
					<button type="button" :disabled=btnDis v-on:click="request_risp(rsp,item.id,'N',item.indice)" class="btn btn-outline-danger">Rifiuta</button>
				</p>		

				<h4>{{item.id}}</h4>
			</div>
			<div class="card-body">
				<h5 class="card-title">
					{{item.data_ref}} {{item.orario_ref}}
				</h5>
				<p class="card-text">
					
				</p>
				
				<a href="#" class="btn btn-success btn-lg btn-block" @click='view_dettaglio(item.id)'>Dettagli</a>
				
				<a href="#" @click="appsel(item.id,item.targa)" class="btn btn-primary  btn-block"><small>Nuovo rifornimento per l'appalto</small></a>
				<a :href="'sinistri/'+item.id+'/1'" class="btn btn-danger btn-block"><small>Nuovo sinistro su questo appalto</small></a>
				
				
			</div>
		</div>	
	`,
	props: ['item','rsp']
})


Vue.component('Detail',{

	template:`
		<div v-if="dettaglio==true" class='container-fluid'>
			<div class="d-grid gap-2">
				<button type="button" class="btn btn-primary" @click="home_appalti">Torna su elenco appalti</button>
			</div>	
			
			

			<div class="mt-2 alert alert-info" role="alert">
				Riferimento Appalto: <b>{{infoappalto.info[0].id_appalto}}</b> 
				<span v-if="infoappalto.info[0].status==0" ><i class="ml-3 fas fa-circle fa-lg" style="color: #FFD43B;"></i></span>
				<span v-if="infoappalto.info[0].status==1" ><i class="ml-3 fas fa-circle fa-lg" style="color: #63f852;"></i></span>
				<span v-if="infoappalto.info[0].status==2" ><i class="ml-3 fas fa-circle fa-lg" style="color: #ff0000;"></i></span>
			</div>
			<hr>
			<div v-if="infoappalto.info[0].luogo_incontro">
				<p class="h6 opacity-25">Luogo incontro</p>
				<p class="h5">{{infoappalto.info[0].luogo_incontro}}</p>
			</div>

			<div v-if="infoappalto.info[0].orario_incontro">
				<p class="h6 opacity-25">Orario incontro</p>
				<p class="h5">{{infoappalto.info[0].orario_incontro}}</p>
			</div>

			<div v-if="infoappalto.info[0].chiesa">
				<p class="h6 opacity-25">Chiesa</p>
				<p class="h5">{{infoappalto.info[0].chiesa}}</p>
			</div>

			<div v-if="infoappalto.info[0].data_ref">
				<p class="h6 opacity-25">data</p>
				<p class="h5">{{infoappalto.info[0].data_ref}}</p>
			</div>			

			<div v-if="infoappalto.info[0].ditta">
				<p class="h6 opacity-25">Ditta</p>
				<p class="h5">{{infoappalto.info[0].ditta}}</p>
			</div>			

			<div v-if="infoappalto.info[0].lavoratori">
				<p class="h6 opacity-25">Squadra</p>
				<p class="h5">{{infoappalto.info[0].lavoratori}}</p>
			</div>
			
			<div v-if="infoappalto.info[0].targa">
				<p class="h6 opacity-25">Targa mezzo</p>
				<p class="h5">{{infoappalto.info[0].targa}}</p>
			</div>
			<div v-if="infoappalto.info[0].responsabile_mezzo">
				<p class="h6 opacity-25">Responsabile mezzo</p>
				<p class="h5">{{infoappalto.info[0].responsabile_mezzo}}</p>
			</div>

			<div v-if="infoappalto.info[0].note">
				<p class="h6 opacity-25">Note</p>
				<p class="h5">{{infoappalto.info[0].note}}</p>
			</div>			


		</div>
	`,
	data() {
		let dettaglio=false;
		let infoappalto=null;
		return {
			infoappalto,
			dettaglio
		};
	},
	methods: {
		home_appalti() {
			detail.dettaglio=false;
			appalti.view_root=true
		},
		view(){

			
		}
	},
    mounted: function () {
        window.detail=this;
    },	
	
	
});



ev=new Vue ({
	el:"#app"
});	

function clickit(from) {
	window.appalti.events(from);    
 }
