const numBox=6
const elemBox=6
const maxI=2

var _m_e="?";var _box="?";var _el="?"
 $(function () {
    $('body').addClass("sidebar-collapse");
    $('#cerca_ditta').on("change keyup paste", function(){
        trova_ditta()
    })
    $('#cerca_nome').on("change keyup paste", function(){
        trova_nome()
    })
    $('#cerca_mezzo').on("change keyup paste", function(){
        trova_mezzo()
    })
    id_giorno_appalto=$("#id_giorno_appalto").val()
    load_appalti(id_giorno_appalto)
    load_ini_lav();
    setZoom(1)

} );

function dragstartHandler(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function dragoverHandler(ev) {
  dest=ev.target.id
  _m_e=$("#"+dest).data('m_e')
  _box=$("#"+dest).data('box')
  _el=$("#"+dest).data('el')
  ev.preventDefault();
}

function dropHandler(ev) {
  ev.preventDefault();
  const from = ev.dataTransfer.getData("text");
  idlav=$("#"+from).data('idlav')
  impegnalav(idlav)
  console.log("dati dell'impegno: _m_e",_m_e,"_box",_box,"_el",_el)
  setsquadra(_m_e,_box,_el)
}


function load_appalti(id_giorno_appalto) {
    maxM=$("#maxM").val()
    maxP=$("#maxP").val()
    ref=numBox
    if (maxM>numBox) ref=maxM
    console.log("ref after",ref)
    for (sca=0;sca<ref;sca++) {
        newapp('M','auto')
    }

    ref=numBox
    if (maxP>numBox) ref=maxP
    for (sca=0;sca<ref;sca++) {
        newapp('P','auto')
    }
}


function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function trova_ditta() {
    nome=$("#cerca_ditta").val()

    $(".allditte").hide()
    $(".allditte").each(function(){

        nome_ref=$(this).data('nome')
        nome_ref=nome_ref.toUpperCase()
        nome1=nome.toUpperCase();
        if (nome_ref.includes(nome1)) $(this).show()
    });    
}

function trova_nome() {
    nome=$("#cerca_nome").val()

    $(".allnomi").hide()
    $(".allnomi").each(function(){

        nome_ref=$(this).data('nome')
        nome_ref=nome_ref.toUpperCase()
        nome1=nome.toUpperCase();
        if (nome_ref.includes(nome1)) $(this).show()
    });    
}

function trova_mezzo() {
    nome=$("#cerca_mezzo").val()

    $(".allmezzi").hide()
    $(".allmezzi").each(function(){
        nome_ref=$(this).data('nome')
        nome_ref=nome_ref.toUpperCase()
        nome1=nome.toUpperCase();
        if (nome_ref.includes(nome1)) $(this).show()
    });    
}

function impegnalav(idlav) {
    $(".allnomi").removeClass('btn btn-success').addClass('btn btn-outline-success')
    $("#btnlav"+idlav).removeClass('btn btn-outline-success').addClass('btn btn-success')
    setsquadra.idlav=idlav
}

function unlock(idlav) {
    $('#btnlav'+idlav).prop('disabled',false);
    $('#unlock'+idlav).hide(120)
    
    removeA(setsquadra.unlock_id,idlav)
    setsquadra.unlock_id.push(idlav)
}

function setsquadra(m_e,box,rowbox) {
    if( typeof setsquadra.idlav == 'undefined' ) {
        alert("Scegliere prima un lavoratore da assegnare!")
        return false
    }
    if( typeof setsquadra.unlock_id == 'undefined' ) setsquadra.unlock_id=new Array()

    idlav=setsquadra.idlav
  

    present=false
    $(".box"+m_e+box).each(function(){
        id_ref=$(this).data( "idlav")
        if (id_ref==idlav) present=true
    })

    reflav="btnlav"+idlav
    nomelav=$("#"+reflav).text().trim()
    
    refbox="box"+m_e+box+rowbox
    remove=false
    if ($("#"+refbox).hasClass('active')) { 
        remove=true
        idlav=$("#"+refbox).data( "idlav")
        $("#"+refbox).removeClass('active')
        $("#"+refbox).first().html("Assegnabile")
        $("#"+refbox).removeData( "idlav", '' );
    }

    $("#btnlav"+idlav).prop("disabled",false)
    numpres=0;max=false
    flag_unlock=false
    $(".box").each(function(){
        id_ref=$(this).data( "idlav")
        if (id_ref==idlav) numpres++
        console.log("numpres",numpres)
        if (numpres>=maxI) {
            if (setsquadra.unlock_id.includes(idlav)) {
                removeA(setsquadra.unlock_id,idlav)
                flag_unlock=true
            } else {
                if (flag_unlock==false) max=true
            }
        }
    })    

    if (present==true || remove==true) {
       // alert("Il lavoratore selezionato è già presente in questo BOX appalto!")
        return false
    }
    


    if (max==false) {
        if (!$("#"+refbox).hasClass('active')) {
            $("#"+refbox).addClass('active')
            html=nomelav+" <span id='resp"+m_e+box+rowbox+"'></span>"
            $("#"+refbox).first().html(html)
            $("#"+refbox).data( "idlav", idlav );
            numpres++
            
        }
    } 
    if (numpres>=maxI) {
        $("#btnlav"+idlav).prop("disabled",true)
        $("#unlock"+idlav).show(120)
    }

}

function validation_form() {
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        var check_no=false
        if (!form.checkValidity()) {
          check_no=true
          event.preventDefault()
          event.stopPropagation()
        } 

        form.classList.add('was-validated')
        if (form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            save_appalto()
        }

      }, false)
    })
}

function load_ini_lav() {
    strall_info=$("#strall").val().split("|")
    for (sca=0;sca<strall_info.length;sca++) {
        m_e=strall_info[sca].split(";")[0]
        box=strall_info[sca].split(";")[1]
        id_lav=strall_info[sca].split(";")[2]
        rowbox=strall_info[sca].split(";")[3]
        if( typeof id_lav !== 'undefined' ) {
            if (id_lav!="0") {
                setsquadra.idlav=id_lav
                setsquadra(m_e,box,rowbox)
            }
        }
    }

}

function save_appalto() {
    $("#btn_save").prop('disabled',true)
    
    $("#btn_save").text('Salvataggio in corso...')
    id_giorno_appalto=$("#id_giorno_appalto").val()

    id_giorno_appalto=save_appalto.id_giorno_appalto
    m_e=save_appalto.m_e
    box=save_appalto.box

    info=$('#form_info').serialize();

    let CSRF_TOKEN = $("#token_csrf").val();
    html="<i class='fas fa-spinner fa-spin'></i>"
    $("#div_wait").html(html)
    base_path = $("#url").val();

    all_id_box=""
    $(".box"+m_e+box).each(function(){
        lav=$(this).data( "idlav")
        if (all_id_box.length>0) all_id_box+=";" 
        if( typeof lav === 'undefined' ) all_id_box+="0"
        else all_id_box+=lav
    
    })    
    timer = setTimeout(function() {	
      fetch(base_path+"/save_infoapp", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box+"&all_id_box="+all_id_box+"&"+info,
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(resp=>{
          if (resp.header=="OK") {
          $("#btnbox"+m_e+box)
            .removeClass('btn-outline-info')
            .removeClass('btn-info')
            .addClass('btn-info')

            $("#div_wait").empty()
            $("#modalinfo").modal('hide')
          }
          else {
            $("#div_wait").html("<font color='red'>Errore durante il salvataggio dei dati</font>")
          }

      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	

}
function save_info(id_giorno_appalto,m_e,box){
    save_appalto.id_giorno_appalto=id_giorno_appalto
    save_appalto.m_e=m_e
    save_appalto.box=box
}
function info_box(m_e,box) {
    $("#div_save").empty();
    id_giorno_appalto=$("#id_giorno_appalto").val()
    let CSRF_TOKEN = $("#token_csrf").val();
    html="<i class='fas fa-spinner fa-spin'></i>"
    $("#div_wait").html(html)
    base_path = $("#url").val();
    timer = setTimeout(function() {	
      fetch(base_path+"/check_allestimento", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box,
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(resp=>{
          if (resp.header=="OK") {
            $(".dati").val('');
            
            if (resp[0]) {
                $("#luogo_incontro").val(resp[0].luogo_incontro)
                $("#orario_incontro").val(resp[0].orario_incontro)
                $("#luogo_destinazione").val(resp[0].luogo_destinazione)
                $("#ora_destinazione").val(resp[0].ora_destinazione)
                $("#data_servizio").val(resp[0].data_servizio)
                $("#numero_persone").val(resp[0].numero_persone)
                $("#servizi_svolti").val(resp[0].servizi_svolti)
                $("#nome_salma").val(resp[0].nome_salma)
                $("#note").val(resp[0].note)
            }
            $("#div_wait").empty()
            html=`
                <button type="submit" id='btn_save' class="btn btn-primary" onclick="save_info(`+id_giorno_appalto+`,'`+m_e+`',`+box+`)">Salva dati appalto</button>
            `
            $("#div_save").html(html)
            validation_form()

          }
          else {
            $("#div_wait").html("<font color='red'>Errore durante il recupero dei dati</font>")
          }

      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	

}

function detail_appalto(m_e,box) {
    html=`
        <form id='form_info' method='post' action="" name='form_info' class="needs-validation" autocomplete="off" novalidate>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5">
                        <label for="luogo_incontro" class="col-form-label">Luogo incontro</label>
                        <input type="text" class="form-control dati" id="luogo_incontro" name='luogo_incontro' required>
                    </div>
                    <div class="col-md-2">
                        <label for="orario_incontro" class="col-form-label">Orario incontro</label>
                        <input type='time' class="form-control dati" id="orario_incontro" required name='orario_incontro'>
                    </div>
                    <div class="col-md-5">
                        <label for="luogo_destinazione" class="col-form-label">Luogo destinazione</label>
                        <input type="text" class="form-control dati" id="luogo_destinazione" name='luogo_destinazione' required>
                    </div>                            
                </div>     

                <div class="row">
                    <div class="col-md-4">
                        <label for="ora_destinazione" class="col-form-label">Ora destinazione</label>
                        <input type="time" class="form-control dati" id="ora_destinazione" name='ora_destinazione' required>
                    </div>
                    <div class="col-md-4">
                        <label for="data_servizio" class="col-form-label">Data servizio</label>
                        <input type='date' class="form-control dati" id="data_servizio" name='data_servizio' required>
                    </div>
                    <div class="col-md-4">
                        <label for="numero_persone" class="col-form-label">Numero persone</label>
                        <input type="number" class="form-control dati" id="numero_persone" name='numero_persone' required>
                    </div>                            
                </div>     

                <div class="row">
                    <div class="col-md-8">
                        <label for="servizi_svolti" class="col-form-label">servizi_svolti</label>
                        <input type="text" class="form-control dati" id="servizi_svolti" name='servizi_svolti'>
                    </div>

                    <div class="col-md-4">
                        <label for="nome_salma" class="col-form-label">Nome salma</label>
                        <input type='text' class="form-control dati" id="nome_salma" name='nome_salma'>
                    </div>
                </div>    

                <div class="row">

                    <div class="col-md-12">
                        <label for="note" class="col-form-label">Note</label>
                        <textarea class="form-control dati" id="note" name="note" row=4></textarea>
                    </div>                            
                </div>                                                    
            </div>
            <hr>
            <div id='div_save'></div>
            
        </form>    
    `
    $("#body_content").html(html)
    info_box(m_e,box)
    $("#modalinfo").modal('show')
}


function accordion(m_e,box) {
    html=""
    html+=`    
    <td style='padding:10px'>
    
        <div class="d-grid gap-2 mb-2">
            <button id="btnbox`+m_e+box+`" type="button" class="btn btn-`+outmp+`info"  data-target="#modalinfo" data-whatever="@mdo" onclick="detail_appalto('`+m_e+`',`+box+`)">Info</button>
        </div>

        <div class="accordion accordion-flush" id="div_gen_box`+m_e+box+`">
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    Ditta
                </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse"">
                    <div class="accordion-body"></div>
                </div>
            </div>

            <div>
            
                <small>
                    <center>Mezzi associati</center>
                </small>




                 
                <div class="viewmezzi mb-2">
                 
                    
                </div>
            </div>
        
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Persone
                </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse"">
                    <div class="accordion-body">
                        <div id='div_pers`+m_e+box+`'></div>
                    </div>
                </div>
            </div>
        </div>    
 
    </td>      
    `
    return html
} 

function removelav(m_e,box,el) {
    refbox="box"+m_e+box+el
    idlav=$("#"+refbox).data( "idlav")
    $("#"+refbox).removeClass('active')
    $("#"+refbox).first().html('Assegnabile')
    $("#"+refbox).removeData( "idlav", '' );
    setresp(m_e,box,el,0,1) //contestualmente elimina mezzo eventuale assegnato
    view_mezzi()
    $("#modalinfo").modal('hide')
}

function view_mezzi() {

    if( typeof setresp.resp == 'undefined' ) return false
    resp=setresp.resp


    numbox_current=0
    $(".box"+m_e).each(function(){
        numbox_current++
    })    
    html=""
    for (box=0;box<numbox_current;box++) {
        for (sc=1;sc<=2;sc++) {
            m_e="M"
            if (sc==2) m_e="P";
            for (elx=0;elx<elemBox;elx++) {
                ind=box+"_"+elx
                if (resp[m_e]) {
                    if (resp[m_e][ind]) {
                        html+=`<span class="badge rounded-pill bg-primary mr-2 mt-2">`+resp[m_e][ind].targa+`</span>`
                    }
                }
            }
        } 
    }
    console.log(html)
    $(".viewmezzi").html(html)
}
function setresp(m_e,box,el,targa,from) {
    refbox="box"+m_e+box+el
    if( typeof setresp.resp == 'undefined' ) setresp.resp=[]
    resp=setresp.resp
 
    idlav=$("#"+refbox).data( "idlav")
    
    numbox_current=0
    $(".box"+m_e).each(function(){
        numbox_current++
    })    
    
    //eliminazione mezzo assegnato
    if (targa=="0") {
        if (resp[m_e]) {
            ind=box+"_"+el
            if (resp[m_e][ind]) {
                delete resp[m_e][ind];
                view_mezzi()
            }
        }
        $("#resp"+m_e+box+el).html('')
        if (from==1) $('#modalinfo').modal('hide')
        return false
    }
    
    if (resp[m_e]) {
        //controllo per verificare se su stesso box esiste già una targa assegnata
        for (elx=0;elx<elemBox;elx++) {
            ind=box+"_"+elx
            if (resp[m_e][ind] && resp[m_e][ind].targa==targa && el!=elx) return "KO";
        }
        //controllo per verificare se su altri box esiste già una targa assegnata
        for (bo=0;bo<numbox_current;bo++) {
            if (bo!=box) {
                for (elx=0;elx<elemBox;elx++) {
                    ind=bo+"_"+elx
                    if (resp[m_e][ind] && resp[m_e][ind].targa==targa) return "KO";
                }        
            }
        }
    }
   

    $("#resp"+m_e+box+el).html("<b>"+targa+"</b>")
    ind=box+"_"+el
    if (!resp[m_e]) resp[m_e]={}
    resp[m_e][ind]={}
    resp[m_e][ind].targa=targa
    resp[m_e][ind].idlav=idlav
    view_mezzi()

    if (from==1) $('#modalinfo').modal('hide')
}

function action_lav(m_e,box,el) {
    infomezzi=$("#infomezzi").val()
    html=""
    html+=`
        <button type="button" class="btn btn-primary" onclick="removelav('`+m_e+`',`+box+`,`+el+`)">
        Rimuovi lavoratore dall'appalto</button>
        <hr>
        <center><h4>Imposta lavoratore come responsabile mezzo</h4></center>
    
    `
    arr_mezzi=infomezzi.split(";")
    html+="<div class='d-grid gap-2'>";
        html+=`
            <button type="button" class="btn btn-outline-warning" 
            onclick="
                esito=setresp('`+m_e+`',`+box+`,`+el+`,'0',1);
                if (esito=='KO') alert('Attenzione! Assegnazione non possibile.')
            ">
            Elimina mezzo assegnato
            </button>
        `;

        for (sca=0;sca<arr_mezzi.length;sca++) {
            targa=arr_mezzi[sca].split("-")[0]
            marca=arr_mezzi[sca].split("-")[1]
            modello=arr_mezzi[sca].split("-")[2]
            
            html+=`
                <button type="button" class="btn btn-outline-success" 
                onclick="
                    esito=setresp('`+m_e+`',`+box+`,`+el+`,'`+targa+`',1);
                    if (esito=='KO') alert('Attenzione! Assegnazione non possibile.')
                ">`
                +targa+` - `+marca+` - `+modello+`
                </button>
            `;
        }
    html+="</div>"    
        

    refbox="box"+m_e+box+el
    idlav=$("#"+refbox).data( "idlav")
    if (typeof idlav == 'undefined' || idlav.length==0) return false
    
    $("#body_content").html(html)

    $("#modalinfo").modal('show')
}

function newapp(m_e,from) {
    strm=$("#strm").val()
    strp=$("#strp").val()

    arrm=strm.split(";");arrp=strp.split(";")
    box=0
    $(".box"+m_e).each(function(){
        box++
    })
    
    outmp="outline-";
    if (m_e=="M") {
        if (strm.includes(box)) outmp="";
    }
    if (m_e=="P") {
        if (strp.includes(box)) outmp="";
    }
    html=""
   
    html=accordion(m_e,box)
    $('#tbApp'+m_e+' tr').append(html)

   
    html="";
    html+=`
        
            <div id='div_box`+m_e+box+`' class="card box`+m_e+`" style="width: 13rem;">
                <div class="card-body">
                    <div class="list-group"  ondrop="dropHandler(event)"   ondragover="dragoverHandler(event)">`
                        for (el=0;el<elemBox;el++) {
                            html+=`    
                            <a href="#" class="list-group-item  clearfix itemlist list-group-item-action box box`+m_e+box+`" id='box`+m_e+box+el+`' data-m_e='`+m_e+`' data-box=`+box+` data-el=`+el+` aria-current="true" onclick="action_lav('`+m_e+`',`+box+`,`+el+`)" >
                                Assegnabile
                            </a>
    
    
                            `
                            
                        }
                        html+=`
                    </div>
                </div>                                    
            </div>
        
    `
    $("#div_pers"+m_e+box).html(html)
    
    if (from=="man") alert("Appalto aggiunto in coda!")

}

function setZoom(value) {
	$('#div_tb').css('transform','scale('+value+')');
	$('#div_tb').css('transformOrigin','left top');
};
