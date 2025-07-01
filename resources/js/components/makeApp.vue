<template>
								
    <div >

        <div class="tabella">

            <table border="1" cellspacing="0">
			<tbody>
                <tr>
                    <td class="nomi" rowspan="3">
						<div class="drop-zone" @drop="onDrop($event, 1)"  @dragover.prevent  @dragenter.prevent>
							<div class="drag-el" v-for="lavoratore in lavoratori">
								<a href="#" draggable="true"  @dragstart="startDrag($event, lavoratore)">
									{{lavoratore.nominativo}}
								</a>
							</div>
						</div>
                    </td>                        
                  
                </tr>
                <tr>
                    <td class='testo'>
						<div class="drop-zone" @drop="onDrop($event, 1)"  @dragover.prevent  @dragenter.prevent>
							<div class="drag-el" style="border:1px solid"> 
							</div>
						</div>
					</td>
                </tr>
			</tbody>	
            </table>
        </div>
    </div>
  </template>
  
<style>
body{
    display: flex;
    justify-content: start;
    flex-direction: column;
}

.tabella{
    overflow-x: auto;
}

table{
    height: 100vh;
    border: 1px solid black;
    width: 100%;
    table-layout:auto;

    td{
        min-width: 150px;
        height: 50px;
        text-align: center;

        textarea{
            height: 100%;
            width: 100%;
        }
    }
}

.testo{
    height: 80%;
}

.nomi{
    width: fit-content;
}
.drop-zone {
  background-color: #ebf1ef;
  margin-bottom: 10px;
  padding: 10px;
}

.drag-el {
  background-color: #cdf1c9;
  margin-bottom: 10px;
  padding: 5px;
}
</style>

  <script>
  export default {
    props: ['testFrom'],
    data() {
	   let lavoratori;

      return {
		lavoratori,

      }
    },
	mounted: function () {
		window.Regole=this;
		this.load_modello()
	},	
	watch:{
		DBmod(edit_pattern,oldval) {
			//if (newval=="-") this.DBmodelli=""
			//else this.DBmodelli=newval
		},

	},		
    methods: { 
		startDrag(evt, item) {
		evt.dataTransfer.dropEffect = 'move'
		evt.dataTransfer.effectAllowed = 'move'
		evt.dataTransfer.setData('itemID', item.id)
		},
		onDrop(evt, list) {
		const itemID = evt.dataTransfer.getData('itemID')
		const item = this.items.find((item) => item.id == itemID)
		item.list = list
		},	
	  load_modello() {
			const metaElements = document.querySelectorAll('meta[name="csrf-token"]');
			const csrf = metaElements.length > 0 ? metaElements[0].content : "";			
			fetch("load_modello", {
				method: 'post',
				headers: {
					"Content-type": "application/x-www-form-urlencoded; charset=UTF-8",
					"X-CSRF-Token": csrf
				},
				body: "load_modello=1"
			})
			.then(response => {
				if (response.ok) {
					return response.json();
				}
			})
			.then(resp=>{
				console.log(resp)
				console.log("resp.header",resp.header)
				
				if (resp.header=='OK') {
					this.lavoratori=resp.lavoratori
					//athis.emptyinfo();	

				}
					


			})
			.catch(status, err => {
				return console.log(status, err);
			})
				
	  },
     
    }
  }
  </script>
  
<style>

</style>