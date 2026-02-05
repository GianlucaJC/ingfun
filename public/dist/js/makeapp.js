const numBox=7
const elemBox=6
const elemRep=15
const elemAss=15
const maxI=20
const zoomI=0.54
var saveall=false
var _m_e="?";var _box="?";var _el="?"
var lavall=new Array();
var servall=new Array();
var dittall=new Array();
var alias_mezzi=new Array();
var appaltoLogs = [];

let storedZoomAll = zoomI;
let storedZoomM = zoomI;
let storedZoomP = zoomI;

function resetZoomAll() {
    setZoomAll(zoomI, 0);
    const slider = $('#zoom_slider_all');
    if (slider.length > 0) {
        slider.val(zoomI);
    }
}

function resetZoomM() {
    setZoomM(zoomI, 0);
    const slider = $('#zoom_slider_m');
    if (slider.length > 0) {
        slider.val(zoomI);
    }
}

function resetZoomP() {
    setZoomP(zoomI, 0);
    const slider = $('#zoom_slider_p');
    if (slider.length > 0) {
        slider.val(zoomI);
    }
}


 $(function () {
    class_o="hold-transition sidebar-mini"
    class_n="hold-transition skin-blue sidebar-collapse"
    $("body").removeClass(class_o).addClass(class_n)
    $("#credit_top").hide()

    elenco_lav=$("#elenco_lav").val().split("|");
    for (sc=0;sc<elenco_lav.length;sc++) {
        id_l=elenco_lav[sc].split(";")[0]
        lavu=elenco_lav[sc].split(";")[1]
        lavall[id_l]=lavu;
    }

    all_servizi=$("#all_servizi").val().split("|");
    for (sc=0;sc<all_servizi.length;sc++) {
        id_serv=all_servizi[sc].split(";")[0]
        serv=all_servizi[sc].split(";")[1]
        servall[id_serv]=serv;
    }

    alld=$("#alld").val().split("|");
    for (sc=0;sc<alld.length;sc++) {
        id_d=alld[sc].split(";")[0]
        ditt=alld[sc].split(";")[1]    
        dittall[id_d]=ditt;
    }

    all_alias_m=$("#all_alias_m").val().split("|");
    for (sc=0;sc<all_alias_m.length;sc++) {
        alias=all_alias_m[sc].split(";")[0]
        ta=all_alias_m[sc].split(";")[1]    
        alias_mezzi[ta]=alias;
    }


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
 
    setZoomAll(zoomI,0); // Inizializza tutti gli slider e i bottoni di reset

    $("#div_side").removeClass('control-sidebar-dark');
    $('.control-sidebar').ControlSidebar('show');
    $("#div_urg").show(1000);
    //$('[data-toggle="tooltip"]').tooltip(); 
    
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    // Aggiunge un bottone per resettare lo zoom per GENERALE
    const sliderAll = $('#zoom_slider_all');
    if (sliderAll.length > 0) {
        const resetButtonAll = $('<button id="resetZoomBtnAll" class="btn btn-secondary btn-sm" style="margin-left: 10px; display: none;">Ripristina Zoom</button>');
        resetButtonAll.on('click', resetZoomAll);
        sliderAll.after(resetButtonAll);
    }

    // Aggiunge un bottone per resettare lo zoom per MATTINA
    const sliderM = $('#zoom_slider_m');
    if (sliderM.length > 0) {
        const resetButtonM = $('<button id="resetZoomBtnM" class="btn btn-secondary btn-sm" style="margin-left: 10px; display: none;">Ripristina Zoom</button>');
        resetButtonM.on('click', resetZoomM);
        sliderM.after(resetButtonM);
    }

    // Aggiunge un bottone per resettare lo zoom per POMERIGGIO
    const sliderP = $('#zoom_slider_p');
    if (sliderP.length > 0) {
        const resetButtonP = $('<button id="resetZoomBtnP" class="btn btn-secondary btn-sm" style="margin-left: 10px; display: none;">Ripristina Zoom</button>');
        resetButtonP.on('click', resetZoomP);
        sliderP.after(resetButtonP);
    }

} );

///////// DRAG & DROP RESP Mezzi
///per l'assegnazione vedi la function setresp()
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
        Swal.fire({
            icon: 'error',
            title: 'Operazione non permessa',
            text: 'Drag & Drop non ammesso!'
        });
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
            Swal.fire({
                icon: 'warning',
                title: 'Attenzione',
                text: "Mezzo già assegnato negli appalti " + testo
            });
            check_ins=false
        }    
    })    

    if (check_ins==false) return false

    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')

    alias = targa;
    if (alias_mezzi[targa]) alias = alias_mezzi[targa];

    $("#"+dest).text(alias)
    $('#'+dest).attr('data-bs-original-title', mezzo);
    $("#"+dest).data( "targa", targa );
    $("#"+dest).data( "mezzo", mezzo );
    console.log("dest",dest,"data-targa",$("#"+dest).data( "targa"))
    $("#"+dest).removeClass('bg-secondary').addClass('bg-warning')
}
//////////////////



///DRAG & DROP da box a box tra lav
function dragstartHandlerLav(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("targetLavBox",ev.target.id)
}

///////// DRAG & DROP Lavoratori (su side sx)
function dragstartHandler(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("targetLav",ev.target.id)
}
function dragoverHandlerPers(ev) {
  pers=ev.target.id
  ev.preventDefault();
}
///drop lavoratori su side sx
function dropHandlerPers(ev) {
    ev.preventDefault();
    const from = ev.dataTransfer.getData("text");
    const lav = $("#" + from).data('idlav');

    if (from.substr(0, 3) === "box" || from.substr(0, 3) === "rep" || from.substr(0, 3) === "ass") {
        Swal.fire({
            title: 'Attenzione!',
            text: "Il lavoratore selezionato sarà rimosso da tutti gli appalti. Procedere?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sì, procedi!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                removelavall(lav);
                $("#spanlav" + lav).show(150);
            }
        });
    } else {
        $("#spanlav" + lav).show(150);
    }
}

///DRAG & DROP da allbox a allbox tra lav
function dragstartHandlerBox(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("targetLavBox",ev.target.id)
}
////drag on Allbox lavoratori
function dragoverHandlerBox(ev) {
    dest=ev.target.id
    from = ev.dataTransfer.getData("text");
    ev.preventDefault();
}

//drop on allbox lavoratori (drag tramite area interna al box)
function dropHandlerBox(ev) {
    ctrl=false
    if (ev.ctrlKey) ctrl=true

    ev.preventDefault();
    const from = ev.dataTransfer.getData("text");

    dest=ev.target.id
    console.log("from",from,"dest",dest)
        
    //in altri casi disabilito il drag & drop
    if (dest.substr(0,6)!="boxall") {
     return false
    }

    m_eo=from.substr(6,1)
    boxo=from.substr(7)

    m_ed=dest.substr(6,1)
    boxd=dest.substr(7)
    
    //resetbox della box di destinazione
    resetbox(m_ed,boxd,2);

    

    console.log("m_eo+boxo","-",m_eo,boxo,"-")
    //copio/sposto ogni elemento del box di origine sul box di destinazione
    el=0
    $(".box"+m_eo+boxo).each(function(){
        id_ref=$(this).data( "idlav")
        if (ctrl==false) removelav(m_eo,boxo,el); 
        console.log("id_ref",id_ref,"m_eo",m_eo,"boxo",boxo,"m_ed",m_ed,"boxd",boxd,"el",el) 
        impegnalav(id_ref)
        setsquadra(m_ed,boxd,el,boxo) //...e lo copio in quello di destinazione
        el++
    })

    //resetbox della box di origine (se non si preme ctrl) 
    //preferito farlo singolarmente durante la $(".box").each()
    /*
    if (ctrl==false)
        resetbox(m_eo,boxo,2);
    */

  $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
}
//////////////////

////drag on box lavoratori
function dragoverHandler(ev) {
    dest=ev.target.id
    from = ev.dataTransfer.getData("text");


    //drag from lav (elenco di sx) to box
    _m_e=$("#"+dest).data('m_e')
    _box=$("#"+dest).data('box')
    _el=$("#"+dest).data('el')
    ev.preventDefault();
}


//drop on box lavoratori
function dropHandler(ev) {
  ctrl=false
  if (ev.ctrlKey) ctrl=true
  ev.preventDefault();
  const from = ev.dataTransfer.getData("text");
  
  dest=ev.target.id
  console.log("from",from,"dest",dest)
  
    
  //in caso di assegnazione responsabile mezzo, from contiene l'id del bottone mezzo (car1, car2)
  if (from.substr(0,3)=="car") {
    box_from=$("#"+from).data('box')
    m_e=$("#"+dest).data('m_e')
    box=$("#"+dest).data('box')
    el=$("#"+dest).data('el')

    if (box_from!=box) {
        Swal.fire({
            icon: 'error',
            title: 'Assegnazione non possibile!',
            text: 'Il responsabile del mezzo può essere assegnato solo a persone dello stesso appalto.'
        });
        return false
    }

    if (m_e.length!=0) {
        //assegnazione responsabile mezzo
        targa=$("#"+from).data("targa")
        
        esito=setresp(m_e,box,el,targa,1);
        if (esito=='KO') Swal.fire({
            icon: 'warning',
            title: 'Attenzione!',
            text: 'Assegnazione non possibile. Il mezzo potrebbe essere già stato assegnato.'
        });
        console.log("targa ass",targa)
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    }

    return false
  }
 
  //spostamento/copia lavoratore da box a box
    boxo=$("#"+from).data('box')
  
  if (from.substr(0,3)=="box") {
    idlav=$("#"+from).data('idlav')
    esito=impegnalav(idlav)
    m_e_f=$("#"+from).data('m_e')
    box_f=$("#"+from).data('box')
    el_f=$("#"+from).data('el')
    console.log("dati dell'impegno: _m_e",_m_e,"_box",_box,"_el",_el)
    esito=setsquadra(_m_e,_box,_el,boxo) // sposta in quello di destinazione
    if (ctrl==false && esito==true)
        removelav(m_e_f,box_f,el_f) //e rimuove il lavoratore dal box di origine

    if (esito==true) 
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')    
    return false
  }




  idlav=$("#"+from).data('idlav')
  
  if (_m_e=="?") return false;
  
  impegnalav(idlav)
  console.log("dati dell'impegno: idlav",idlav,"_m_e",_m_e,"_box",_box,"_el",_el)
  esito=setsquadra(_m_e,_box,_el,boxo)
  if (esito==false) return false;
  if (from.substr(0,6)=="btnlav" && esito==true) {
    //$("#"+from).hide(120)
    $("#spanlav"+idlav).hide();
  }
  
  //in caso il drag proviene dai reperibili lo rimuovo (spostamento) a meno che non sia premuto CTRL (copia)
  if (from.substr(0,3)=="rep") {
    if (ctrl==false) {
        $("#"+from).data("idlav","")
        $("#"+from).text("__________")
        remove_impegno(from)
        $("#"+from).removeClass('impegnato')
    }
  }

  //in caso il drag proviene dagli assenti lo rimuovo dal box-elemento assenti
  if (from.substr(0,3)=="ass") {
    m_e_f=$("#"+from).data('m_e')
    el_ref=$("#"+from).data('el')
   
    refass="ass"+m_e_f+el_ref
    console.log("refass",refass,"from",from)
    remove_impegno(refass)
    $("#"+refass).removeClass('impegnato')
    $("#"+refass).first().html("__________")
    $("#"+refass).removeData( "idlav", '' );
  }

  $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
}
//////////////////

///DRAG & DROP da ass a ass
function dragstartHandlerAss(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("targetLavBox",ev.target.id)
}

////drag on box assenti
function dragoverHandlerAss(ev) {
    dest=ev.target.id
    from = ev.dataTransfer.getData("text");
    //drag from lav (elenco di sx) to box
    ev.preventDefault();
}
//drop on box assenti
function dropHandlerAss(ev) {
  ctrl=false
  if (ev.ctrlKey) ctrl=true

  ev.preventDefault();
  const from = ev.dataTransfer.getData("text");
  dest=ev.target.id

  //spostamento lavoratore da box lavoratori a box assenti
  if (from.substr(0,6)=="btnlav" || from.substr(0,3)=="ass" || from.substr(0,3)=="rep" || from.substr(0,3)=="box") {
  

    idlav=$("#"+from).data('idlav')
    console.log("idlav prima:",idlav)
    if (from.substr(0, 3) === "box") {
        Swal.fire({
            title: 'Attenzione!',
            text: "Il lavoratore selezionato sarà rimosso da tutti gli appalti. Procedere?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sì, procedi!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                removelavall(idlav);
            }
        });
    }
    m_e=$("#"+dest).data('m_e')
    el=$("#"+from).data('el')
    console.log("assegnazioni per assenti - from",from,"dest",dest,"m_e",m_e)

    if (dest.substr(0,3)!='ass') return false
    $("#spanlav"+idlav).hide();
    
    
    present=false
    check_self=false
    if (from.substr(0,5)==dest.substr(0,5)) check_self=true
    if (check_self==false) {
        $(".ass"+m_e).each(function(){
            id_ref=$(this).data( "idlav")
            if (id_ref && idlav) {
                if (id_ref==idlav) present=true
            }
        })
    }
    if (present==true) {
        return false
    }
    console.log("idlav",idlav)
    if (ctrl==false) {
      if (from.substr(0,3)=="ass") {
          $("#"+from).data("idlav","")
          $("#"+from).text("__________")
          $("#"+from).removeClass('impegnato')
          remove_impegno(from)
      }
      if (from.substr(0,3)=="rep") {
          $("#"+from).data("idlav","")
          $("#"+from).text("__________")
          remove_impegno(from)
          $("#"+from).removeClass('impegnato')
      }
    }
    
    reflav="btnlav"+idlav
    nomelav=$("#"+reflav).text().trim()
    
    //refbox="box"+m_e+el
    refbox=dest
    remove=false

    color=$("#"+reflav).data('color')

    if (!$("#"+refbox).hasClass('impegnato')) {
        elref=refbox.substr(5)
        $("#"+refbox).addClass('text-'+color)
        $("#"+refbox).addClass('impegnato')
        html=nomelav
        $("#"+refbox).first().html(html)
        $("#"+refbox).data( "idlav", idlav );
        $("#"+refbox).data( "el", elref );

    }
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
  }
}


///

///DRAG & DROP da rep a rep
function dragstartHandlerRep(ev) {
  ev.dataTransfer.setData("text", ev.target.id);
  console.log("targetLavBox",ev.target.id)
}

////drag on box reperibilità
function dragoverHandlerRep(ev) {
    dest=ev.target.id
    from = ev.dataTransfer.getData("text");
    //drag from lav (elenco di sx) to box
    ev.preventDefault();
}


//drop on box reperibilità
function dropHandlerRep(ev) {
  ctrl=false
  if (ev.ctrlKey) ctrl=true

  ev.preventDefault();
  const from = ev.dataTransfer.getData("text");
  dest=ev.target.id

  console.log("from",from,"dest",dest,"m_e",m_e)
  refbox=dest
  if ($("#"+refbox).hasClass('impegnato')) return false

  //spostamento lavoratore da box lavoratori a box reperibilità
  if (from.substr(0,6)=="btnlav" || from.substr(0,3)=="rep" || from.substr(0,3)=="box" || from.substr(0,3)=="ass" ) {
  
    idlav=$("#"+from).data('idlav')
    

    m_e=$("#"+dest).data('m_e')
    el=$("#"+from).data('el')
    
    if (dest.substr(0,3)!='rep') return false
    $("#spanlav"+idlav).hide();



    present=false
    check_self=false
    if (from.substr(0,5)==dest.substr(0,5)) check_self=true
    if (check_self==false) {
        $(".rep"+m_e).each(function(){
            id_ref=$(this).data( "idlav")
            if (id_ref==idlav) present=true
        })
    }
    if (present==true) {
       // alert("Il lavoratore selezionato è già presente in questo BOX appalto!")
        return false
    }
    console.log("idlav",idlav)
    if (ctrl==false) {
      if (from.substr(0,3)=="rep") {
          $("#"+from).data("idlav","")
          $("#"+from).text("__________")
          remove_impegno(from)
          $("#"+from).removeClass('impegnato')
      }
      if (from.substr(0,3)=="ass") {
          $("#"+from).data("idlav","")
          $("#"+from).text("__________")
          remove_impegno(from)
          $("#"+from).removeClass('impegnato')
      }
    }

    reflav="btnlav"+idlav
    nomelav=$("#"+reflav).text().trim()
    
    //refbox="box"+m_e+el
    remove=false

    color=$("#"+reflav).data('color')

    remove_impegno(refbox)
    
 
    if (!$("#"+refbox).hasClass('impegnato')) {
        elref=refbox.substr(5)
        $("#"+refbox).addClass('text-'+color)

        $("#"+refbox).addClass('impegnato')
        html=nomelav
        $("#"+refbox).first().html(html)
        $("#"+refbox).data( "idlav", idlav );
        $("#"+refbox).data( "el", elref );
       
    }
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
  }
}

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
  if (from.substr(0, 6) !== "btndit") {
    Swal.fire({
        icon: 'error',
        title: 'Operazione non permessa',
        text: 'Drag & Drop non ammesso!'
    });
    return false
  }
  dest=ev.target.id

  ditta=$("#"+from).data("nome");d_origin=ditta
  alias=$("#"+from).data("alias");
  if (alias.length!=0) {ditta=alias;}


  iddit=$("#"+from).data("iddit")
  //if (ditta.length>20) ditta=ditta.substr(0,16)+"..."
  html="<span title='"+d_origin+"'><i class='fa-solid fa-location-dot'></i> "+ditta+"</span>"
  $("#"+dest).html(html)
  $("#"+dest).removeClass('bg-secondary').addClass('bg-success')
  $("#"+dest).data("iddit",iddit)
  console.log("dest",dest,"from",from)
  $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
  
} 
///////// DRAG & DROP Lavoratori


function remove_impegno(refrep) {
    colo="";
    for (c=1;c<=5;c++) {
        if (c==1) colo="info";
        if (c==2) colo="danger";
        if (c==3) colo="primary";
        if (c==4) colo="warning";
        if (c==5) colo="secondary";
        $("#"+refrep).removeClass('text-'+colo)
    } 

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
    html="";
    html+=`
    <div class="container-fluid" style='background-color:white'>
        <div class="row">
            <div class="col-md-6">    
                <div id='div_reperx'>
                    <div id='repMa'>	
                    </div> 
                    <div id='repMb'>	
                    </div>             
                    <div id='repPa'>	
                    </div>  
                    <div id='repPb'>	
                    </div>                                          
                </div>
            </div>    
            <div class="col-md-6">    
                <div id='div_assenti'>
                    <div id='assMa'>	
                    </div>                         
                    <div id='assMb'>	
                    </div>    
                </div>
            </div>
        </div>
    </div>            
    `

    $("#div_side").html(html) //in sidebar.blade
 
    html=""
    for (sc=1;sc<=4;sc++) {
        html=inirep(sc)
        if (sc==1) me="Ma"
        if (sc==2) me="Mb"
        if (sc==3) me="Pa"
        if (sc==4) me="Pb"
        $('#rep'+me).html(html)
    }    
    
    html=""
    for (sc=1;sc<=2;sc++) {
        html=iniass(sc)
        if (sc==1) me="Ma"
        if (sc==2) me="Mb"
        $('#ass'+me).html(html)
    }    
        

    //dopo il rendering dell'accordion    
    //precarico dei dati visibili (es. numero persone, orario incontro, etc.)
    load_inf()
}

function load_inf() {
    id_giorno_appalto=$("#id_giorno_appalto").val()
    let CSRF_TOKEN = $("#token_csrf").val();      
    base_path = $("#url").val();
    timer = setTimeout(function() {	
      fetch(base_path+"/check_allestimento", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&from=1",
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(ris=>{
            resp=ris.info_appalto
            for (sca=0;sca<resp.length;sca++) {
                hide=resp[sca].hide
                box=resp[sca].id_box
                m_e=resp[sca].m_e
                if (hide==1) $("#tdbox"+m_e+box).hide()
                numero_persone=resp[sca].numero_persone
                orario_incontro=resp[sca].orario_incontro
                html=`
                <i class="fa-solid fa-person"></i> 
                `+numero_persone+`
                    <i class="ml-3 fa-solid fa-clock"></i> `
                +orario_incontro
                $("#infoapp"+m_e+box).removeClass('bg-secondary').removeClass('bg-success').addClass('bg-success')
                $("#infoapp"+m_e+box).html(html)
                console.log("m_e",m_e,"box",box)
            }
            resp=ris.info_altro
            
            //recupero tutte le ditte (mi servirà perchè all'ID salvato devo collegare la denominazione)
            alld=$("#alld").val();
            infod=alld.split("|");
            arr_d=new Array();
            for (sc=0;sc<infod.length;sc++) {
                elem=infod[sc];arr_ref=elem.split(";")
                arr_d[arr_ref[0]]=arr_ref[1]+";"+arr_ref[2]
            }
            //
            
            for (sca=0;sca<resp.length;sca++) {
                box=resp[sca].box
                m_e=resp[sca].m_e
                targa1=resp[sca].targa1
                targa2=resp[sca].targa2
                ditta=resp[sca].ditta
                
                if (ditta && typeof ditta !== 'undefined') {
                    iddit=ditta.toString()
                    refditta="";
                    if (arr_d[iddit]) {
                        refditta=arr_d[iddit];d_origin=refditta
                        realditta=refditta.split(";")[0]
                        alias=refditta.split(";")[1]
                        d_origin=realditta
                        if (alias && alias.length!=0) d_origin=alias
                        //if (refditta.length>20) refditta=refditta.substr(0,16)+"..."
                        dest="ditta"+m_e+box

                        html="<span title='"+realditta+"'><i class='fa-solid fa-location-dot'></i> "+d_origin+"</span>"
                        $("#"+dest).html(html)
                        $("#"+dest).data("iddit",iddit)
                        $("#"+dest).removeClass('bg-secondary').removeClass('bg-success').addClass('bg-success')
                       
                    }
                }

                if (targa1 && targa1.length>0) {
                    dest="car1"+m_e+box
                    $("#"+dest).removeClass('bg-secondary').removeClass('bg-warning').addClass('bg-warning')
                    
                    let alias1 = targa1;
                    if (alias_mezzi[targa1]) alias1 = alias_mezzi[targa1];
                    $("#"+dest).text(alias1)
                    $('#'+dest).attr('data-bs-original-title', targa1); // Keep original targa in tooltip
                    //$('#'+dest).attr('data-bs-original-title', mezzo);
                    $("#"+dest).data( "targa", targa1 );                    
                }
                if (targa2 && targa2.length>0) {
                    dest="car2"+m_e+box
                    $("#"+dest).removeClass('bg-secondary').removeClass('bg-warning').addClass('bg-warning')

                    let alias2 = targa2;
                    if (alias_mezzi[targa2]) alias2 = alias_mezzi[targa2];
                    $("#"+dest).text(alias2)
                    $('#'+dest).attr('data-bs-original-title', targa2); // Keep original targa in tooltip
                    //$('#'+dest).attr('data-bs-original-title', mezzo);
                    $("#"+dest).data( "targa", targa2 );    
                }

            }

            dato=ris.info_reper
            
            if (dato && dato[0] && dato[0].reper && dato[0].reper.length>0) {
                reper=dato[0].reper
                a_rep=reper.split("|")
                for (sca=0;sca<a_rep.length;sca++) {
                    me=a_rep[sca].split(";")[0]
                    elem=a_rep[sca].split(";")[1]
                    idlav=a_rep[sca].split(";")[2]
                    refbox="rep"+me+elem
                    if (elem && idlav && idlav!="0") {
                        reflav="btnlav"+idlav
                        nomelav=$("#"+reflav).text().trim()
                        $("#"+refbox).data("idlav",idlav)
                        $("#"+refbox).text(nomelav)
                        color=$("#"+reflav).data('color')
                        $("#"+refbox).addClass('text-'+color)
                        $("#"+refbox).addClass('impegnato')
                    }
                }
            }

            dato=ris.info_assenti
            

            if (dato && dato[0] && dato[0].assenti && dato[0].assenti.length>0) {
                assenti=dato[0].assenti
                a_ass=assenti.split("|")
                for (sca=0;sca<a_ass.length;sca++) {
                    me=a_ass[sca].split(";")[0]
                    elem=a_ass[sca].split(";")[1]
                    idlav=a_ass[sca].split(";")[2]
                    refbox="ass"+me+elem
                    if (elem && idlav && idlav!="0") {
                        reflav="btnlav"+idlav
                        nomelav=$("#"+reflav).text().trim()
                        $("#"+refbox).data("idlav",idlav)
                        $("#"+refbox).text(nomelav)
                        color=$("#"+reflav).data('color')
                        $("#"+refbox).addClass('text-'+color)
                        $("#"+refbox).addClass('impegnato')
                    }
                }
            }   
            info_urgenze=ris.info_urgenze
            load_urgenze(info_urgenze)
      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	
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
    /*
    $(".allnomi").removeClass('btn btn-success').addClass('btn btn-outline-success')
    $("#btnlav"+idlav).removeClass('btn btn-outline-success').addClass('btn btn-success')
    */

    setsquadra.idlav=idlav
}

function unlock(idlav) {
    $('#btnlav'+idlav).prop('disabled',false);
    $('#unlock'+idlav).hide(120)
    
    removeA(setsquadra.unlock_id,idlav)
    setsquadra.unlock_id.push(idlav)
}

function setsquadra(m_e,box,rowbox,boxo) {
    if( typeof setsquadra.idlav == 'undefined' ) {
        //alert("Scegliere prima un lavoratore da assegnare!")
        return false
    }
    if( typeof setsquadra.unlock_id == 'undefined' ) setsquadra.unlock_id=new Array()

    idlav=setsquadra.idlav
  

    present=false
    $(".box"+m_e+box).each(function(){
        id_ref=$(this).data( "idlav")
        if (id_ref==idlav) present=true
    })
    if (boxo==box || boxo==1000) present=false

    reflav="btnlav"+idlav
    nomelav=$("#"+reflav).text().trim()
    color=$("#"+reflav).data('color')
    refbox="box"+m_e+box+rowbox
    if ($("#"+refbox).hasClass('impegnato')) return false;
     

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
    

    if (present==true) {
       // alert("Il lavoratore selezionato è già presente in questo BOX appalto!")
       return false
    }

    if (max==false ) {
        if (!$("#"+refbox).hasClass('impegnato')) {
            $("#"+refbox).addClass('impegnato')
            $("#"+refbox).addClass('text-'+color)

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
    return true

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
            const servizi = $("#servizi_svolti").val();
            if (servizi.length === 0) Swal.fire({
                icon: 'warning',
                title: 'Attenzione',
                text: 'Definire almeno un servizio!'
            });
            else save_appalto()
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
        $("#spanlav"+id_lav).hide();
        rowbox=strall_info[sca].split(";")[3]
        responsabile_targa=strall_info[sca].split(";")[4]
        if( typeof id_lav !== 'undefined' ) {
            if (id_lav!="0") {
                setsquadra.idlav=id_lav
                setsquadra(m_e,box,rowbox,1000)
                if (responsabile_targa.length>0) esito=setresp(m_e,box,rowbox,responsabile_targa,2);
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

    all_id_box="";
    car1="";car2="";
    ditta="";
    if (from==0) {
        ditta=$("#ditta"+m_e+box).data('iddit')
        car1=$("#car1"+m_e+box).data('targa')
        car2=$("#car2"+m_e+box).data('targa')
        console.log("car1x",car1,"car2x",car2)
        $(".box"+m_e+box).each(function(){
            lav=$(this).data( "idlav")
            if (all_id_box.length>0) all_id_box+=";" 
            if( typeof lav === 'undefined' ) all_id_box+="0"
            else all_id_box+=lav
        
        })  
        if( typeof car1 === 'undefined' ) car1=""
        if( typeof car2 === 'undefined' ) car2=""
    }
    

    if( typeof setresp.resp !== 'undefined' ) respo=setresp.resp
    else respo=[]

    all_id_boxes=""
    cars="";


    //construzione stringa concatenta per responsabile mezzi (sia from==0 che from1==1)
    targhe_resp="";

    $(".box").each(function(){
        lav=$(this).data( "idlav")
        m_e1=$(this).data( "m_e")
        box1=$(this).data( "box")
        elx=$(this).data( "el")
        
        var exist  = Object.keys(respo).includes(m_e1)
        if (exist==true) {
            ind=box1+"_"+elx
            console.log("ind",ind,"elx",elx,"m_e1",m_e1)
            var exist  = Object.keys(respo[m_e1]).includes(ind)
            if (exist==true) {
                if (respo[m_e1][ind].targa) {
                    if (targhe_resp.length>0) targhe_resp+="|"
                    targhe_resp+=respo[m_e1][ind].targa+";"+m_e1+";"+box1+";"+elx+";"+respo[m_e1][ind].idlav
                }
            }
        } 
    })  


    if (from==1) {
        $(".box").each(function(){
            lav=$(this).data( "idlav")
            m_e1=$(this).data( "m_e")
            box1=$(this).data( "box")
            if (all_id_boxes.length>0) all_id_boxes+=";" 
            if( typeof lav === 'undefined' ) all_id_boxes+=m_e1+"|"+box1+"|0"
            else all_id_boxes+=m_e1+"|"+box1+"|"+lav
        })      
    }

    refcar="";strcar="";
    carm1="";carm2="";
    carp1="";carp2="";
    for (scac=1;scac<=4;scac++) {
        if (scac==1) {refcar="car1M";strcar=carm1;}
        if (scac==2) {refcar="car2M";strcar=carm2;}
        if (scac==3) {refcar="car1P";strcar=carp1;}
        if (scac==4) {refcar="car2P";strcar=carp2;}
        
        $("."+refcar).each(function(){
            if (strcar.length!=0) strcar+="|"
            t="";
            if( typeof ($(this).data('targa')) != 'undefined' ) t=$(this).data('targa')
            strcar+=$(this).data('box')+";"+t
        })
        if (scac==1) carm1=strcar;
        if (scac==2) carm2=strcar;
        if (scac==3) carp1=strcar;
        if (scac==4) carp2=strcar;
    }

    ditte="";
    $(".ditte").each(function(){
        iddit=0
        if( typeof ($(this).data('iddit')) != 'undefined' ) iddit=$(this).data('iddit')
        if (ditte.length>0) ditte+="|"
        ditte+=$(this).data('box')+";"+$(this).data('m_e')+";"+iddit
    })
    console.log("ditte",ditte)

    reper="";
    $(".rep").each(function(){
        idrep=0
        if( typeof ($(this).data('idlav')) != 'undefined' ) idrep=$(this).data('idlav')
        id_elem=$(this).data('el')
        if (reper.length>0) reper+="|"
        reper+=$(this).data('m_e')+";"+id_elem+";"+idrep
    })    
    console.log("reper",reper)
    assenti="";
    $(".ass").each(function(){
        idass=0
        if( typeof ($(this).data('idlav')) != 'undefined' ) idass=$(this).data('idlav')
        id_elem=$(this).data('el')
        if (assenti.length>0) assenti+="|"
        assenti+=$(this).data('m_e')+";"+id_elem+";"+idass
    })    
    console.log("assenti",assenti)    
    
    /*
        in caso di salvataggio singolo (bottone salva dentro info),
        sono utili car1 e car2 (vedi nel blocco from==0 come vengono valorizzati)
        in caso di salvataggio multiplo ci son 4 variabili (carm1, carm2, carp1, carp2) per tutti i box (usando concatenazione stringhe).
        Stesso discorso per le ditte:
        salvataggio singolo (from==0) viene passato l'id della ditta in ditta
        in caso multiplo (from==1) vedi concatenazione id nella variabile ditte
    */        



      servizi=$("#servizi_svolti").val();

      timer = setTimeout(function() {	
      fetch(base_path+"/save_infoapp", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box+"&all_id_box="+all_id_box+"&all_id_boxes="+all_id_boxes+"&"+info+"&servizi="+servizi+"&car1="+car1+"&car2="+car2+"&carm1="+carm1+"&carm2="+carm2+"&carp1="+carp1+"&carp2="+carp2+"&ditta="+ditta+"&ditte="+ditte+"&reper="+reper+"&assenti="+assenti+"&targhe_resp="+targhe_resp+"&from="+from,
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

function getPersonInconsistencies() {
    let inconsistencies = [];

    const checkShift = (shift, shiftName) => {
        // Itera su tutti i box visibili per il turno specificato
        $(`[id^=tdbox${shift}]`).each(function() {
            if (!$(this).is(':visible')) return;

            const boxId = $(this).attr('id');
            const boxIndex = parseInt(boxId.replace(`tdbox${shift}`, ''));
            
            const info_app = $(`#infoapp${shift}${boxIndex}`);
            if (info_app.hasClass('bg-success')) {
                const expected_persons_text = info_app.text().trim().split(' ')[0];
                const expected_persons = parseInt(expected_persons_text, 10);

                if (!isNaN(expected_persons)) {
                    let assigned_persons = 0;
                    $(`.box${shift}${boxIndex}`).each(function() {
                        if ($(this).data('idlav')) {
                            assigned_persons++;
                        }
                    });

                    if (assigned_persons !== expected_persons) {
                        inconsistencies.push(`Appalto ${shiftName} (Box ${boxIndex + 1}): Previste ${expected_persons} persone, assegnate ${assigned_persons}.`);
                    }
                }
            }
        });
    };

    checkShift('M', 'Mattina');
    checkShift('P', 'Pomeriggio');
    return inconsistencies;
}

function save_all() {
    const inconsistencies = getPersonInconsistencies();

    const performSave = () => {
        const id_giorno_appalto = $("#id_giorno_appalto").val();
        save_info(id_giorno_appalto, "", 1000, 1);
        $("#btn_save_all").prop('disabled', true).html("<i class='fas fa-spinner fa-spin'></i> Salvataggio in corso...");
        save_appalto();
    };

    if (inconsistencies.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sono state trovate delle incongruenze',
            html: inconsistencies.join('<br>') + '<br><br><strong>Vuoi salvare comunque?</strong>',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sì, salva!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                performSave();
            }
        });
    } else {
        performSave();
    }
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
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box+"&from=0",
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(resp=>{
          if (resp.header=="OK") {
            $(".dati").val('');
            dap=$("#dap").val();
            all_servizi=$("#all_servizi").val().split("|");
            servizi_svolti=new Array()
            if (resp.info_appalto[0]) {
                $("#luogo_incontro").val(resp.info_appalto[0].luogo_incontro)
                $("#orario_incontro").val(resp.info_appalto[0].orario_incontro)
                $("#luogo_destinazione").val(resp.info_appalto[0].luogo_destinazione)
                $("#ora_destinazione").val(resp.info_appalto[0].ora_destinazione)
                //$("#data_servizio").val(resp.info_appalto[0].data_servizio)
                $("#data_servizio").val(dap)
                $("#numero_persone").val(resp.info_appalto[0].numero_persone)
                
                $("#nome_salma").val(resp.info_appalto[0].nome_salma)
                $("#note").val(resp.info_appalto[0].note)
                $("#note_fatturazione").val(resp.info_appalto[0].note_fatturazione)
                sv=resp.info_appalto[0].servizi_svolti
                if (sv && sv.length>0)
                    servizi_svolti=resp.info_appalto[0].servizi_svolti.split(",")
            } 

            html=`
                <select class="form-select select2" name="servizi_svolti[]" id="servizi_svolti" multiple>`
                for (sc=0;sc<all_servizi.length;sc++) {
                    id_serv=all_servizi[sc].split(";")[0]
                    serv=all_servizi[sc].split(";")[1]
                    html+="<option value='"+id_serv+"' ";
                    //if (in_array($id_servizio,$id_servizi)) echo " selected ";
                    if (servizi_svolti.includes(id_serv)) html+=" selected ";
                    if (resp.info_appalto.length==0) {
                        if (id_serv==3) html+=" selected "
                    }
                    html+=">"+serv+"</option>";
                }
            html+=`</select>`
            $("#div_serv").html(html)
            $('.select2').select2()

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
                        <input type='date' readonly class="form-control dati" id="data_servizio" name='data_servizio' required>
                    </div>
                    <div class="col-md-4">
                        <label for="numero_persone" class="col-form-label">Numero persone</label>
                        <input type="number" class="form-control dati" id="numero_persone" name='numero_persone' required>
                    </div>                            
                </div>     

                <div class="row">
                    <div class="col-md-8">
                        <label for="servizi_svolti" class="col-form-label">Servizi svolti</label>
                        <div id='div_serv'>
                            <i class='fas fa-spinner fa-spin'></i>
                        </div>
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
                <div class="row">
                    <div class="col-md-12">
                        <label for="note_fatturazione" class="col-form-label">Note Fatturazione</label>
                        <textarea class="form-control dati" id="note_fatturazione" name="note_fatturazione" row=4></textarea>
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
        Swal.fire({
            title: 'Sei sicuro?',
            text: "Verrà eliminato il mezzo e l'eventuale assegnazione del responsabile.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sì, elimina!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
            
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
        });
    }
}

function removeditta(id) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi disassociare la ditta da questo appalto?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, disassocia!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
    $("#"+id).removeData( "iddit", '' );
    html="<i class='fa-solid fa-location-dot'></i>"
    $("#"+id).html(html)
    $("#"+id).removeClass('bg-success').addClass('bg-secondary')
    $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    
}
    });
}

function resetbox(m_e,box,from) {
    html="";
    //from==0 || from==2 chiamata con riferimento a singolo box
    //from==1 chiamata con riferimento a tutti i box
    //from==2: da spostamento squadra da box a box
    $("#alert_confirm").hide()

    if (from!=2) {
        if (from === 0) {
            Swal.fire({
                title: 'Sei sicuro?',
                text: "Verranno resettati tutti i dati del box!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sì, resetta!',
                cancelButtonText: 'Annulla'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return false;
                }
                // Continue with reset if confirmed
                performReset(m_e, box, from);
            });
            return; // Stop execution here, will be continued in then()
        } else {
            if (!$('#conferma').is(":checked")) {
                $("#alert_confirm").show(130)
                return false
            }
            performReset(m_e, box, from);
        }
    }

    if (from==0) {
        html=inibox(m_e,box)
        $("#boxinfo"+m_e+box).html(html)
        html=inimezzi(m_e,box)
        $("#mezzi_info"+m_e+box).html(html)
        html=initditte(m_e,box)
        $("#ditte_info"+m_e+box).html(html)
        for (el=0;el<elemBox;el++) {
            setresp(m_e,box,el,0,1) 
        }
    }    
    else if (from!=2) {
        $(".box").each(function(){
            m_e=$(this).data( "m_e")
            box=$(this).data( "box")
            html=inibox(m_e,box)
            $("#boxinfo"+m_e+box).html(html)
            html=inimezzi(m_e,box)
            $("#mezzi_info"+m_e+box).html(html)
            html=initditte(m_e,box)
            $("#ditte_info"+m_e+box).html(html)
            for (el=0;el<elemBox;el++) {
                setresp(m_e,box,el,0,1) 
            }

        })
    }    
    $("#modalinfo").modal('hide')
    if (from==0)
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    
}

function performReset(m_e, box, from) {
    let html = "";
    if (from == 0) {
        html = inibox(m_e, box);
        $("#boxinfo" + m_e + box).html(html);
        html = inimezzi(m_e, box);
        $("#mezzi_info" + m_e + box).html(html);
        html = initditte(m_e, box);
        $("#ditte_info" + m_e + box).html(html);
        for (let el = 0; el < elemBox; el++) {
            setresp(m_e, box, el, 0, 1);
        }
    } else if (from != 2) {
        $(".box").each(function () {
            const m_e_local = $(this).data("m_e");
            const box_local = $(this).data("box");
            html = inibox(m_e_local, box_local);
            $("#boxinfo" + m_e_local + box_local).html(html);
            html = inimezzi(m_e_local, box_local);
            $("#mezzi_info" + m_e_local + box_local).html(html);
            html = initditte(m_e_local, box_local);
            $("#ditte_info" + m_e_local + box_local).html(html);
            for (let el = 0; el < elemBox; el++) {
                setresp(m_e_local, box_local, el, 0, 1);
            }
        });
    }
    $("#modalinfo").modal('hide');
    if (from == 0)
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning');
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
    id_giorno_appalto=$("#id_giorno_appalto").val()
    resetbox(m_e,box,0)
    let CSRF_TOKEN = $("#token_csrf").val();      
    base_path = $("#url").val();
    timer = setTimeout(function() {	
      fetch(base_path+"/deletebox", {
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
      .then(ris=>{
            resp=ris.header
            if (resp=="OK") {
                save_all()
                $("#tdbox"+m_e+box).hide(200)
                $("#modalinfo").modal('hide')
            } else Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: 'Problema occorso durante la cancellazione!'
            });

      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	    
}

function optionbox(m_e,box) {
    html=""
    html=`
        <div class="d-grid gap-2">

            <button class="btn btn-warning" id='btn_reset_all_box' type="button" disabled onclick="resetbox('`+m_e+`',`+box+`,1)">Reset ALL Box</button>              
            <hr>
            <button class="btn btn-warning" id='btn_dele_box' type="button" disabled onclick="deletebox('`+m_e+`',`+box+`)">Elimina box</button>
           


            <div class="form-check form-switch ml-4">
                <input class="form-check-input" type="checkbox" id="conferma" onclick="enable_reset()">
                <label class="form-check-label" for="conferma">Conferma operazione</label>
            </div>
            <div class="alert alert-light" role="alert" id='alert_confirm'>
                <b>Attenzione!</b><br>
                Prima di avviare l'operazione cliccare sul check di conferma<hr>
                <small>
                <b>N.B.: Da tener presente che per i primi due bottoni, l'operazione viene consolidata solo cliccando sul bottone 'Salva Tutto'</b>
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
        <font size='5px'>
            <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-2 car1`+m_e+` car`+m_e+`" style="font-size:.8em"  id='car1`+m_e+box+`'   data-m_e='`+m_e+`' data-refcar='car1' data-box='`+box+`' data-bs-toggle="tooltip" ondrop="dropHandlerMezzi(event)"  draggable="true" ondragstart="dragstartHandlerResp(event)" ondragover="dragoverHandlerMezzi(event)" data-placement="top" onclick="removemezzo(this.id)">
                Mezzo1
            </span>
        </font>
         <font size='5px'>
            <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-2 car2`+m_e+` car`+m_e+`" style="font-size:.8em"  id='car2`+m_e+box+`' data-m_e='`+m_e+`' data-refcar='car2' data-box='`+box+`' data-bs-toggle="tooltip" ondrop="dropHandlerMezzi(event)"  draggable="true" ondragstart="dragstartHandlerResp(event)" ondragover="dragoverHandlerMezzi(event)" data-placement="top" onclick="removemezzo(this.id)">
                Mezzo2
            </span>     
        </font>    
    `
    return html
}

function initditte(m_e,box) {
    html="";
    html+=`
        <span class="badge rounded-pill bg-secondary mr-2 mt-2 p-2 ditte" 
            id='ditta`+m_e+box+`' data-m_e='`+m_e+`' data-box='`+box+`'  ondragover="dragoverHandlerDitta(event)" ondrop="dropHandlerDitta(event)"  data-placement="top" onclick='removeditta(this.id)' style='width:300px;height:40px;white-space:collapse;font-size:1.3em'>
            <i class="fa-solid fa-location-dot"></i>
            
        </span>


    `
    return html
}

function accordion(m_e,box) {
    strm=$("#strm").val()
    strp=$("#strp").val()

    outmp="outline-";
    if (m_e=="M") {
        if (strm.includes(box)) outmp="";
    }
    if (m_e=="P") {
        if (strp.includes(box)) outmp="";
    }

    html=""
    

    html+=`    
    <td style='padding:10px' id='tdbox`+m_e+box+`'>
    
        <div class="d-grid gap-2 mb-2">
            <button id="btnbox`+m_e+box+`" type="button" class="btn btn-`+outmp+`info noprint"  data-target="#modalinfo" data-whatever="@mdo" onclick="detail_appalto('`+m_e+`',`+box+`)" >Info</button>
            <div class="panel-footer text-center">
                <font size='6px'>
                    <span id='infoapp`+m_e+box+`' class="badge rounded-pill bg-secondary pull-left p-2">
                        <i class="fa-solid fa-person"></i> 
                        <i class="ml-3 fa-solid fa-clock"></i>
                    </span>    
                </font>    
                <span class="pull-right noprint">

                    <a class="link-secondary" href='#' onclick="optionbox('`+m_e+`',`+box+`)"><i class="fa-solid fa-gears"></i> Option
                    </a><hr>

                    <a class="link-secondary" href='#' onclick="resetbox('`+m_e+`',`+box+`,0)">
                    <i class="fas fa-trash"></i>   
                        Reset box
                    </a>


                    <a class="ml-2 btn_make_msg link-secondary" href='#' onclick="make_msg('`+m_e+`',`+box+`,1)"><i class="fab fa-whatsapp"></i>  
                        Genera
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
                    <div class="accordion-body">
                        <div id='div_pers`+m_e+box+`'></div>
                    </div>
            </div>
        </div>   
    </td>      
    `
    return html
} 

function removelavall(idlav) {
    $(".box").each(function(){
        id_ref_lav=$(this).data( "idlav")
        if (id_ref_lav==idlav) {
            m_E=$("#"+this.id).data('m_e')
            boX=$("#"+this.id).data('box')
            
            for (elxx=0;elxx<elemBox;elxx++) {
                refboxx="box"+m_E+boX+elxx
                idlav1=$("#"+refboxx).data( "idlav")
                if (idlav==idlav1) removelav(m_E,boX,elxx)
            } 
            
        }
    })    
    
    $(".rep").each(function(){
        id_ref_lav=$(this).data( "idlav")
        if (id_ref_lav==idlav) {
            $("#"+this.id).data("idlav","")
            $("#"+this.id).text("__________")
            $("#"+this.id).removeClass('impegnato')
            remove_impegno(this.id)        
        }
    })

    $(".ass").each(function(){
        id_ref_lav=$(this).data( "idlav")
        if (id_ref_lav==idlav) {
            $("#"+this.id).data("idlav","")
            $("#"+this.id).text("__________")
            $("#"+this.id).removeClass('impegnato')
            remove_impegno(this.id)        
        }        
    })
}
function removelav(m_e,box,el) {
    refbox="box"+m_e+box+el
    $("#"+refbox).removeClass('impegnato')
    remove_impegno(refbox)

    $("#"+refbox).first().html('Assegna')
    $("#"+refbox).removeData( "idlav", '' );
    setresp(m_e,box,el,0,1) //contestualmente elimina mezzo eventuale assegnato
    
    $("#modalinfo").modal('hide')
}





function setresp(m_e,box,el,targa,from) {
    refbox="box"+m_e+box+el
    if( typeof setresp.resp == 'undefined' ) setresp.resp=[]
    resp=setresp.resp
 
    idlav_set=$("#"+refbox).data( "idlav")
    
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
    resp[m_e][ind].idlav=idlav_set
    if (from=="1") {
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    }
   
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
        Elimina mezzo assegnato</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>

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

function validation_urg() {
    $("#frm_urgenze").validate({
        rules: {
            lav_urg: {
                required: true
            },
            ditta_urg: {
                required: true
            },
            servizi_urg: {
                required: true
            },
            descr_urgenza: {
                required: true
            },                        
        },
        messages: {
            lav_urg: {
             required: "Selezionare il lavoratore",
            },
            ditta_urg: {
             required: "Selezionare la ditta",
            },
            servizi_urg: {
             required: "Selezionare il servizio",
            },
            descr_urgenza: {
             required: "Definire una descrizione",
            },



        },
  errorPlacement: function(label, element) {
    if (element.hasClass('web-select2')) {
      label.insertAfter(element.next('.select2-container')).addClass('mt-2 text-danger');
      select2label = label
    } else {
      label.addClass('mt-2 text-danger');
      label.insertAfter(element);
    }
  },        
        highlight: function(element) {
            $(element).parent().addClass('is-invalid')
            $(element).addClass('form-control-danger')
        },
        success: function(label, element) {
            $(element).parent().removeClass('is-invalid')
            $(element).removeClass('form-control-danger')
            label.remove();
        },
        submitHandler: function(form) {
           update_urg();
        },        
    })

      /*
    var forms = document.querySelectorAll('.needs-validation_urg')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
        })
    */



}
function load_urgenze(resp) {
    if (resp.length==0) return false
    urgenze.resp=resp
    for (sca=0;sca<resp.length;sca++) {
        id_urg=resp[sca].id
        id_ditta=resp[sca].id_ditta
        id_servizio=resp[sca].id_servizio
        id_lavoratore=resp[sca].id_lavoratore

        arrl=id_lavoratore.split(",")
        lav_urg_t="";
        for (x=0;x<arrl.length;x++) {
            id_lu=arrl[x]
            if (lav_urg_t.length>0) lav_urg_t+=", "
            if (lavall[id_lu]) lav_urg_t+=lavall[id_lu]
            else lav_urg_t+=id_lu
        }

        servizi_urg_t=id_servizio
        if (servall[id_servizio]) servizi_urg_t=servall[id_servizio]
        ditta_urg_t=id_ditta
        if (dittall[id_ditta]) ditta_urg_t=dittall[id_ditta]
        descr_urgenza=resp[sca].descrizione
        html=`
        <div id='div_urg`+id_urg+`'>
            <li class="list-group-item" id='urge`+id_urg+`'>
                <a href="#" onclick='urgenze(`+id_urg+`)' class="list-group-item list-group-item-action" aria-current="true">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><b>`+lav_urg_t+`</b></h5>
                    <small>`+servizi_urg_t+`</small>
                    </div>
                    <p class="mb-1">`+ditta_urg_t+`</p>
                    <small>`+descr_urgenza+`</small>
                </a>
            </li>
        </div>
        `
        $("#div_lista_urgenze").append(html)    
    }
}

function update_urg() {
    html=""
    $("#btn_save_urg").prop("disabled",true)
    $("#btn_save_urg").text("Salvataggio in corso...")
    $("#wait_urg").show()
    id_edit_urg=$("#id_edit_urg").val()

    
    lav_urg=$("#lav_urg").val()
    ditta_urg=$("#ditta_urg").val()
    servizi_urg=$("#servizi_urg").val()

    lav_urg_t=$("#lav_urg option:selected").text()
    ditta_urg_t=$("#ditta_urg option:selected").text()
    servizi_urg_t=$("#servizi_urg option:selected").text()

    descr_urgenza=$("#descr_urgenza").val()
    
    id_giorno_appalto=$("#id_giorno_appalto").val()
    let CSRF_TOKEN = $("#token_csrf").val();      
    base_path = $("#url").val();
    timer = setTimeout(function() {	
      fetch(base_path+"/save_urgenza", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&id_edit_urg="+id_edit_urg+"&lav_urg="+lav_urg+"&ditta_urg="+ditta_urg+"&servizi_urg="+servizi_urg+"&descr_urgenza="+descr_urgenza,
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(ris=>{
            urgenze.resp=ris.info_urgenze
            resp=ris.header
            $("#btn_save_urg").prop("disabled",false)
            $("#btn_save_urg").text("Salva urgenza")
            $("#wait_urg").hide()            
            if (resp=="OK") {
                id_ref=ris.id_ref
                html+=`
                    <li class="list-group-item" id='urge`+id_ref+`'>
                        <a href="#" onclick='urgenze(`+id_ref+`)' class="list-group-item list-group-item-action" aria-current="true">
                            <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1"><b>`+lav_urg_t+`</b></h5>
                            <small>`+servizi_urg_t+`</small>
                            </div>
                            <p class="mb-1">`+ditta_urg_t+`</p>
                            <small>`+descr_urgenza+`</small>
                        </a>
                    </li>
                `
                if (id_edit_urg!="0") 
                    $("#div_urg"+id_edit_urg).html(html)
                else   {
                    html1=`<div id='div_urg`+id_ref+`'>`+html+`</div>`
                    $("#div_lista_urgenze").append(html1)
                }
                
                $("#modalinfo").modal('hide')

            } else Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: 'Problema occorso durante il salvataggio!'
            });

      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	


}

function dele_urg(id_urg) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi cancellare l'urgenza?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sì, cancella!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
    let CSRF_TOKEN = $("#token_csrf").val();
    html="<i class='fas fa-spinner fa-spin'></i>"
    
    base_path = $("#url").val();
    timer = setTimeout(function() {	
    $("#urge"+id_urg).html('Cancellazione in corso...')
      fetch(base_path+"/dele_urg", {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: "_token="+ CSRF_TOKEN+"&id_urg="+id_urg,
      })
      .then(response => {
          if (response.ok) {
            return response.json();
          }
      })
      .then(resp=>{
          if (resp.header=="OK") {
            $("#urge"+id_urg).remove()
            $("#modalinfo").modal('hide')
          } 
      })
      .catch(status, err => {
          return console.log(status, err);
      })     

    }, 800)	 
        }
    });
}

function urgenze(id_urg) {
    indice_resp="New"
    if( typeof urgenze.resp == 'undefined' ) resp=""
    else {
        resp=urgenze.resp
        for (sca=0;sca<resp.length;sca++) {
            _id=resp[sca].id 
            if (_id==id_urg) indice_resp=sca
        }
    }

    id_edit_urg=0
    if (id_urg!="New") id_edit_urg=id_urg
    dap1=$("#dap1").val();
   
    html=""
    html+=`
        <center>
            <h4>Definizione Urgenza del `+dap1+`<h4>
        </center><hr>
		<form method='post'class="row g-3 needs-validation_urg" novalidate id='frm_urgenze' name='frm_urgenze' autocomplete="off">
            <input type='hidden' name='id_edit_urg' id='id_edit_urg' value='`+id_edit_urg+`'>
            <div class="row">
                <div class="col-md-4">
                    <label for="ditta_urg" class="control-label">Ditta</label>
                    <div id='div_ditta_urg'>
                        <i class='fas fa-spinner fa-spin'></i>
                    </div>
                </div>            
                <div class="col-md-8">
                    <label for="servizi_urg" class="control-label">Servizio da svolgere</label>
                    <div id='div_serv1'>
                        <i class='fas fa-spinner fa-spin'></i>
                    </div>
                </div>
            </div>           
            <div class="row">         
                <div class="col-md-4">
                    <label for="lav_urg" class="control-label">Lavoratore</label>
                    <div id='div_lav_urg'>
                        <i class='fas fa-spinner fa-spin'></i>
                    </div>
                </div>              
                <div class="col-md-8">
                    <label for="descr_urgenza" class="control-label">Descrizione</label>
                    <input type='text' class="form-control dati" id="descr_urgenza" name="descr_urgenza" >
                </div>                            
            </div>    
            <hr>
            <button type="submit" id='btn_save_urg' class="btn btn-primary">Salva urgenza</button>`
            if (id_urg!="New") {
                html+=`
                <button type="button" onclick='dele_urg(`+id_urg+`)' id='btn_dele_urg' class="btn btn-warning">Elimina urgenza</button>`
                
            }

            html+=`
            <span id='wait_urg' style='display:none'><i class='fas fa-spinner fa-spin'></i></span>
        </form>      

    `
    /* salva urgenza gestito da validation_urg() */
    $("#body_content").html(html)
    

    elenco_lav=$("#elenco_lav").val().split("|");
    lav_u=new Array()
    html=`
        <select class="form-select select2" name="lav_urg[]" id="lav_urg" multiple required>
            <option value=''>Select...</option>
        `
        
        for (sc=0;sc<elenco_lav.length;sc++) {
            id_l=elenco_lav[sc].split(";")[0]
            lavu=elenco_lav[sc].split(";")[1]
            html+="<option value='"+id_l+"' ";
            //if (in_array($id_servizio,$id_servizi)) echo " selected ";
            if (lav_u.includes(id_l)) html+=" selected ";
            html+=">"+lavu+"</option>";
        }
    html+=`</select>`    
    $("#div_lav_urg").html(html)   

    alld=$("#alld").val().split("|");
    ditta_u=new Array()
    html=`
        <select class="form-select select2" name="ditta_urg" id="ditta_urg" required>
            <option value=''>Select...</option>
        `
        
        for (sc=0;sc<alld.length;sc++) {
            id_d=alld[sc].split(";")[0]
            ditt=alld[sc].split(";")[1]
            html+="<option value='"+id_d+"' ";
            //if (in_array($id_servizio,$id_servizi)) echo " selected ";
            if (ditta_u.includes(id_d)) html+=" selected ";
            html+=">"+ditt+"</option>";
        }
    html+=`</select>`    
    $("#div_ditta_urg").html(html)    

    all_servizi=$("#all_servizi").val().split("|");
    servizi_svolti=new Array()
    html=`
        <select class="form-select select2" name="servizi_urg" id="servizi_urg" required>
            <option value=''>Select...</option>
        `
        for (sc=0;sc<all_servizi.length;sc++) {
            id_serv=all_servizi[sc].split(";")[0]
            serv=all_servizi[sc].split(";")[1]
            html+="<option value='"+id_serv+"' ";
            //if (in_array($id_servizio,$id_servizi)) echo " selected ";
            if (servizi_svolti.includes(id_serv)) html+=" selected ";
            html+=">"+serv+"</option>";
        }
    html+=`</select>`    
    $("#div_serv1").html(html)

    $('#servizi_urg').select2().attr('required',true);

    $('.select2').select2()
    validation_urg()

    if (indice_resp!="New") {
        id_ditta=resp[indice_resp].id_ditta
        $("#ditta_urg").val(id_ditta)
        id_servizio=resp[indice_resp].id_servizio
        $("#servizi_urg").val(id_servizio)
        id_lavoratore=resp[indice_resp].id_lavoratore
        arrl=id_lavoratore.split(",")
        $("#lav_urg").val(arrl);
        descr_urgenza=resp[indice_resp].descrizione
        $("#descr_urgenza").val(descr_urgenza)
    }

    $("#modalinfo").modal('show')
}


function inibox(m_e,box) {
    html="";
    for (el=0;el<elemBox;el++) {
        html+=`    
        <a href="#" class="list-group-item  clearfix itemlist list-group-item-action box box`+m_e+box+`" id='box`+m_e+box+el+`' data-m_e='`+m_e+`' data-box=`+box+` data-el=`+el+` aria-current="true" onclick="action_lav('`+m_e+`',`+box+`,`+el+`)"  >
            Assegna
        </a>
        `
    }
    return html
}

function action_rep(m_e,el) {
    refrep="rep"+m_e+el
    idlav=($("#"+refrep).data("idlav"))
    if (!idlav || idlav.length == 0) return false;
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi rimuovere il nominativo dalla lista reperibilità?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, rimuovi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            remove_impegno(refrep);
            $("#" + refrep).removeClass('impegnato').first().html("__________").removeData("idlav", '');
            $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning');
        }
    });
}
function inirep(sc) {
    m_e="";txt_rep="";
    
    if (sc==1) {m_e="Ma";txt_rep="Mattino"}
    if (sc==2) {m_e="Mb";txt_rep="Pomeriggio"}
    if (sc==3) {m_e="Pa";txt_rep="Primo Notturno"}
    if (sc==4) {m_e="Pb";txt_rep="Secondo Notturno"}
    /*
    if (sc==3) {m_e="Pa";txt_rep="Primo Notturno"}
    if (sc==4) {m_e="Pb";txt_rep="Secondo Notturno"}
    */



    html="";
    if (sc==1) {
        html+=`
            <div class="alert alert-light" role="alert">
                Reperibilità
                    <a class="link-success noprint" style="color: #22391478 !important;" href='#' onclick="msg_rep()"><i class="fab fa-whatsapp"></i>  
                        Genera
                    </a>
            </div>
        `    
    }
    to=elemRep
    if (sc==3 || sc==4) to=1
    html+=`
        <div id='div_rep`+m_e+`' class="card">
    
            <div class="card-body"><font size='2px'><b>`+txt_rep+`</b></font>
                <div id='boxrep`+m_e+`' class="list-group"  ondrop="dropHandlerRep(event)"   ondragover="dragoverHandlerRep(event)" draggable="true" ondragstart="dragstartHandlerRep(event)">`
                for (el=0;el<to;el++) {
                    html+=`    


                    <div style='line-height:1.6;' id='sparep' >
                        <font size='1rem' class='repview' >
                            <a href="javascript:void(0)" class="rep rep`+m_e+`" id='rep`+m_e+el+`' data-m_e='`+m_e+`' data-el=`+el+` aria-current="true" onclick="action_rep('`+m_e+`',`+el+`)">
                                __________
                            </a>
                        </font>
                    </div>                    
                    `
                }
                html+=`</div>
            </div>                                    
    </div>`
    return html
}


function action_ass(m_e,el) {
    refass="ass"+m_e+el
    idlav=($("#"+refass).data("idlav"))
    if (!idlav || idlav.length == 0) return false;
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Vuoi rimuovere il nominativo dalla lista assenti?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, rimuovi!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            $("#spanlav" + idlav).show();
            $("#" + refass).removeClass('impegnato');
            remove_impegno(refass);
            $("#" + refass).first().html("__________").removeData("idlav", '');
            $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning');
        }
    });
}
function iniass(sc) {
    m_e="";txt_ass="";
    
    if (sc==1) {m_e="Ma";txt_ass="Mattino"}
    if (sc==2) {m_e="Mb";txt_ass="Pomeriggio"}


    html="";
    if (sc==1) {
        html+=`
            <div class="alert alert-light" role="alert">
                Assenti
            </div>
        `    
    }
    html+=`
        <div id='div_ass`+m_e+`' class="card">
    
            <div class="card-body"><font size='2px'><b>`+txt_ass+`</b></font>
                <div id='boxass`+m_e+`' class="list-group"  ondrop="dropHandlerAss(event)"   ondragover="dragoverHandlerAss(event)" draggable="true" ondragstart="dragstartHandlerAss(event)">`
                for (el=0;el<elemAss;el++) {
                    html+=`   
                        <div style='line-height:1.6;' id='spanass'  >
                            <font size='1rem' class='assview'>
                                <a href="javascript:void(0)" class="ass ass`+m_e+`" id='ass`+m_e+el+`' data-m_e='`+m_e+`' data-el=`+el+` aria-current="true" onclick="action_ass('`+m_e+`',`+el+`)">
                                    __________
                                </a>
                            </font>
                        </div>
                    `
                }
                html+=`</div>
            </div>                                    
    </div>`
    return html
}

function newapp(m_e,from) {
    if (from=="man") {
        tipo_box="mattutino"
        if (m_e == "P") tipo_box = "pomeridiano";
        Swal.fire({
            title: 'Sei sicuro?',
            text: "Vuoi creare un nuovo box " + tipo_box + "?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sì, crea!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                createNewAppBox(m_e, from);
            }
        });
    } else {
        createNewAppBox(m_e, from);
    }
}

function createNewAppBox(m_e, from) {
    box=0
    $(".box"+m_e).each(function(){
        box++
    })
    html=""
   
    html=accordion(m_e,box)
    $('#tbApp'+m_e+' tr').append(html)
    

   
    html="";
    html+=`
        
            <div id='div_box`+m_e+box+`' class="card box`+m_e+`" style="width: 13rem;" >
                <div class="card-body" id='boxall`+m_e+box+`'  ondrop="dropHandlerBox(event)"   ondragover="dragoverHandlerBox(event)" draggable="true" ondragstart="dragstartHandlerBox(event)" >
                    <div id='boxinfo`+m_e+box+`' class="list-group"  ondrop="dropHandler(event)"   ondragover="dragoverHandler(event)" draggable="true" ondragstart="dragstartHandlerLav(event)">`
                        html+=inibox(m_e,box);    
                        html+=`
                    </div>
                </div>                                    
            </div>
        
    `
    $("#div_pers"+m_e+box).html(html)
    
    if (from=="man") {
        Swal.fire({
            icon: 'success',
            title: 'Successo',
            text: 'Appalto aggiunto in coda!'
        });
        $("#btn_save_all").removeClass('btn-outline-success').removeClass('btn-warning').addClass('btn-warning')
    }
}

function setZoomGeneric(value, from, targetDiv, wrapperDiv, resetButtonId, sliderId) {
    const scaleValue = parseFloat(value);
    if (targetDiv) {
        $(targetDiv).css({
            'transform': 'scale(' + scaleValue + ')',
            'transform-origin': 'left top',
            'width': (100 / scaleValue) + '%'
        });

        const divTb = $(targetDiv);
        if (divTb.length > 0) {
            const originalHeight = divTb.get(0).scrollHeight;
            // Use Math.ceil to prevent rounding errors that could make the container too small
            const scaledHeight = Math.ceil(originalHeight * scaleValue);
            $(wrapperDiv).css({
                'height': scaledHeight + 'px',
                'overflow-y': 'hidden'
            });
        }
    }

    if (from == 1) $("#div_side").hide(120);
    if (scaleValue <= zoomI) $("#div_side").show(120);

    // Gestisce la visibilità del bottone di reset
    const btn = $(resetButtonId);
    if (btn.length > 0) {
        if (scaleValue.toFixed(2) !== zoomI.toFixed(2)) {
            btn.show();
        } else {
            btn.hide();
        }
    }

    if (sliderId) {
        $(sliderId).val(scaleValue);
    }
}

function setZoomAll(value, from) {
    // Update Morning section
    setZoomGeneric(value, from, '#div_tb_m', '#zoom_wrapper_m', '#resetZoomBtnM', '#zoom_slider_m');
    // Update Afternoon section
    setZoomGeneric(value, from, '#div_tb_p', '#zoom_wrapper_p', '#resetZoomBtnP', '#zoom_slider_p');
    // Update the "All" reset button
    setZoomGeneric(value, from, null, null, '#resetZoomBtnAll', null);
}

function setZoomM(value, from) {
    setZoomGeneric(value, from, '#div_tb_m', '#zoom_wrapper_m', '#resetZoomBtnM', '#zoom_slider_m');
}

function setZoomP(value, from) {
    setZoomGeneric(value, from, '#div_tb_p', '#zoom_wrapper_p', '#resetZoomBtnP', '#zoom_slider_p');
}

function get_msg() {
    msg=$("#txt_msg").val()
    msg=msg.replace(/\n/g,'%0A')
    $("#a_send").attr('href', 'whatsapp://send?text='+msg);
    $("#modalinfo").modal('hide')
}

function msg_rep() {
    saveall=$("#btn_save_all").hasClass('btn-outline-success')
    if (saveall==false) {
        Swal.fire({
            icon: 'warning',
            title: 'Salvataggio in sospeso',
            text: "C'è un salvataggio in sospeso. Prima di procedere con la creazione del messaggio è necessario salvare le modifiche in corso."
        });
        return false;
    }
    testo="";load=""
    load="<i class='fas fa-spinner fa-spin'></i>"
    html=`
        <div id='div_load_msg'>`+load+`</div>
        <div class="mb-3">
            <label for="txt_msg" class="form-label">Testo del messaggio</label>
            <textarea class="form-control" id="txt_msg" rows="10"></textarea>
        </div>
        <hr>
        <a aria-label="Send Appalto" id='a_send' href="#">
            <button type="button" class="btn btn-success btn-sm" onclick='get_msg()'>
                <i class="fab fa-whatsapp"></i> Invia
            </button>
        </a>
    `        
    $("#modalinfo").modal('show')
    $("#body_content").html(html)
    html=""

    for (sc=1;sc<=4;sc++) {
        nomi_rep=""
        if (sc==1) me="Ma"
        if (sc==2) me="Mb"
        if (sc==3) me="Pa"
        if (sc==4) me="Pb"
        $(".rep"+me).each(function(){
            id_ref=$(this).data( "idlav")
            if (id_ref) {
                if (lavall[id_ref]) lav_t=lavall[id_ref]
                if (lav_t.trim()!="0") nomi_rep+=lav_t+"\n"
            }
        })
        if (nomi_rep.length!=0) {
            if (sc==1) html+="Mattina a disposizione:\n"
            if (sc==2) html+="Pomeriggio a disposizione:\n"
            if (sc==3) html+="Primo turno notturno(18:00-24:00):\n"
            if (sc==4) html+="Secondo turno notturno(24:00-6:00 ):\n"
            html+=nomi_rep+"\n"
        }
    }
    if (html.length>0) html+="Fine Squadre"
    $("#txt_msg").val(html)
    $("#div_load_msg").empty()

}

function make_msg(m_e,box,from) {
    if (from==1) {
        saveall=$("#btn_save_all").hasClass('btn-outline-success')
        if (saveall==false) {
            Swal.fire({
                icon: 'warning',
                title: 'Salvataggio in sospeso',
                text: "C'è un salvataggio in sospeso. Prima di procedere con la creazione del messaggio è necessario salvare le modifiche in corso."
            });
            return false;
        }
    }
    testo="";load=""
    if (from==1) load="<i class='fas fa-spinner fa-spin'></i>"
    html=`
        <div id='div_load_msg'>`+load+`</div>
        <div class="mb-3">
            <label for="txt_msg" class="form-label">Testo del messaggio</label>
            <textarea class="form-control" id="txt_msg" rows="10"></textarea>
        </div>
        <hr>
        <a aria-label="Send Appalto" id='a_send' href="#">
            <button type="button" class="btn btn-success btn-sm" onclick='get_msg()'>
                <i class="fab fa-whatsapp"></i> Invia
            </button>
        </a>
    `    
    $("#modalinfo").modal('show')
    $("#body_content").html(html)
    if (from==0) {
        $("#div_load_msg").empty()
    }

    if (from==1) {
        ditta=$("#ditta"+m_e+box).html()
        spanElement = document.getElementById("ditta"+m_e+box);
        ditta = spanElement.textContent.trim();        

        id_giorno_appalto=$("#id_giorno_appalto").val()
        let CSRF_TOKEN = $("#token_csrf").val();
        
       
        base_path = $("#url").val();
        timer = setTimeout(function() {	
        fetch(base_path+"/check_allestimento", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: "_token="+ CSRF_TOKEN+"&id_giorno_appalto="+id_giorno_appalto+"&m_e="+m_e+"&box="+box+"&from=0",
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
        })
        .then(resp=>{
            if (resp.header=="OK") {
                infoa=resp.info_appalto  
                html="";
                html+="*"+ditta+"*\n\n"
                html+="Alle ore "
                orario_incontro="";
                luogo_incontro="";
                if (infoa[0] && infoa[0].orario_incontro && infoa[0].orario_incontro.length>0) orario_incontro=infoa[0].orario_incontro
                if (infoa[0] && infoa[0].luogo_incontro && infoa[0].luogo_incontro.length>0) luogo_incontro=infoa[0].luogo_incontro
                html+="*"+orario_incontro+"*, _"+luogo_incontro+"_"
                car1=$("#car1"+m_e+box).data('targa');car2=$("#car2"+m_e+box).data('targa');
                al_m1="";al_m2="";
                if (alias_mezzi[car1]) al_m1=alias_mezzi[car1]
                if (alias_mezzi[car2]) al_m2=alias_mezzi[car2]
                
                // if (car1) html+=", "+car1
                // if (car2) html+=", "+car2
                
                if (car1) html+=", "+al_m1
                if (car2) html+=", "+al_m2
                

                html+="\n\n"

                infobox=resp.infobox.split(";")
                resplav=""
                for (sca=0;sca<infobox.length;sca++) {
                    idl=infobox[sca]
                    lav_t=idl
                    if (lavall[idl]) lav_t=lavall[idl]
                    if (lav_t.trim()!="0") {
                        if (resplav.length!=0) resplav+=", "
                        resplav+=lav_t
                    }
                }
                
                html+=resplav
                ora_destinazione="";luogo_destinazione="";
                if (infoa[0] && infoa[0].ora_destinazione && infoa[0].ora_destinazione.length>0) ora_destinazione=infoa[0].ora_destinazione
                if (infoa[0] && infoa[0].luogo_destinazione && infoa[0].luogo_destinazione.length>0) luogo_destinazione=infoa[0].luogo_destinazione
                
                html+="\n*"+ora_destinazione+"*, *"+luogo_destinazione+"*"  

                note="";
                if (infoa[0] && infoa[0].note && infoa[0].note.length>0) note=infoa[0].note

                
                html+="\n\n"+note

                
                resplav="";inforesp="";
                if (resp.resp_targa && resp.resp_targa.length>0) {
                    resp_targa=resp.resp_targa.split(";")
                    for (sca=0;sca<resp_targa.length;sca++) {
                        if (inforesp.length>0) inforesp+=", "
                        targa_r=resp_targa[sca].split("|")[0]
                        lav_resp=resp_targa[sca].split("|")[1]
                        lav_r=lav_resp
                        if (lavall[lav_resp]) lav_r=lavall[lav_resp]
                        
                        let mezzo_display = targa_r;
                        if (alias_mezzi[targa_r] && alias_mezzi[targa_r].length > 0) {
                            mezzo_display = alias_mezzi[targa_r];
                        }
                        
                        inforesp+="Responsabile mezzo _"+mezzo_display+"_ : *"+lav_r+"*"
                    }              
                    html+="\n"+inforesp
                }

                $("#txt_msg").val(html)
                $("#div_load_msg").empty()



            }
            else {
                $("#div_load_msg").html("<font color='red'>Errore durante il recupero dei dati</font>")
            }

        })
        .catch(status, err => {
            return console.log(status, err);
        })     

        }, 800)    
    }
}

//#region Funzioni di formattazione per i Log
const logKeyLabels = {
    'all_workers_data': 'Lavoratori Appalti',
    'vehicles_morning_1': 'Veicoli Mattina 1',
    'vehicles_morning_2': 'Veicoli Mattina 2',
    'vehicles_afternoon_1': 'Veicoli Pomeriggio 1',
    'vehicles_afternoon_2': 'Veicoli Pomeriggio 2',
    'companies_data': 'Ditte Appalti',
    'on_call_data': 'Reperibilità',
    'absent_data': 'Assenti',
    'all_responsibles': 'Responsabili Mezzi',
    'workers_in_box': 'Lavoratori nel Box',
    // per salvataggio singolo
    'luogo_incontro': 'Luogo Incontro',
    'orario_incontro': 'Orario Incontro',
    'luogo_destinazione': 'Luogo Destinazione',
    'ora_destinazione': 'Ora Destinazione',
    'data_servizio': 'Data Servizio',
    'numero_persone': 'Numero Persone',
    'servizi_svolti': 'Servizi Svolti',
    'nome_salma': 'Nome Salma',
    'note': 'Note',
    'note_fatturazione': 'Note Fatturazione',
    'ditta_id': 'Ditta',
    'car1_targa': 'Targa Mezzo 1',
    'car2_targa': 'Targa Mezzo 2',
};

function getLogKeyLabel(key) {
    return logKeyLabels[key] || key.replace(/_/g, ' ');
}
function formatWorkersData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = {}; // { M: { 0: [], 1: [] }, P: { 0: [], 1: [] } }
    const parts = dataString.split(';');
    parts.forEach(part => {
        const [m_e, box, lav_id] = part.split('|');
        if (m_e && box !== undefined && lav_id && lav_id !== '0') {
            if (!assignments[m_e]) assignments[m_e] = {};
            if (!assignments[m_e][box]) assignments[m_e][box] = [];
            const lav_name = lavall[lav_id] || `ID:${lav_id}`;
            assignments[m_e][box].push(lav_name);
        }
    });

    let html = '<ul class="list-unstyled" style="margin:0; padding-left: 1.2em; text-indent: -1.2em;">';
    for (const m_e in assignments) {
        const turno = m_e === 'M' ? 'Mattina' : 'Pomeriggio';
        html += `<li><strong>${turno}:</strong><ul class="list-unstyled" style="padding-left: 1.2em;">`;
        const sortedBoxes = Object.keys(assignments[m_e]).sort((a, b) => a - b);
        for (const box of sortedBoxes) {
            html += `<li>Box ${parseInt(box)+1}: ${assignments[m_e][box].join(', ')}</li>`;
        }
        html += '</ul></li>';
    }
    html += '</ul>';
    return html;
}

function formatVehiclesData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = [];
    const parts = dataString.split('|');
    parts.forEach(part => {
        const [box, targa] = part.split(';');
        if (box !== undefined && targa) {
            const alias = alias_mezzi[targa] || targa;
            assignments.push(`Box ${parseInt(box)+1}: ${alias}`);
        }
    });
    return assignments.join('<br>');
}

function formatCompaniesData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = {};
    const parts = dataString.split('|');
    parts.forEach(part => {
        const [box, m_e, ditta_id] = part.split(';');
        if (box !== undefined && m_e && ditta_id && ditta_id !== '0') {
            const turno = m_e === 'M' ? 'Mattina' : 'Pomeriggio';
            if (!assignments[turno]) assignments[turno] = [];
            const ditta_name = dittall[ditta_id] || `ID:${ditta_id}`;
            assignments[turno].push({box: parseInt(box), name: `Box ${parseInt(box)+1}: ${ditta_name}`});
        }
    });

    let html = '<ul class="list-unstyled" style="margin:0; padding-left: 1.2em; text-indent: -1.2em;">';
    for (const turno in assignments) {
        html += `<li><strong>${turno}:</strong><ul class="list-unstyled" style="padding-left: 1.2em;">`;
        assignments[turno].sort((a,b) => a.box - b.box).forEach(assignment => {
            html += `<li>${assignment.name}</li>`;
        });
        html += '</ul></li>';
    }
    html += '</ul>';
    return html;
}

function formatReperData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = {};
    const parts = dataString.split('|');
    const fasce = { 'Ma': 'Mattino', 'Mb': 'Pomeriggio', 'Pa': 'Primo Notturno', 'Pb': 'Secondo Notturno' };
    parts.forEach(part => {
        const [fascia, elem, lav_id] = part.split(';');
        if (fascia && lav_id && lav_id !== '0') {
            const fascia_name = fasce[fascia] || fascia;
            if (!assignments[fascia_name]) assignments[fascia_name] = [];
            assignments[fascia_name].push(lavall[lav_id] || `ID:${lav_id}`);
        }
    });

    let html = '<ul class="list-unstyled" style="margin:0;">';
    for (const fascia_name in assignments) {
        html += `<li><strong>${fascia_name}:</strong> ${assignments[fascia_name].join(', ')}</li>`;
    }
    html += '</ul>';
    return html;
}

function formatAbsentData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = {};
    const parts = dataString.split('|');
    const fasce = { 'Ma': 'Mattino', 'Mb': 'Pomeriggio' };
    parts.forEach(part => {
        const [fascia, elem, lav_id] = part.split(';');
        if (fascia && lav_id && lav_id !== '0') {
            const fascia_name = fasce[fascia] || fascia;
            if (!assignments[fascia_name]) assignments[fascia_name] = [];
            assignments[fascia_name].push(lavall[lav_id] || `ID:${lav_id}`);
        }
    });

    let html = '<ul class="list-unstyled" style="margin:0;">';
    for (const fascia_name in assignments) {
        html += `<li><strong>${fascia_name}:</strong> ${assignments[fascia_name].join(', ')}</li>`;
    }
    html += '</ul>';
    return html;
}

function formatResponsiblesData(dataString) {
    if (!dataString) return '<em>(vuoto)</em>';
    const assignments = [];
    const parts = dataString.split('|');
    parts.forEach(part => {
        const [targa, m_e, box, rowbox, lav_id] = part.split(';');
        if (targa && lav_id) {
            const alias = alias_mezzi[targa] || targa;
            const lav_name = lavall[lav_id] || `ID:${lav_id}`;
            assignments.push(`Mezzo ${alias}: ${lav_name}`);
        } else if (targa) {
            // Fallback per log vecchi senza lav_id
            const turno = m_e === 'M' ? 'Mattina' : 'Pomeriggio';
            const alias = alias_mezzi[targa] || targa;
            assignments.push(`Mezzo ${alias} (Turno ${turno}, Box ${parseInt(box)+1}, Pos ${rowbox})`);
        }
    });
    return assignments.join('<br>');
}

function formatLogValue(key, value, payload) {
    if (value === null || value === undefined || value === '') return '<em>(vuoto)</em>';

    switch (key) {
        case 'all_workers_data': case 'workers_in_box':
            return formatWorkersData(value);
        case 'vehicles_morning_1':
        case 'vehicles_morning_2':
        case 'vehicles_afternoon_1':
        case 'vehicles_afternoon_2':
            return formatVehiclesData(value);
        case 'companies_data':
            return formatCompaniesData(value);
        case 'on_call_data':
            return formatReperData(value);
        case 'absent_data':
            return formatAbsentData(value);
        case 'all_responsibles':
            return formatResponsiblesData(value);
        case 'ditta_id':
            return dittall[value] || `ID:${value}`;
        case 'servizi_svolti':
            if (!value) return '<em>(vuoto)</em>';
            const service_ids = Array.isArray(value) ? value : String(value).split(',');
            return service_ids.map(id => servall[id] || `ID:${id}`).join(', ');
        case 'car1_targa':
        case 'car2_targa':
             return alias_mezzi[value] || value;
        case 'luogo_incontro': case 'orario_incontro':
        case 'luogo_destinazione': case 'ora_destinazione':
        case 'data_servizio': case 'numero_persone':
        case 'nome_salma': case 'note':
        case 'note_fatturazione':
            return value;
        default:
            return value;
    }
}

function getDetailedDiffHtml(prevPayload, currentPayload) {
    let diffHtml = '<div style="font-family: sans-serif; font-size: 0.9em;">';
    let hasChanges = false;

    const allKeys = new Set([...Object.keys(prevPayload), ...Object.keys(currentPayload)]);

    for (const key of allKeys) {
        const prevValue = prevPayload[key];
        const currentValue = currentPayload[key];

        const prevString = JSON.stringify(prevValue);
        const currentString = JSON.stringify(currentValue);

        if (prevString !== currentString) {
            hasChanges = true;

            let formattedPrev = formatLogValue(key, prevValue, prevPayload);
            let formattedCurrent = formatLogValue(key, currentValue, currentPayload);

            diffHtml += `<div style="margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px solid #ddd;">`;
            diffHtml += `<strong style="text-transform: capitalize;">${getLogKeyLabel(key)}:</strong><br>`;
            if (prevValue !== undefined && prevValue !== '') {
                diffHtml += `<div style="color: #dc3545; padding-left: 10px; background: #f8d7da; border-radius: 4px; margin-top: 5px; padding: 5px;">${formattedPrev}</div>`;
            }
            if (currentValue !== undefined && currentValue !== '') {
                diffHtml += `<div style="color: #198754; padding-left: 10px; background: #d1e7dd; border-radius: 4px; margin-top: 5px; padding: 5px;">${formattedCurrent}</div>`;
            }
            diffHtml += `</div>`;
        }
    }

    if (!hasChanges) {
        diffHtml += '<p style="margin: 0; color: #888;">Nessuna modifica rilevata tra questo salvataggio e il precedente.</p>';
    }
    diffHtml += '</div>';
    return diffHtml;
}

function formatPayloadAsList(payload) {
    if (!payload || Object.keys(payload).length === 0) return '<p>Dati non disponibili.</p>';

    let listHtml = '<ul class="list-group list-group-flush">';

    const keys = Object.keys(payload);

    for (const key of keys) {
        const currentValue = payload[key];

        listHtml += `<li class="list-group-item" style="padding: .5rem 0; background-color: transparent;">`;
        listHtml += `<strong style="display: inline-block; width: 180px; vertical-align: top; font-size: 0.9em; text-transform: capitalize;">${getLogKeyLabel(key)}:</strong> `;

        let displayValue = formatLogValue(key, currentValue, payload);

        listHtml += `<div style="display: inline-block; width: calc(100% - 190px);">${displayValue}</div>`;
        listHtml += '</li>';
    }
    listHtml += '</ul>';
    return listHtml;
}
//#endregion

function showAppaltoLogs() {
    const id_giorno_appalto = $("#id_giorno_appalto").val();
    const CSRF_TOKEN = $("#token_csrf").val();
    const base_path = $("#url").val();

    Swal.fire({
        title: 'Caricamento Log Eventi...',
        html: '<i class="fas fa-spinner fa-spin"></i>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    fetch(base_path + "/get_appalto_logs/" + id_giorno_appalto, {
        method: 'post',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: "_token=" + CSRF_TOKEN
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        }
        throw new Error('Errore nel recupero dei log.');
    })
    .then(logs => {
        let logHtml = '<div style="max-height: 400px; overflow-y: auto; text-align: left;">';
        if (logs.length === 0) {
            logHtml += '<p>Nessun evento di salvataggio registrato per questo appalto.</p>';
        } else {
            appaltoLogs = logs; // Store logs globally
            for (let i = 0; i < logs.length; i++) {
                const log = logs[i];
                const currentPayload = log.raw_payload || {};
                const prevPayload = (i + 1 < logs.length) ? (logs[i + 1].raw_payload || {}) : {};

                let detailsHtml;
                if ((log.action.includes('Salvataggio') || log.action.includes('Ripristino')) && (i + 1 < logs.length)) {
                    detailsHtml = getDetailedDiffHtml(prevPayload, currentPayload);
                } else { // 'Creazione' or the very first log
                    detailsHtml = formatPayloadAsList(currentPayload);
                }

                let restoreButton = '';
                // Add restore button for past states that have a full payload
                if (i > 0 && (log.action.includes('Salvataggio completo') || log.action.includes('Ripristino'))) {
                    restoreButton = `<button class="btn btn-sm btn-outline-warning" onclick="confirmRestore(${i})">Ripristina a questa versione</button>`;
                }

                logHtml += `
                    <div style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px;">
                        <strong>Quando:</strong> ${log.timestamp}<br>
                        <strong>Chi:</strong> ${log.user}<br>
                        <strong>Azione:</strong> ${log.action}<br>
                        <strong>Dettagli:</strong> ${detailsHtml}
                        <div class="mt-2">${restoreButton}</div>
                    </div>
                `;
            }
        }
        logHtml += '</div>';

        Swal.fire({
            title: 'Log Eventi Appalto',
            html: logHtml,
            width: '90%',
            confirmButtonText: 'Chiudi'
        });
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Errore',
            text: error.message
        });
    });
}

function confirmRestore(logIndex) {
    const log = appaltoLogs[logIndex];
    if (!log || !log.raw_payload) {
        Swal.fire('Errore', 'Dati di log non trovati per il ripristino.', 'error');
        return;
    }

    Swal.fire({
        title: 'Sei sicuro?',
        html: `Stai per ripristinare lo stato dell'appalto alla versione del <strong>${log.timestamp}</strong>.<br>Tutte le modifiche successive andranno perse. Questa operazione è irreversibile.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sì, ripristina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            restoreState(log.raw_payload);
        }
    });
}

function restoreState(payload) {
    const id_giorno_appalto = $("#id_giorno_appalto").val();
    const CSRF_TOKEN = $("#token_csrf").val();
    const base_path = $("#url").val();

    Swal.fire({
        title: 'Ripristino in corso...',
        html: '<i class="fas fa-spinner fa-spin"></i>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    // Map payload keys to request keys
    const dataToSend = {
        _token: CSRF_TOKEN,
        id_giorno_appalto: id_giorno_appalto,
        is_restore: 1,
        from: 1, // To trigger the 'save_all' logic
        m_e: "",
        box: 1000,
        all_id_boxes: payload.all_workers_data || '',
        carm1: payload.vehicles_morning_1 || '',
        carm2: payload.vehicles_morning_2 || '',
        carp1: payload.vehicles_afternoon_1 || '',
        carp2: payload.vehicles_afternoon_2 || '',
        ditte: payload.companies_data || '',
        reper: payload.on_call_data || '',
        assenti: payload.absent_data || '',
        targhe_resp: payload.all_responsibles || '',
        // These are not in the payload but might be needed by the endpoint
        all_id_box: "",
        info: 0,
        servizi: "",
        car1: "",
        car2: ""
    };

    // Convert data to URL-encoded string
    const body = Object.keys(dataToSend).map(key => encodeURIComponent(key) + '=' + encodeURIComponent(dataToSend[key])).join('&');

    fetch(base_path + "/save_infoapp", {
        method: 'post',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: body
    })
    .then(response => response.json())
    .then(resp => {
        if (resp.header === "OK") {
            Swal.fire({
                icon: 'success',
                title: 'Ripristino completato!',
                text: 'Lo stato dell\'appalto è stato ripristinato. La pagina verrà ricaricata.',
                allowOutsideClick: false,
                showConfirmButton: true,
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Errore', 'Si è verificato un errore durante il ripristino.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Errore', 'Si è verificato un errore di comunicazione.', 'error');
    });
}

function check_persone() {
    const inconsistencies = getPersonInconsistencies();

    if (inconsistencies.length > 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sono state trovate delle incongruenze',
            html: inconsistencies.join('<br>')
        });
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Verifica completata',
            text: 'Nessuna incongruenza trovata.'
        });
    }
}