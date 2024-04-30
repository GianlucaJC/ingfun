$(document).ready( function () {
	$("#credit_top").hide()
	$('.notif').each(function () {
		value=$(this).html()
		if (value.trim()==0) $(this).hide()
	})	

} );



var app = Vue.component('App',{
	template: 
		`<div class='container-fluid'>
			<p v-if="refr_page==false && resp">
				<button type="button" class="btn btn-primary" onclick="$('#div_servizi').show();appalti.resp=null">Home servizi</button>
			</p>	

			<p v-if="refr_page==true">
				<a href='misapp'>
					<button type="button" class="btn btn-warning">Home servizi <i class="fas fa-sync-alt"></i></button>
				</a>
			</p>	

			<p v-if="resp!=null">
				<product-box :key="l.id" v-for="l in resp['lavori']"  v-bind:item='l' v-bind:rsp='resp'></product-box>
			</p>
		</div>
	`,
	data() {
		
		let refr_page=false;
		let resp= null; 
		let tipo_ricerca=0;
		return {
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



Vue.component("product-box",{
    mounted: function () {
        window.appalto=this;
    },		
	methods: {
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

				<h4>{{item.descrizione_appalto}}</h4>
			</div>
			<div class="card-body">
				<h5 class="card-title">
					{{item.data_ref}} {{item.orario_ref}}
				</h5>
				<p class="card-text">
					
				</p>
				<a href="#" class="btn btn-success">Dettagli</a>
				<a href="#" class="ml-2 btn btn-primary">Rifornimenti</a>
				<a href="#" class="ml-2 btn btn-danger">Sinistri</a>
			</div>
		</div>	
	`,
	props: ['item','rsp']
})

ev=new Vue ({
	el:"#app"
});	

function clickit(from) {
	window.appalti.events(from);    
 }
