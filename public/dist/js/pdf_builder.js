/**
 * Gathers all data for a specific shift (Mattina 'M' or Pomeriggio 'P').
 * @param {string} m_e - The shift identifier ('M' or 'P').
 * @returns {Array} An array of objects, each representing an appalto box.
 */
function getAppaltiData(m_e) {
    const appalti = [];
    // Trova tutti i box appalto direttamente dal DOM invece di usare maxBox
    $(`[id^=tdbox${m_e}]`).each(function() {
        const boxElement = $(this);
        const id = this.id; // es. "tdboxM0"
        const box = parseInt(id.replace(`tdbox${m_e}`, ''));

        if (boxElement.length > 0 && boxElement.is(':visible')) {
            const appaltoData = {
                box: box + 1,
                info: $(`#infoapp${m_e}${box}`).text().trim().replace(/\s+/g, ' '),
                ditta: {
                    nome: $(`#ditta${m_e}${box}`).text().trim(),
                    title: $(`#ditta${m_e}${box} span`).attr('title') || $(`#ditta${m_e}${box}`).text().trim()
                },
                mezzi: [],
                personale: []
            };

            // Mezzi
            for (let i = 1; i <= 2; i++) {
                const mezzoEl = $(`#car${i}${m_e}${box}`);
                if (mezzoEl.data('targa')) {
                    appaltoData.mezzi.push({
                        nome: mezzoEl.text().trim(),
                        targa: mezzoEl.data('targa')
                    });
                }
            }

            // Personale
            $(`.box${m_e}${box}`).each(function() {
                const idlav = $(this).data('idlav');
                if (idlav) {
                    const nome = $(this).clone().children().remove().end().text().trim();
                    const respTarga = $(this).find('span[id^="resp"]').text().trim();
                    appaltoData.personale.push({
                        nome: nome,
                        responsabile: respTarga ? `(Resp. ${respTarga})` : ''
                    });
                }
            });
            
            // Aggiungi solo se ha un contenuto (personale, mezzi o ditta)
            if (appaltoData.personale.length > 0 || appaltoData.mezzi.length > 0 || appaltoData.ditta.nome !== "") {
                appalti.push(appaltoData);
            }
        }
    });
    // Ordina per numero di box per garantire l'ordine corretto
    appalti.sort((a, b) => a.box - b.box);
    return appalti;
}

/**
 * Gathers data for the 'Reperibilità' section.
 * @returns {Object} An object containing arrays of workers for each slot.
 */
function getReperibilitaData() {
    const data = {
        "Mattino": [],
        "Pomeriggio": [],
        "Primo Notturno": [],
        "Secondo Notturno": []
    };
    const fasce = { 'Ma': 'Mattino', 'Mb': 'Pomeriggio', 'Pa': 'Primo Notturno', 'Pb': 'Secondo Notturno' };

    for (const code in fasce) {
        $(`.rep${code}`).each(function() {
            const nome = $(this).text().trim();
            if (nome !== '__________') {
                data[fasce[code]].push(nome);
            }
        });
    }
    return data;
}

/**
 * Gathers data for the 'Assenti' section.
 * @returns {Object} An object containing arrays of absent workers for each shift.
 */
function getAssentiData() {
    const data = {
        "Mattino": [],
        "Pomeriggio": []
    };
    const fasce = { 'Ma': 'Mattino', 'Mb': 'Pomeriggio' };

    for (const code in fasce) {
        $(`.ass${code}`).each(function() {
            const nome = $(this).text().trim();
            if (nome !== '__________') {
                data[fasce[code]].push(nome);
            }
        });
    }
    return data;
}

/**
 * Gathers data for the 'Urgenze' section.
 * @returns {Array} An array of objects, each representing an urgency.
 */
function getUrgenzeData() {
    const urgenze = [];
    $('#div_lista_urgenze .list-group-item-action').each(function() {
        urgenze.push({
            lavoratore: $(this).find('h5').text().trim(),
            servizio: $(this).find('small').first().text().trim(),
            ditta: $(this).find('p').text().trim(),
            descrizione: $(this).find('small').last().text().trim()
        });
    });
    return urgenze;
}


/**
 * Builds the HTML for a row of appalto boxes.
 * @param {Array} appaltiData - Data for the appalti.
 * @param {string} titolo - The title for the section (e.g., 'APPALTI DELLA MATTINA').
 * @returns {string} The generated HTML string.
 */
function buildAppaltiHtml(appaltiData, titolo) {
    if (appaltiData.length === 0) return '';

    let boxesHtml = '';
    appaltiData.forEach(appalto => {
        let mezziHtml = appalto.mezzi.map(m => `<div class="mezzo">${m.nome}</div>`).join('');
        let personaleHtml = appalto.personale.map(p => `<div class="personale-item">${p.nome} ${p.responsabile}</div>`).join('');

        boxesHtml += `
            <div class="pdf-box">
                <div class="pdf-box-header-info">${appalto.info}</div>
                <div class="pdf-box-header-ditta" title="${appalto.ditta.title}">${appalto.ditta.nome}</div>
                <div class="pdf-box-content">
                    <div class="mezzi-container">${mezziHtml}</div>
                    <div class="personale-container">${personaleHtml}</div>
                </div>
            </div>
        `;
    });

    return `
        <div class="pdf-section">
            <div class="pdf-section-title">${titolo}</div>
            <div class="pdf-row">${boxesHtml}</div>
        </div>
    `;
}

/**
 * Builds the HTML for the Reperibilità and Assenti sections.
 * @param {Object} reperData - Data for Reperibilità.
 * @param {Object} assentiData - Data for Assenti.
 * @returns {string} The generated HTML string.
 */
function buildSideInfoHtml(reperData, assentiData) {
    let reperHtml = '';
    let hasReper = false;
    for (const fascia in reperData) {
        if (reperData[fascia].length > 0) {
            hasReper = true;
            reperHtml += `
                <div class="side-info-group">
                    <strong>${fascia}:</strong>
                    <span>${reperData[fascia].join(', ')}</span>
                </div>
            `;
        }
    }

    let assentiHtml = '';
    let hasAssenti = false;
    for (const fascia in assentiData) {
        if (assentiData[fascia].length > 0) {
            hasAssenti = true;
            assentiHtml += `
                <div class="side-info-group">
                    <strong>${fascia}:</strong>
                    <span>${assentiData[fascia].join(', ')}</span>
                </div>
            `;
        }
    }

    return `
        <div class="pdf-section pdf-row-side-info">
            ${hasReper ? `<div class="side-info-box"><div class="pdf-section-title-small">REPERIBILITÀ</div>${reperHtml}</div>` : ''}
            ${hasAssenti ? `<div class="side-info-box"><div class="pdf-section-title-small">ASSENTI</div>${assentiHtml}</div>` : ''}
        </div>
    `;
}

/**
 * Builds the HTML for the Urgenze section.
 * @param {Array} urgenzeData - Data for Urgenze.
 * @returns {string} The generated HTML string.
 */
function buildUrgenzeHtml(urgenzeData) {
    if (urgenzeData.length === 0) return '';

    let urgenzeBoxesHtml = urgenzeData.map(urg => `
        <div class="pdf-urgenza-box">
            <div class="urgenza-header">
                <strong>${urg.lavoratore}</strong>
                <small>${urg.servizio}</small>
            </div>
            <p>${urg.ditta}</p>
            <small>${urg.descrizione}</small>
        </div>
    `).join('');

    return `
        <div class="pdf-section">
            <div class="pdf-section-title">URGENZE</div>
            <div class="pdf-row">${urgenzeBoxesHtml}</div>
        </div>
    `;
}


/**
 * The core logic for PDF generation, to be called after checks.
 */
function proceedWithPdfGeneration() {
    $("#btn_print").prop('disabled', true).removeClass('btn-outline-success').addClass('btn-success').html('<i class="fas fa-spinner fa-spin"></i> Preparazione PDF...');

    setTimeout(function() {
        // 1. Gather data
        const appaltiMattina = getAppaltiData('M');
        const appaltiPomeriggio = getAppaltiData('P');
        const reperibilita = getReperibilitaData();
        const assenti = getAssentiData();
        const urgenze = getUrgenzeData();
        const dataAppalto = $("#dap1").val();

        // 2. Build HTML string
        const contentHtml = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Appalti del ${dataAppalto}</title>
                <style>
                    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 10px; }
                    .pdf-page { padding: 10px; }
                    .pdf-header { text-align: center; font-size: 16px; font-weight: bold; margin-bottom: 15px; }
                    .pdf-section { margin-bottom: 15px; page-break-inside: avoid; }
                    .pdf-section-title { background-color: #1e8bff; color: #ffff1e; text-align: center; padding: 5px; font-weight: bold; font-size: 12px; margin-bottom: 5px; }
                    .pdf-section-title-small { font-weight: bold; margin-bottom: 5px; border-bottom: 1px solid #ccc; padding-bottom: 2px; }
                    .pdf-row { display: flex; flex-wrap: wrap; gap: 10px; }
                    .pdf-box { border: 1px solid #ccc; border-radius: 4px; padding: 5px; width: 200px; flex-shrink: 0; background-color: #f8f9fa; }
                    .pdf-box-header-info { font-size: 11px; font-weight: bold; text-align: center; padding: 3px; background-color: #28a745; color: white; border-radius: 3px; margin-bottom: 5px; }
                    .pdf-box-header-ditta { font-weight: bold; text-align: center; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
                    .mezzi-container { display: flex; justify-content: space-around; margin-bottom: 5px; }
                    .mezzo { background-color: #ffc107; padding: 2px 5px; border-radius: 3px; font-size: 9px; }
                    .personale-container { border-top: 1px solid #ddd; padding-top: 5px; }
                    .personale-item { line-height: 1.4; }
                    .pdf-row-side-info { gap: 20px; }
                    .side-info-box { border: 1px solid #ddd; padding: 10px; flex-grow: 1; background-color: #f8f9fa; }
                    .side-info-group { margin-bottom: 5px; }
                    .side-info-group strong { display: block; }
                    .pdf-urgenza-box { border: 1px solid #e0a800; background-color: #fff3cd; padding: 5px; border-radius: 4px; width: 220px; flex-shrink: 0; }
                    .urgenza-header { display: flex; justify-content: space-between; margin-bottom: 5px; }
                </style>
            </head>
            <body>
                <div class="pdf-page">
                    <div class="pdf-header">Appalti del ${dataAppalto}</div>
                    ${buildAppaltiHtml(appaltiMattina, 'APPALTI DELLA MATTINA')}
                    ${buildAppaltiHtml(appaltiPomeriggio, 'APPALTI DEL POMERIGGIO')}
                    ${buildSideInfoHtml(reperibilita, assenti)}
                    ${buildUrgenzeHtml(urgenze)}
                </div>
            </body>
            </html>
        `;

        // 3. Generate PDF
        const options = {
            margin: 5,
            filename: `appalti_${dataAppalto.replace(/-/g, '')}.pdf`,
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a3', orientation: 'landscape' }
        };

        html2pdf().from(contentHtml).set(options).save().then(function() {
            $("#btn_print").prop('disabled', false).removeClass('btn-success').addClass('btn-outline-success').html('<i class="fas fa-print"></i> Stampa videata');
        });
    }, 100); // Small delay to allow UI to update
}

/**
 * Main function to generate the PDF from scratch using data.
 */
function generatePdfFromData() {
    const hasUnsavedChanges = $("#btn_save_all").hasClass('btn-warning');

    if (hasUnsavedChanges) {
        Swal.fire({
            title: 'Salvataggio Richiesto',
            text: "Ci sono modifiche non salvate. Per favore, salva prima di procedere con la stampa.",
            icon: 'error',
            confirmButtonText: 'OK'
        });
    } else {
        proceedWithPdfGeneration();
    }
}