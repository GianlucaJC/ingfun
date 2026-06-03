// Set globali per memorizzare lo stato della selezione e delle fatture generate
var selectedAppaltiIds = new Set();
var generatedInvoicesHtml = new Map();

$(document).ready(function () {
    var table = $('#tbl_list_appalti').DataTable({ // Assegna l'istanza di DataTable a una variabile
        "pageLength": 10,
        "order": [[3, "desc"]], // Ordina per la colonna "Data" (indice 3) in ordine decrescente
        "lengthChange": true,
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        dom: 'lBfrtip',
        buttons: [
            'excel', 'pdf'
        ],
        language: {
            lengthMenu: 'Visualizza _MENU_ records per pagina',
            zeroRecords: 'Nessun appalto trovato',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti appalti',
            infoFiltered: '(Filtrati da _MAX_ appalti totali)',
        },
    });

    // Funzione per aggiornare lo stato del checkbox globale 'select all'
    function updateSelectAllCheckboxState() {
        const allFilteredRows = table.rows({ search: 'applied' });
        const totalFilteredRows = allFilteredRows.count();

        if (totalFilteredRows === 0) {
            $('#select_all').prop('checked', false).prop('indeterminate', false);
            return;
        }

        let selectedInFilterCount = 0;
        allFilteredRows.data().each(function(rowData) {
        // Estrai l'ID dalla terza colonna (indice 2)
        const id = rowData[2];
        if (id && selectedAppaltiIds.has(id.toString())) {
                selectedInFilterCount++;
            }
        });

        if (selectedInFilterCount === totalFilteredRows) { // Tutti i checkbox visibili sono selezionati
            $('#select_all').prop('checked', true).prop('indeterminate', false);
        } else if (selectedInFilterCount > 0) { // Alcuni checkbox visibili sono selezionati, ma non tutti
            $('#select_all').prop('checked', false).prop('indeterminate', true);
        } else { // Nessun checkbox visibile è selezionato
            $('#select_all').prop('checked', false).prop('indeterminate', false);
        }
    }

    // Select all checkbox
    $('#select_all').on('click', function (e) {
        e.stopPropagation(); // Impedisce la propagazione dell'evento per evitare il trigger di ordinamento
        const isChecked = this.checked;

        // Itera su tutti i dati delle righe filtrate (non solo sulla pagina corrente)
        table.rows({ search: 'applied' }).data().each(function (rowData) {
        // Estrai l'ID dalla terza colonna (indice 2)
        const id = rowData[2];

            if (id) {
                if (isChecked) {
                selectedAppaltiIds.add(id.toString());
                } else {
                selectedAppaltiIds.delete(id.toString());
                }
            }
        });
        table.draw(false); // Ridisegna la tabella per aggiornare i checkbox visibili
    });

    // Individual checkbox
    $('#tbl_list_appalti tbody').on('click', '.appalto-checkbox', function () {
        const id = $(this).val();
        if ($(this).is(':checked')) {
            selectedAppaltiIds.add(id.toString());
        } else {
            selectedAppaltiIds.delete(id.toString());
        }
        updateSelectAllCheckboxState();
    });

    // Al cambio pagina o ad ogni ridisegno della tabella, aggiorna lo stato dei checkbox
    table.on('draw.dt', function () {
        // Aggiorna i checkbox individuali e le icone delle fatture sulla nuova pagina
        table.rows({ page: 'current' }).nodes().to$().find('.appalto-checkbox').each(function() {
            const id = $(this).val();
            // Ripristina lo stato del checkbox
            $(this).prop('checked', selectedAppaltiIds.has(id.toString()));

            // Ripristina l'icona PDF se è stata generata in questa sessione
            if (generatedInvoicesHtml.has(id.toString())) {
                $(this).closest('tr').find(`#fatture-cell-${id}`).html(generatedInvoicesHtml.get(id.toString()));
            }
        });
        updateSelectAllCheckboxState();
    });

    // Generate invoices button
    $('#genera_fatture').on('click', function () {
        const selectedIds = Array.from(selectedAppaltiIds); // Usa il set globale

        if (selectedIds.length === 0) {
            Swal.fire('Attenzione', 'Selezionare almeno un giorno di appalto per generare le fatture.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Conferma generazione',
            text: `Verranno generate fatture per i ${selectedIds.length} giorni di appalto selezionati. Procedere?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sì, genera!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                generazioneFatture(selectedIds);
            }
        });
    });

    // Export invoices button
    $('#esporta_fatture').on('click', function () { // Usa il set globale
        const selectedIds = Array.from(selectedAppaltiIds);

        if (selectedIds.length === 0) {
            Swal.fire('Attenzione', 'Selezionare almeno un giorno di appalto per esportare le fatture.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Conferma Esportazione',
            text: `Verranno generati i file CSV per i ${selectedIds.length} giorni di appalto selezionati e saranno caricati via FTP. Procedere?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sì, esporta!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                esportazioneFatture(selectedIds);
            }
        });
    });

    // Info button for invoicing
    $('#info_fatturazione').on('click', function() {
        Swal.fire({
            title: 'Come funziona la generazione fatture',
            icon: 'info',
            html:
                '<div style="text-align: left; padding: 1em;">' +
                "Per poter generare correttamente le righe in fattura per un appalto, devono essere soddisfatte <b>entrambe</b> le seguenti condizioni:" +
                '<ul class="mt-3 list-group">' +
                '<li class="list-group-item"><b>1. Servizi Definiti:</b> All\'appalto (il "box") deve essere associato almeno un servizio nella sezione <i>"Servizi Svolti"</i>.</li>' +
                '<li class="list-group-item"><b>2. Prezzo nel Listino:</b> Per ogni servizio svolto, deve esistere un prezzo specifico per la ditta nel <i>"Listino Clienti"</i>. Se un servizio non ha un prezzo definito per quella ditta, non potrà essere incluso in fattura.</li>' +
                '</ul>' +
                '<div class="alert alert-warning mt-3" role="alert">In assenza di queste condizioni, potrebbero essere generate fatture vuote (senza righe).</div>' +
                '</div>',
            confirmButtonText: 'Ho capito!'
        });
    });

    // Export previous month invoices button
    $('#esporta_fatture_mese_precedente').on('click', function () {
        const url = $('#url').val() + '/get_appalti_previous_month';
        const token = $('#token_csrf').val();

        Swal.fire({
            title: 'Ricerca appalti del mese precedente...',
            text: 'Attendere mentre cerchiamo gli appalti fatturabili.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: token
            },
            success: function (response) {
                Swal.close();
                if (response.status === 'ok') {
                    const ids = response.ids;
                    const dateRange = response.date_range;
                    const totalCount = response.total_count;
                    const details_boxes = response.details_boxes;
                    const details_urgenze = response.details_urgenze;

                    if (totalCount > 0) {
                        // Costruisci il dettaglio in HTML
                        let detailHtml = `
                            <div style="text-align: left; margin-top: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
                                <strong>Dettaglio (clicca sui numeri per vedere gli ID):</strong>
                                <ul class="list-unstyled mt-2" style="padding-left: 0;">
                                    <li style="display: flex; justify-content: space-between; padding: 5px 0;">
                                        Appalti Standard: 
                                        <a href="#" id="show-details-boxes" class="badge bg-primary" style="text-decoration: none;">${response.count_boxes}</a>
                                    </li>
                                    <li style="display: flex; justify-content: space-between; padding: 5px 0;">
                                        Urgenze: 
                                        <a href="#" id="show-details-urgenze" class="badge bg-warning text-dark" style="text-decoration: none;">${response.count_urgenze}</a>
                                    </li>
                                </ul>
                            </div>
                        `;

                        Swal.fire({
                            title: 'Esportazione Mese Precedente',
                            html: `Sono stati trovati <strong>${totalCount}</strong> giorni di appalto fatturabili per il periodo <strong>${dateRange}</strong>.<br>
                                   ${detailHtml}<br>
                                   Vuoi procedere con l'esportazione?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sì, esporta!',
                            cancelButtonText: 'Annulla',
                            didOpen: () => {
                                $('#show-details-boxes').on('click', function(e) {
                                    e.preventDefault();
                                    showDetailsModal('Dettaglio Appalti Standard', details_boxes, 'boxes');
                                });
                                $('#show-details-urgenze').on('click', function(e) {
                                    e.preventDefault();
                                    showDetailsModal('Dettaglio Urgenze', details_urgenze, 'urgenze');
                                });
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                esportazioneFatture(ids);
                            }
                        });
                    } else {
                        Swal.fire('Informazione', `Nessun giorno di appalto fatturabile trovato per il periodo <strong>${dateRange}</strong>.`, 'info');
                    }
                } else {
                    Swal.fire('Errore', response.message || 'Si è verificato un errore durante la ricerca degli appalti.', 'error');
                }
            },
            error: function (xhr, status, error) {
                Swal.close();
                Swal.fire('Errore', 'Si è verificato un errore di comunicazione con il server.', 'error');
                console.error(error);
            }
        });
    });    

    // New button handler for generating quotes
    $('#genera_preventivi').on('click', function () {
        const selectedIds = Array.from(selectedAppaltiIds);

        if (selectedIds.length === 0) {
            Swal.fire('Attenzione', 'Selezionare almeno un giorno di appalto per generare i preventivi.', 'warning');
            return;
        }

        // AJAX call to get appalti details
        const url = $('#url').val() + '/get_dettagli_appalti_per_preventivo';
        const token = $('#token_csrf').val();

        Swal.fire({
            title: 'Caricamento...',
            text: 'Recupero dei dettagli degli appalti selezionati.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: token,
                ids: selectedIds
            },
            success: function (response) {
                if (response.status === 'ok' && response.appalti.length > 0) {
                    let modalContent = `
                        <div class="mb-2 text-start">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" id="select-all-preventivi"> Seleziona/Deseleziona Tutti
                            </label>
                        </div>
                        <div id="preventivi-appalti-list" style="max-height: 45vh; overflow-y: auto; border: 1px solid #ddd; padding: 5px; text-align:left;">
                            <table class="table table-sm table-hover">
                                <thead style="position: sticky; top: 0; background-color: white; z-index: 1;">
                                    <tr>
                                        <th style="width: 5%;">Sel.</th>
                                        <th style="width: 15%;">ID Giorno</th>
                                        <th style="width: 15%;">Data</th>
                                        <th style="width: 35%;">Ditta</th>
                                        <th style="width: 30%;">Dettaglio</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    response.appalti.forEach(appalto => {
                        modalContent += `
                            <tr>
                                <td class="text-center"><input class="preventivo-checkbox" type="checkbox" value="${appalto.id_appalto_info}" style="float: none; margin-left: 0;"></td>
                                <td>${appalto.id_appalto}</td>
                                <td>${new Date(appalto.data_servizio).toLocaleDateString('it-IT')}</td>
                                <td>${appalto.ditta_name}</td>
                                <td>Turno: ${appalto.m_e === 'M' ? 'Mattina' : 'Pomeriggio'}, Box: ${appalto.box_number + 1}</td>
                            </tr>
                        `;
                    });
                    modalContent += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    Swal.fire({
                        title: 'Seleziona Appalti per il Preventivo',
                        html: modalContent,
                        width: '800px',
                        showCancelButton: true,
                        confirmButtonText: 'Genera Preventivi Selezionati',
                        cancelButtonText: 'Annulla',
                        didOpen: () => {
                            // Logic for select/deselect all
                            $('#select-all-preventivi').on('click', function() {
                                const isChecked = $(this).is(':checked');
                                $('.preventivo-checkbox').prop('checked', isChecked);
                            });
                            // If an individual checkbox is unchecked, uncheck the "select all"
                            $('.preventivo-checkbox').on('click', function() {
                                if (!$(this).is(':checked')) {
                                    $('#select-all-preventivi').prop('checked', false);
                                } else {
                                    // Check if all are checked now
                                    if ($('.preventivo-checkbox:checked').length === $('.preventivo-checkbox').length) {
                                        $('#select-all-preventivi').prop('checked', true);
                                    }
                                }
                            });
                        },
                        preConfirm: () => {
                            const selectedAppaltiInfoIds = [];
                            $('#preventivi-appalti-list input.preventivo-checkbox:checked').each(function() {
                                selectedAppaltiInfoIds.push($(this).val());
                            });
                            if (selectedAppaltiInfoIds.length === 0) {
                                Swal.showValidationMessage('Devi selezionare almeno un appalto.');
                            }
                            return selectedAppaltiInfoIds;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            generazionePreventivi(result.value);
                        }
                    });

                } else {
                    Swal.fire('Nessun Appalto Valido', response.message || 'Nessun appalto valido per la generazione di preventivi trovato tra quelli selezionati.', 'info');
                }
            },
            error: function (xhr, status, error) {
                Swal.fire('Errore', 'Si è verificato un errore di comunicazione con il server.', 'error');
                console.error(error);
            }
        });
    });
});

function showDetailsModal(title, details, type) {
    let tableHtml = '<div style="max-height: 400px; overflow-y: auto; text-align: left;">';
    tableHtml += '<table class="table table-bordered table-striped">';
    
    if (type === 'boxes') {
        tableHtml += `
            <thead>
                <tr>
                    <th>ID Appalto</th>
                    <th>Data</th>
                    <th>Turno</th>
                    <th>Box</th>
                </tr>
            </thead>
            <tbody>
        `;
        details.forEach(item => {
            tableHtml += `
                <tr>
                    <td>${item.id_appalto}</td>
                    <td>${new Date(item.data_appalto).toLocaleDateString('it-IT')}</td>
                    <td>${item.m_e === 'M' ? 'Mattina' : 'Pomeriggio'}</td>
                    <td>${item.id_box + 1}</td>
                </tr>
            `;
        });
    } else if (type === 'urgenze') {
        tableHtml += `
            <thead>
                <tr>
                    <th>ID Appalto</th>
                    <th>Data</th>
                    <th>ID Urgenza</th>
                </tr>
            </thead>
            <tbody>
        `;
        details.forEach(item => {
            tableHtml += `
                <tr>
                    <td>${item.id_appalto}</td>
                    <td>${new Date(item.data_appalto).toLocaleDateString('it-IT')}</td>
                    <td>${item.id_urgenza}</td>
                </tr>
            `;
        });
    }

    tableHtml += '</tbody></table></div>';

    Swal.fire({
        title: title,
        html: tableHtml,
        width: '800px',
        confirmButtonText: 'Chiudi'
    });
}

function generazioneFatture(ids) {
    const url = $('#url').val() + '/genera_fatture_da_appalti';
    const token = $('#token_csrf').val();

    // Show a loading indicator
    Swal.fire({
        title: 'Generazione in corso...',
        text: 'Attendere la generazione delle fatture.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            ids: ids
        },
        success: function (response) {
            if (response.status === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: response.message
                });

                const fatture = response.fatture;
                const baseUrl = $('#url').val();

                for (const id_giorno_appalto in fatture) {
                    const invoices = fatture[id_giorno_appalto];
                    let finalHtml = '';

                    if (invoices.length === 1) {
                        // Single invoice
                        const invoice = invoices[0];
                        const pdfUrl = `${baseUrl}/invito/${invoice.id_fattura}?genera_pdf=genera&preview_pdf=preview`;
                        finalHtml = `
                            <a href="${pdfUrl}" target="_blank" class="btn btn-danger btn-sm" title="Visualizza Fattura ${invoice.ditta_name}">
                                <i class="fa fa-file-pdf"></i>
                            </a>`;
                    } else if (invoices.length > 1) {
                        // Multiple invoices, use a dropdown
                        let dropdownItems = '';
                        invoices.forEach(invoice => {
                            const pdfUrl = `${baseUrl}/invito/${invoice.id_fattura}?genera_pdf=genera&preview_pdf=preview`;
                            dropdownItems += `<li><a class="dropdown-item" href="${pdfUrl}" target="_blank">${invoice.ditta_name}</a></li>`;
                        });

                        finalHtml = `
                            <div class="btn-group">
                                <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-file-pdf"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    ${dropdownItems}
                                </ul>
                            </div>`;
                    }

                    // Memorizza l'HTML generato per la persistenza nella sessione della pagina
                    if (invoices.length > 0) {
                        generatedInvoicesHtml.set(id_giorno_appalto.toString(), finalHtml);
                    }

                    // Aggiorna la cella solo se è attualmente visibile nel DOM
                    const cell = $(`#fatture-cell-${id_giorno_appalto}`);
                    if (cell.length) {
                        cell.html(finalHtml);
                    }
                }
            } else {
                Swal.fire('Errore', response.message || 'Si è verificato un errore durante la generazione delle fatture.', 'error');
            }
        },
        error: function (xhr, status, error) {
            Swal.fire('Errore', 'Si è verificato un errore di comunicazione con il server.', 'error');
            console.error(error);
        }
    });
}

function generaFatturaSingola(id) {
    Swal.fire({
        title: 'Conferma generazione',
        text: `Verrà generata la/e fattura/e per il giorno di appalto selezionato. Procedere?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, genera!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            generazioneFatture([id]);
        }
    });
}

function generazionePreventivi(ids) {
    if (ids.length === 0) {
        Swal.fire('Attenzione', 'Nessun appalto selezionato.', 'warning');
        return;
    }

    const url = $('#url').val() + '/genera_preventivo_pdf';
    const token = $('#token_csrf').val();

    Swal.fire({
        title: 'Generazione Preventivi...',
        text: 'Attendere la creazione dei file.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            ids: ids
        },
        success: function (response) {
            if (response.status === 'ok') {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    html: response.message
                });

                // Inject PDFs into the table
                if (response.quotes) {
                    // This map will store HTML for each day's cell
                    const dayCells = new Map();

                    response.quotes.forEach(quote => {
                        const pdfUrl = $('#url').val() + '/' + quote.pdf_url;
                        const html = `
                            <a href="${pdfUrl}" target="_blank" class="btn btn-info btn-sm" title="Visualizza Preventivo ${quote.ditta_name}">
                                <i class="fa fa-file-signature"></i>
                            </a>`;
                        
                        quote.associated_days.forEach(dayId => {
                            if (!dayCells.has(dayId)) {
                                dayCells.set(dayId, []);
                            }
                            dayCells.get(dayId).push({
                                html: html,
                                ditta_name: quote.ditta_name,
                                pdf_url: pdfUrl
                            });
                        });
                    });

                    // Now update the DOM
                    dayCells.forEach((quotesForDay, dayId) => {
                        let finalHtml = '';
                        if (quotesForDay.length === 1) {
                            finalHtml = quotesForDay[0].html;
                        } else if (quotesForDay.length > 1) {
                            let dropdownItems = '';
                            quotesForDay.forEach(q => {
                                dropdownItems += `<li><a class="dropdown-item" href="${q.pdf_url}" target="_blank">${q.ditta_name}</a></li>`;
                            });
                            finalHtml = `
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-file-signature"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        ${dropdownItems}
                                    </ul>
                                </div>`;
                        }
                        
                        const cell = $(`#preventivi-cell-${dayId}`);
                        if (cell.length) {
                            cell.html(finalHtml);
                        }
                    });
                }

            } else {
                Swal.fire('Errore', response.message || 'Si è verificato un errore durante la generazione dei preventivi.', 'error');
            }
        },
        error: function (xhr, status, error) {
            Swal.fire('Errore', 'Si è verificato un errore di comunicazione con il server.', 'error');
            console.error(error);
        }
    });
}

function esportazioneFatture(ids) {
    const url = $('#url').val() + '/esporta_fatture_csv';
    const token = $('#token_csrf').val();

    // Show a loading indicator
    Swal.fire({
        title: 'Generazione ed Esportazione in corso...',
        text: 'Attendere la creazione dei file CSV e l\'upload via FTP.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: token,
            ids: ids
        },
        success: function(data) {
            // Se ci sono file (anche in caso di errore parziale come fallimento FTP),
            // mostriamo la lista e il pulsante per svuotarla.
            if (data.files && data.files.length > 0) {
                Swal.fire({
                    title: data.status === 'ok' ? 'Esportazione completata!' : 'Errore Esportazione',
                    html: `<div>${data.message}</div>`,
                    icon: data.status === 'ok' ? 'success' : 'warning', // 'warning' se ci sono file ma lo stato è 'error'
                    showCancelButton: false,
                }).then((result) => {
                    if (!result.isDenied) return;

                    const svuotaUrl = $('#url').val() + '/svuota_lista_csv';
                    const token = $('#token_csrf').val();

                    $.post(svuotaUrl, { _token: token })
                        .done(function(svuotaData) {
                            Swal.fire(svuotaData.status === 'ok' ? 'Fatto!' : 'Errore', svuotaData.message, svuotaData.status === 'ok' ? 'success' : 'error');
                        })
                        .fail(function() {
                            Swal.fire('Errore', 'Si è verificato un errore di rete.', 'error');
                        });
                });
            } else {
                // Nessun file generato, mostra un semplice messaggio di stato.
                Swal.fire(
                    data.status === 'ok' ? 'Informazione' : 'Errore',
                    data.message,
                    data.status === 'ok' ? 'info' : 'error'
                );
            }
        },
        error: function (xhr, status, error) {
            Swal.fire('Errore', 'Si è verificato un errore di comunicazione con il server.', 'error');
            console.error(error);
        }
    });
}

function dele_element(id) {
    if (confirm("Sicuri di cancellare l'elemento?")) {
        $("#dele_cand").val(id);
        $("#frm_appalti").submit();
    }
}

function restore_element(id) {
    if (confirm("Sicuri di ripristinare l'elemento?")) {
        $("#restore_cand").val(id);
        $("#frm_appalti").submit();
    }
}

function new_app() {
    Swal.fire({
        title: 'Nuovo gruppo di appalti',
        html:
            `<h6>Data Appalto</h6><input type="date" id="data_appalto" class="swal2-input" value="${$('#data_app').val()}">
            <hr>
            <h6>Impostazioni Layout (opzionale)</h6>
            <div style="display: flex; justify-content: space-around; text-align: left; gap: 15px;">
                <div><label for="num_box" class="swal2-label" style="font-size:1em">Box per turno:</label><input type="number" id="num_box" class="swal2-input" value="20" style="width: 80px;"></div>
                <div><label for="elem_box" class="swal2-label" style="font-size:1em">Lavoratori per box:</label><input type="number" id="elem_box" class="swal2-input" value="6" style="width: 80px;"></div>
            </div>
            <div style="display: flex; justify-content: space-around; text-align: left; margin-top: 1em; gap: 15px;">
                <div><label for="elem_rep" class="swal2-label" style="font-size:1em">Slot reperibilità:</label><input type="number" id="elem_rep" class="swal2-input" value="15" style="width: 80px;"></div>
                <div><label for="elem_ass" class="swal2-label" style="font-size:1em">Slot assenti:</label><input type="number" id="elem_ass" class="swal2-input" value="15" style="width: 80px;"></div>
            </div>`,
        confirmButtonText: 'Crea',
        focusConfirm: false,
        preConfirm: () => {
            const data_appalto = Swal.getPopup().querySelector('#data_appalto').value;
            if (!data_appalto) {
                Swal.showValidationMessage(`Per favore inserisci una data`);
                return false;
            }
            return {
                data_appalto: data_appalto,
                num_box: Swal.getPopup().querySelector('#num_box').value,
                elem_box: Swal.getPopup().querySelector('#elem_box').value,
                elem_rep: Swal.getPopup().querySelector('#elem_rep').value,
                elem_ass: Swal.getPopup().querySelector('#elem_ass').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $('<input>').attr({
                type: 'hidden',
                name: 'newapp',
                value: '1'
            }).appendTo('#frm_appalti');
            $('<input>').attr({
                type: 'hidden',
                name: 'data_appalto',
                value: result.value.data_appalto
            }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'num_box', value: result.value.num_box }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_box', value: result.value.elem_box }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_rep', value: result.value.elem_rep }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_ass', value: result.value.elem_ass }).appendTo('#frm_appalti');
            $('#frm_appalti').submit();
        }
    })
}

function edit_layout(element) {
    const id = $(element).data('id');
    const num_box = $(element).data('num_box') || 20;
    const elem_box = $(element).data('elem_box') || 6;
    const elem_rep = $(element).data('elem_rep') || 15;
    const elem_ass = $(element).data('elem_ass') || 15;

    Swal.fire({
        title: `Modifica Layout Appalto #${id}`,
        html:
            `<h6>Impostazioni Layout</h6>
            <div style="display: flex; justify-content: space-around; text-align: left; gap: 15px;">
                <div><label for="num_box" class="swal2-label" style="font-size:1em">Box per turno:</label><input type="number" id="num_box" class="swal2-input" value="${num_box}" style="width: 80px;"></div>
                <div><label for="elem_box" class="swal2-label" style="font-size:1em">Lavoratori per box:</label><input type="number" id="elem_box" class="swal2-input" value="${elem_box}" style="width: 80px;"></div>
            </div>
            <div style="display: flex; justify-content: space-around; text-align: left; margin-top: 1em; gap: 15px;">
                <div><label for="elem_rep" class="swal2-label" style="font-size:1em">Slot reperibilità:</label><input type="number" id="elem_rep" class="swal2-input" value="${elem_rep}" style="width: 80px;"></div>
                <div><label for="elem_ass" class="swal2-label" style="font-size:1em">Slot assenti:</label><input type="number" id="elem_ass" class="swal2-input" value="${elem_ass}" style="width: 80px;"></div>
            </div>`,
        confirmButtonText: 'Salva Modifiche',
        focusConfirm: false,
        preConfirm: () => {
            return {
                id_appalto: id,
                num_box: Swal.getPopup().querySelector('#num_box').value,
                elem_box: Swal.getPopup().querySelector('#elem_box').value,
                elem_rep: Swal.getPopup().querySelector('#elem_rep').value,
                elem_ass: Swal.getPopup().querySelector('#elem_ass').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $('<input>').attr({
                type: 'hidden',
                name: 'edit_layout',
                value: '1'
            }).appendTo('#frm_appalti');
            $('<input>').attr({
                type: 'hidden',
                name: 'id_appalto_edit',
                value: result.value.id_appalto
            }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'num_box', value: result.value.num_box }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_box', value: result.value.elem_box }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_rep', value: result.value.elem_rep }).appendTo('#frm_appalti');
            $('<input>').attr({ type: 'hidden', name: 'elem_ass', value: result.value.elem_ass }).appendTo('#frm_appalti');
            $('#frm_appalti').submit();
        }
    });
}