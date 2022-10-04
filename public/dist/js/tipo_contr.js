$(document).ready( function () {
    $('#tbl_list_contr').DataTable({
        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun Tipo contratto trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti dati',
            infoFiltered: '(Filtrati da _MAX_ tipi totali)',
        },
    });	
	
} );