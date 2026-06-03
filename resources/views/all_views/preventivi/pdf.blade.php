<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Preventivo</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; }
        .page-break { page-break-after: always; }
        .header, .footer { width: 100%; text-align: center; position: fixed; }
        .header { top: 0px; }
        .footer { bottom: 0px; font-size: 10px; }
        .content { margin-top: 150px; margin-bottom: 50px; }
        .logo { width: 200px; }
        .company-details { text-align: right; font-size: 10px; }
        .client-details { margin-top: 20px; padding: 10px; border: 1px solid #ccc; }
        .items-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .totals { float: right; width: 300px; margin-top: 20px; }
        .totals table { width: 100%; }
        .totals td { padding: 5px; }
        .reference { margin-top: 40px; font-size: 11px; }
    </style>
</head>
<body>
    @foreach($preventivi as $index => $preventivo)
        <div class="header">
            <img src="{{ public_path('logo_ing.jpeg') }}" alt="logo" class="logo">
            <div class="company-details">
                <strong>{{ $azienda_emittente['nome'] }}</strong><br>
                {{ $azienda_emittente['indirizzo'] }}<br>
                P.IVA: {{ $azienda_emittente['piva'] }} - C.F: {{ $azienda_emittente['cf'] }}<br>
                Tel: {{ $azienda_emittente['telefono'] }} - PEC: {{ $azienda_emittente['pec'] }}<br>
                Codice Univoco: {{ $azienda_emittente['sdi'] }} - N. REA: {{ $azienda_emittente['rea'] }}
            </div>
        </div>

        <div class="content">
            <h2>PREVENTIVO</h2>
            <table style="width:100%;">
                <tr>
                    <td style="width:50%;">
                        <div class="client-details">
                            <strong>Spett.le</strong><br>
                            {{ $preventivo['appalto']->ditta_denominazione }}<br>
                            {{ $preventivo['appalto']->ditta_indirizzo }}<br>
                            {{ $preventivo['appalto']->ditta_cap }} {{ $preventivo['comune_ditta'] }} ({{ $preventivo['appalto']->ditta_provincia }})<br>
                            P.IVA: {{ $preventivo['appalto']->ditta_piva }} / C.F: {{ $preventivo['appalto']->ditta_cf }}
                        </div>
                    </td>
                    <td style="width:50%; vertical-align: top; text-align: right;">
                        Data: {{ \Carbon\Carbon::now()->format('d/m/Y') }}<br>
                        <strong>Preventivo N. {{ $preventivo['numero_preventivo'] }}</strong>
                    </td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Descrizione</th>
                        <th style="text-align: center;">Q.tà</th>
                        <th style="text-align: right;">Prezzo Unit.</th>
                        <th style="text-align: right;">IVA %</th>
                        <th style="text-align: right;">Imponibile</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preventivo['servizi'] as $servizio)
                    <tr>
                        <td>{{ $servizio['descrizione'] }}</td>
                        <td style="text-align: center;">{{ $servizio['quantita'] }}</td>
                        <td style="text-align: right;">€ {{ number_format($servizio['prezzo_unitario'], 2, ',', '.') }}</td>
                        <td style="text-align: right;">{{ $servizio['aliquota'] }}%</td>
                        <td style="text-align: right;">€ {{ number_format($servizio['imponibile'], 2, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <table>
                    <tr>
                        <td>Imponibile:</td>
                        <td style="text-align: right;">€ {{ number_format($preventivo['totale_imponibile'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>IVA:</td>
                        <td style="text-align: right;">€ {{ number_format($preventivo['totale_iva'], 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Totale Documento:</strong></td>
                        <td style="text-align: right;"><strong>€ {{ number_format($preventivo['totale_documento'], 2, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </div>

            <div style="clear:both;"></div>

            @if (!empty($preventivo['nota_corpo']))
            <div style="margin-top: 20px; font-size: 11px;">
                <p><b>Nota:</b> {{ $preventivo['nota_corpo'] }}</p>
            </div>
            @endif

            @if(!empty($preventivo['riferimenti_salma']))
            <div class="reference">
                Riferimento: Salme {{ implode(', ', $preventivo['riferimenti_salma']) }}
            </div>
            @endif

            <div style="text-align: right; margin-top: 50px;">
                <p>Firma per accettazione</p>
                <p>_________________________</p>
            </div>
        </div>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>