const numBox=6
const elemBox=6
const maxI=2
var saveall=false
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
    //$('[data-toggle="tooltip"]').tooltip(); 
    
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    
} );

///////// DRAG & DROP RESP Mezzi
function dragstartHandlerResp(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("target",ev.target.id)
}
//////////////////



///////// DRAG & DROP Mezzi
function dragstartHandlerMezzi(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}

function dragoverHandlerMezzi(ev) {
  car=ev.target.id
  ev.preventDefault();
}

function dropHandlerMezzi(ev) {
    ev.preventDefault();
    const from = ev.dataTransfer.getData("text");
    if (from.substr(0,8)!="btnmezzo")  {
        alert("Drag & Drop non ammesso!")
        return false
    }
    mezzo=$("#"+from).data('mezzo')
    targa=$("#"+from).data('targa')
    dest=ev.target.id
    m_e=$("#"+dest).data('m_e')
    box=$("#"+dest).data('box')
    testo="Mattutini"
    if (m_e=="P") testo="Pomeridiani"
    check_ins=true
    $(".car"+m_e).each(function(){
        check_targa=$(this).data( "targa")
        if (targa==check_targa) {
            alert("Mezzo già assegnato negli appalti "+testo)
            check_ins=false
        }    
    })    

    if (check_ins==false) return false

    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')

    $("#"+dest).text(targa)
    $('#'+dest).attr('data-bs-original-title', mezzo);
    $("#"+dest).data( "targa", targa );
    console.log("dest",dest,"data-targa",$("#"+dest).data( "targa"))
    $("#"+dest).removeClass('bg-secondary').addClass('bg-warning')
}
//////////////////


///////// DRAG & DROP Lavoratori
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
  dest=ev.target.id
    
  //in caso di assegnazione responsabile mezzo, from contiene l'id del bottone mezzo (car1, car2)
  if (from.substr(0,3)=="car") {
    box_from=$("#"+from).data('box')
    m_e=$("#"+dest).data('m_e')
    box=$("#"+dest).data('box')
    el=$("#"+dest).data('el')

    if (box_from!=box) {
        alert("Assegnazione non possibile!")
        return false
    }

    if (m_e.length!=0) {
        //assegnazione responsabile mezzo
        targa=$("#"+from).data("targa")
        
        esito=setresp(m_e,box,el,targa,1);
        if (esito=='KO') alert('Attenzione! Assegnazione non possibile.')
        console.log("targa ass",targa)
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    }
    return false
  }
 
  if (from.substr(0,6)!="btnlav") {
    alert("Drag & Drop non ammesso")
    return false
  }

  

  idlav=$("#"+from).data('idlav')
  impegnalav(idlav)
  console.log("dati dell'impegno: _m_e",_m_e,"_box",_box,"_el",_el)
  setsquadra(_m_e,_box,_el)
  $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
}
//////////////////


///////// DRAG & DROP Ditte


function dragstartHandlerDitta(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
}
function dragoverHandlerDitta(ev) {
  dest=ev.target.id
  ev.preventDefault();
}
function dropHandlerDitta(ev) {
  ev.preventDefault();
  const from = ev.dataTransfer.getData("text");
  if (from.substr(0,6)!="btndit") {
    alert("Drag & Drop non ammesso!")
    return false
  }
  dest=ev.target.id

  ditta=$("#"+from).data("nome");d_origin=ditta
  iddit=$("#"+from).data("iddit")
  if (ditta.length>20) ditta=ditta.substr(0,16)+"..."
  html="<span title='"+d_origin+"'><i class='fa-solid fa-location-dot'></i> "+ditta+"</span>"
  $("#"+dest).html(html)
  $("#"+dest).removeClass('bg-secondary').addClass('bg-success')
  $("#"+dest).data("iddit",iddit)
  console.log("dest",dest,"from",from)
  $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
  
} 
///////// DRAG & DROP Lavoratori

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
            html=nomelav+" <span id='resp"+m_e+box+rowbox+"'></span><input type='hidden' id='resp_raw"+m_e+box+rowbox+"'>"
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
    from=save_appalto.from


    //from==0 salvataggio singolo box da bottone interno ad info
    //from==1 invocato dal bottone salva tutto (non esegue serialize del form per le info sull'appalto)
    if (from==0) info=$('#form_info').serialize();
    else info=0;

    let CSRF_TOKEN = $("#token_csrf").val();
    html="<i class='fas fa-spinner fa-spin'></i>"
    $("#div_wait").html(html)
    base_path = $("#url").val();

    all_id_box=""
    if (from==0) {
        $(".box"+m_e+box).each(function(){
            lav=$(this).data( "idlav")
            if (all_id_box.length>0) all_id_box+=";" 
            if( typeof lav === 'undefined' ) all_id_box+="0"
            else all_id_box+=lav
        
        })  
    }

    all_id_boxes=""
    if (from==1) {
        $(".box").each(function(){
            lav=$(this).data( "idlav")
            m_e1=$(this).data( "m_e")
            box1=$(this).data( "box")

            if (all_id_boxes.length>0) all_id_boxes+=";" 
            if( typeof lav === 'undefined' ) all_id_boxes+="0|"+m_e1+"|"+box1
            else all_id_boxes+=lav+"|"+m_e1+"|"+box1
        })      
    }

   
      timer = setTimeout(function() {	
      fetch(base_path+"/save_infoapp", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box+"&all_id_box="+all_id_box+"&all_id_boxes="+all_id_boxes+"&"+info+"&from="+from,
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(resp=>{

          if (resp.header=="OK") {
            if (from==0) {
                $("#btnbox"+m_e+box)
                .removeClass('btn-outline-info')
                .removeClass('btn-info')
                .addClass('btn-info')
            }    

            $("#div_wait").empty()
            $("#modalinfo").modal('hide')
            if (from==0) {
                numero_persone=$("#numero_persone").val()
                orario_incontro=$("#orario_incontro").val()
                html=`
                <i class="fa-solid fa-person"></i> 
                `+numero_persone+`
                 <i class="ml-3 fa-solid fa-clock"></i> `
                +orario_incontro

                $("#infoapp"+m_e+box).removeClass('bg-secondary').removeClass('bg-success').addClass('bg-success')
                $("#infoapp"+m_e+box).html(html)
            }    
            if (from==1) {
                //save_all
                $("#btn_save_all").prop('disabled',false)
                $("#btn_save_all").html("<i class='fa-solid fa-floppy-disk'></i> Salva Tutto")
                $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-outline-success')

            }

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

function save_all() {
    id_giorno_appalto=$("#id_giorno_appalto").val()
    save_info(id_giorno_appalto,"",1000,1)
    $("#btn_save_all").prop('disabled',true)
    $("#btn_save_all").text("Salvataggio in corso...")
    save_appalto()
}

function save_info(id_giorno_appalto,m_e,box,from){
    save_appalto.id_giorno_appalto=id_giorno_appalto
    save_appalto.m_e=m_e
    save_appalto.box=box
    save_appalto.from=from
    //save_appalto() viene invocato dal submit controllato da validation_form()
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
                <button type="submit" id='btn_save' class="btn btn-primary" onclick="save_info(`+id_giorno_appalto+`,'`+m_e+`',`+box+`,0)">Salva dati appalto</button>
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

function removemezzo(dest) {
    if (dest.length>0) {
        if (!confirm("Sicuri di eliminare il mezzo (e l'eventuale assegnazione del responsabile mezzo)?"))
            return false
            
       $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')    
        resp=new Array()
        if( typeof setresp.resp != 'undefined' ) resp=setresp.resp
        targa=$("#"+dest).data("targa")
        m=dest.substr(3,1)
        m_e=$("#"+dest).data('m_e')
        box=$("#"+dest).data('box')
        var exist  = Object.keys(resp).includes(m_e)
        if (exist) {
        
            for (elx=0;elx<elemBox;elx++) {
                ind=box+"_"+elx
                if (resp[m_e][ind] && resp[m_e][ind].targa==targa) {
                    esito=setresp(m_e,box,elx,'0',1);
                    break;
                }
            }
        }
        $("#"+dest).text("Mezzo"+m)
        $('#'+dest).attr('data-bs-original-title', "");
        $("#"+dest).data( "targa", "");
        $("#"+dest).removeClass('bg-warning').addClass('bg-secondary')

        
            
    }
}

function removeditta(id) {
    if (!confirm("Sicuri di disassociare la ditta?")) return false;
    $("#"+id).removeData( "iddit", '' );
    html="<i class='fa-solid fa-location-dot'></i>"
    $("#"+id).html(html)
    $("#"+id).removeClass('bg-success').addClass('bg-secondary')
    
}

function resetbox(m_e,box,from) {
    html="";
    $("#alert_confirm").hide()
    if (!$('#conferma').is(":checked")) {
        $("#alert_confirm").show(130)
        return false
    }
    if (from==0) {
        html=inibox(m_e,box)
        $("#boxinfo"+m_e+box).html(html)
        html=inimezzi(m_e,box)
        $("#mezzi_info"+m_e+box).html(html)
        html=initditte(m_e,box)
        $("#ditte_info"+m_e+box).html(html)
    }    
    else {
        $(".box").each(function(){
            m_e=$(this).data( "m_e")
            box=$(this).data( "box")
            html=inibox(m_e,box)
            $("#boxinfo"+m_e+box).html(html)
            html=inimezzi(m_e,box)
            $("#mezzi_info"+m_e+box).html(html)
            html=initditte(m_e,box)
            $("#ditte_info"+m_e+box).html(html)

        })
    }    
    $("#modalinfo").modal('hide')
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    
}

function enable_reset() {
    $('#alert_confirm').show()
    $("#btn_reset_box").prop("disabled",true)
    $("#btn_dele_box").prop("disabled",true)
    $("#btn_reset_all_box").prop("disabled",true)

    if ($('#conferma').is(":checked")) {
        $("#btn_reset_box").prop("disabled",false)
        $("#btn_dele_box").prop("disabled",false)
        $("#btn_reset_all_box").prop("disabled",false)
        $('#alert_confirm').hide(130)
    }
    
}
function deletebox(m_e,box) {
    $("#alert_confirm").hide()
    if (!$('#conferma').is(":checked")) {
        $("#alert_confirm").show(130)
        return false
    }
    $("#tdbox"+m_e+box).hide()
    $("#modalinfo").modal('hide')
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')

}

function optionbox(m_e,box) {
    html=""
    html=`
        <div class="d-grid gap-2">
            <button class="btn btn-primary" id='btn_reset_box' type="button" disabled onclick="resetbox('`+m_e+`',`+box+`,0)">Reset Box</button>
          
            <button class="btn btn-warning" id='btn_dele_box' type="button" onclick="deletebox('`+m_e+`',`+box+`)"  style='display:none'>Elimina box</button>
            <hr>
            <button class="btn btn-warning" id='btn_reset_all_box' type="button" disabled onclick="resetbox('`+m_e+`',`+box+`,1)">Reset ALL Box</button>              
            <div class="form-check form-switch ml-4">
                <input class="form-check-input" type="checkbox" id="conferma" onclick="enable_reset()">
                <label class="form-check-label" for="conferma">Conferma operazione</label>
            </div>
            <div class="alert alert-light" role="alert" id='alert_confirm'>
                <b>Attenzione!</b><br>
                Prima di avviare l'operazione cliccare sul check di conferma<hr>
                <small>
                <b>N.B.: Da tener presente che l'operazione viene consolidata solo cliccando sul bottone 'Salva Tutto'</b>
                </small>
            </div>

        </div>
    `
    $("#body_content").html(html)
    $("#modalinfo").modal('show')    
}

function inimezzi(m_e,box) {
    html=""
    html+=`
        <center><i class="fa-solid fa-car mt-2"></i></center>
        <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-1 car`+m_e+`" style="font-size:.8em"  id='car1`+m_e+box+`'   data-m_e='`+m_e+`' data-box='`+box+`' data-bs-toggle="tooltip" ondrop="dropHandlerMezzi(event)"  draggable="true" ondragstart="dragstartHandlerResp(event)" ondragover="dragoverHandlerMezzi(event)" data-placement="top" onclick="removemezzo(this.id)">
            Mezzo1
        </span>
    
        <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-1 car`+m_e+`" style="font-size:.8em"  id='car2`+m_e+box+`' data-m_e='`+m_e+`' data-box='`+box+`' data-bs-toggle="tooltip" ondrop="dropHandlerMezzi(event)"  draggable="true" ondragstart="dragstartHandlerResp(event)" ondragover="dragoverHandlerMezzi(event)" data-placement="top" onclick="removemezzo(this.id)">
            Mezzo2
        </span>     
    `
    return html
}

function initditte(m_e,box) {
    html="";
    html+=`
        <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-1" 
            id='ditta`+m_e+box+`' data-m_e='`+m_e+`' data-box='`+box+`'  ondragover="dragoverHandlerDitta(event)" ondrop="dropHandlerDitta(event)"  data-placement="top" onclick='removeditta(this.id)' style='width:100%'>
            <i class="fa-solid fa-location-dot"></i>
        </span>      
    `
    return html
}

function accordion(m_e,box) {
    html=""
    html+=`    
    <td style='padding:10px' id='tdbox`+m_e+box+`'>
    
        <div class="d-grid gap-2 mb-2">
            <button id="btnbox`+m_e+box+`" type="button" class="btn btn-`+outmp+`info"  data-target="#modalinfo" data-whatever="@mdo" onclick="detail_appalto('`+m_e+`',`+box+`)" >Info</button>
            <div class="panel-footer text-center">
            
                <span id='infoapp`+m_e+box+`' class="badge rounded-pill bg-secondary pull-left">
                    <i class="fa-solid fa-person"></i> 
                    <i class="ml-3 fa-solid fa-clock"></i>
                </span>    
                <span class="pull-right">
                    <a class="link-secondary" href='#' onclick="optionbox('`+m_e+`',`+box+`)"><i class="fa-solid fa-gears"></i> <small>Option</small>
                    </a>
                </span>                
            </div>
            <div id='ditte_info`+m_e+box+`' class='mb-2'>`
            html+=initditte(m_e,box)
            html+=`</div>
        </div>

        <div class="accordion accordion-flush" id="div_gen_box`+m_e+box+`">
            <div id='mezzi_info`+m_e+box+`' class='mb-2'>`
                html+=inimezzi(m_e,box)     
            html+=
            `</div>
        
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    Persone
                </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse">
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
    
    $("#modalinfo").modal('hide')
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
            }
        }
        $("#resp"+m_e+box+el).html('')

        $("#resp_raw"+m_e+box+el).val('')
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
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
    $("#resp_raw"+m_e+box+el).val(targa)
    ind=box+"_"+el
    if (!resp[m_e]) resp[m_e]={}
    resp[m_e][ind]={}
    resp[m_e][ind].targa=targa
    resp[m_e][ind].idlav=idlav
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
   
}

function action_lav(m_e,box,el) {
    infomezzi=$("#infomezzi").val()
    html=""
    html+=`
        <button type="button" class="btn btn-primary" onclick="removelav('`+m_e+`',`+box+`,`+el+`)">
        Rimuovi lavoratore dall'appalto</button>
        <button type="button" class="ml-2 btn btn-warning" 
        onclick="
            esito=setresp('`+m_e+`',`+box+`,`+el+`,'0',1);
            if (esito=='KO') alert('Attenzione! Assegnazione non possibile.')
        ">
        Elimina mezzo assegnato
        </button>        
    `
    arr_mezzi=infomezzi.split(";")
        /*
        html+="<div class='d-grid gap-2'>";

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
        */
    html+="</div>"    
        

    refbox="box"+m_e+box+el
    idlav=$("#"+refbox).data( "idlav")
    if (typeof idlav == 'undefined' || idlav.length==0) return false
    
    $("#body_content").html(html)

    $("#modalinfo").modal('show')
}


function inibox(m_e,box) {
    html="";
    for (el=0;el<elemBox;el++) {
        html+=`    
        <a href="#" class="list-group-item  clearfix itemlist list-group-item-action box box`+m_e+box+`" id='box`+m_e+box+el+`' data-m_e='`+m_e+`' data-box=`+box+` data-el=`+el+` aria-current="true" onclick="action_lav('`+m_e+`',`+box+`,`+el+`)" >
            Assegnabile
        </a>
        `
    }
    return html
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
                    <div id='boxinfo`+m_e+box+`' class="list-group"  ondrop="dropHandler(event)"   ondragover="dragoverHandler(event)">`
                        html+=inibox(m_e,box);    
                        html+=`
                    </div>
                </div>                                    
            </div>
        
    `
    $("#div_pers"+m_e+box).html(html)
    
    if (from=="man") {
        alert("Appalto aggiunto in coda!")
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    }
    

}

function setZoom(value) {
	$('#div_tb').css('transform','scale('+value+')');
	$('#div_tb').css('transformOrigin','left top');
};
