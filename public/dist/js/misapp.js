$(document).ready( function () {
	$("#credit_top").hide()
	$('.notif').each(function () {
		value=$(this).html()
		if (value.trim()==0) $(this).hide()
	})	

} );

//$('#div_lavori').hide();$('#div_servizi').show()
var app = Vue.component('App',{
	template: 
		`<div class='container-fluid' id='div_lavori'>
			<p v-if="resp">	
			
				<button type="button" class="btn btn-primary" onclick="$('#div_lavori').hide();$('#div_servizi').show()">Home servizi</button>
			</p>	

			<p v-if="resp!=null">
				<product-box :key="l.id" v-for="l in resp['appalti']['lavori']"  v-bind:item='l'></product-box>
			</p>
		</div>
	`,
	data() {
		let resp= null; 
		return {
			resp
		};
	},
    mounted: function () {
        window.appalti=this;
    },	
	methods: {
		events(from) {
			$('#div_lavori').show();
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
			.then(risposta=>{		
				resp=Array()
				resp['appalti']=risposta
				this.resp=resp
			})
			.catch(status, err => {
				return console.log(status, err);
			})	
		}		
	}	
});



Vue.component("product-box",{
	template:`
		<div class="card">
			<div class="card-header">
			
				<div v-if="item.status==0">
					<h5><font color='yellow'><i class="fas fa-circle"></i></font></h5>
				</div>
				<div v-if="item.status==1">
					<h5><font color='success'><i class="fas fa-circle"></i></font></h5>
				</div>
				<div v-if="item.status==2">
					<h5><font color='red'><i class="fas fa-circle"></i></font></h5>
				</div>
			

				<h4>{{item.descrizione_appalto}}</h4>
			</div>
			<div class="card-body">
				<h5 class="card-title">
					{{item.data_ref}} {{item.orario_ref}}
				</h5>
				<p class="card-text">
					
				</p>
				<a href="#" class="btn btn-success">Dettagli</a>
				<a href="#" class="ml-3 btn btn-primary">Rifornimenti</a>
				<a href="#" class="ml-3 btn btn-danger">Sinistri</a>
				
			</div>
		</div>	
	`,
	props: ['item']
})

ev=new Vue ({
	el:"#app"
});	

function clickit(from) {
	window.appalti.events(from);    
 }
