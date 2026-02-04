$(document).ready(function () {
    $('#tbl_list_appalti').DataTable({
        "pageLength": 10,
        "lengthChange": true,
        dom: 'Bfrtip',
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

    // Select all checkbox
    $('#select_all').on('click', function () {
        $('.appalto-checkbox').prop('checked', this.checked);
    });

    // Individual checkbox
    $('#tbl_list_appalti tbody').on('click', '.appalto-checkbox', function () {
        if (!this.checked) {
            $('#select_all').prop('checked', false);
        }
    });

    // Generate invoices button
    $('#genera_fatture').on('click', function () {
        const selectedIds = [];
        $('.appalto-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

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
    $('#esporta_fatture').on('click', function () {
        const selectedIds = [];
        $('.appalto-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

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
                        const cell = $(`#fatture-cell-${id_giorno_appalto}`);
                        cell.empty(); // Clear previous content

                        if (invoices.length === 1) {
                            // Single invoice
                            const invoice = invoices[0];
                            const pdfUrl = `${baseUrl}/invito/${invoice.id_fattura}?genera_pdf=genera&preview_pdf=preview`;
                            const buttonHtml = `
                                <a href="${pdfUrl}" target="_blank" class="btn btn-danger btn-sm" title="Visualizza Fattura ${invoice.ditta_name}">
                                    <i class="fa fa-file-pdf"></i>
                                </a>`;
                            cell.html(buttonHtml);
                        } else if (invoices.length > 1) {
                            // Multiple invoices, use a dropdown
                            let dropdownItems = '';
                            invoices.forEach(invoice => {
                                const pdfUrl = `${baseUrl}/invito/${invoice.id_fattura}?genera_pdf=genera&preview_pdf=preview`;
                                dropdownItems += `<li><a class="dropdown-item" href="${pdfUrl}" target="_blank">${invoice.ditta_name}</a></li>`;
                            });

                            const dropdownHtml = `
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-file-pdf"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        ${dropdownItems}
                                    </ul>
                                </div>`;
                            cell.html(dropdownHtml);
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
});

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
                let fileListHtml = '';
                const baseUrl = $('#url').val();
                fileListHtml = '<p class="text-left mt-3">File generati:</p><ul class="list-group text-left">';
                data.files.forEach(file => {
                    const fileUrl = `${baseUrl}/storage/csv_exports/${file}`;
                    fileListHtml += `<li class="list-group-item"><a href="${fileUrl}" download>${file}</a></li>`;
                });
                fileListHtml += '</ul>';

                Swal.fire({
                    title: data.status === 'ok' ? 'Esportazione completata!' : 'Errore Esportazione',
                    html: `<div>${data.message}</div>${fileListHtml}`,
                    icon: data.status === 'ok' ? 'success' : 'warning', // 'warning' se ci sono file ma lo stato è 'error'
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'OK',
                    denyButtonText: `Svuota Lista CSV`,
                    denyButtonColor: '#d33',
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
        title: 'Data del nuovo gruppo di appalti',
        html: `<input type="date" id="data_appalto" class="swal2-input" value="${$('#data_app').val()}">`,
        confirmButtonText: 'Crea',
        focusConfirm: false,
        preConfirm: () => {
            const data_appalto = Swal.getPopup().querySelector('#data_appalto').value
            if (!data_appalto) {
                Swal.showValidationMessage(`Per favore inserisci una data`)
            }
            return { data_appalto: data_appalto }
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
            $('#frm_appalti').submit();
        }
    })
}