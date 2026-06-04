<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invito a Fatturare</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }
        .header {
            top: 0px;
        }
        .footer {
            bottom: 0px;
            font-size: 10px;
            color: #777;
        }
        .content {
            margin-top: 150px;
            margin-bottom: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            width: 40%;
            float: right;
        }
        .total-section table {
            width: 100%;
        }
        .total-section th, .total-section td {
            border: none;
            padding: 4px 8px;
        }
        .company-details, .client-details {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            margin-bottom: 20px;
        }
        .client-details {
            float: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
        h1, h2, h3 {
            margin: 0;
        }
        .document-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="content">
            <div class="clearfix" style="margin-bottom: 30px;">
                <div class="company-details">
                    <h3>{{ $azienda_prop }}</h3>
                    {{-- Aggiungi qui altri dettagli dell'azienda se necessario --}}
                </div>
                <div class="client-details">
                    <h3>Spett.le</h3>
                    <strong>{{ $denominazione }}</strong><br>
                    {{ $indirizzo }}<br>
                    {{ $cap }} {{ $comune }} ({{ $provincia }})<br>
                    @if($piva) P.IVA: {{ $piva }}<br> @endif
                    @if($cf) C.F.: {{ $cf }}<br> @endif
                    @if($sdi) SDI: {{ $sdi }}<br> @endif
                    @if($pec) PEC: {{ $pec }}<br> @endif
                </div>
            </div>

            <div class="document-title">
                <h2>INVITO A FATTURARE N. {{ $id_doc }}</h2>
                <h3>Data: {{ $data_fattura }}</h3>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Descrizione</th>
                        <th class="text-right">Q.tà</th>
                        <th class="text-right">Prezzo Unit.</th>
                        <th class="text-right">IVA %</th>
                        <th class="text-right">Subtotale</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totale_imponibile = 0;
                        $totale_iva = 0;
                        $totale_documento = 0;
                    @endphp
                    @foreach ($articoli_fattura as $articolo)
                        @php
                            $imponibile_riga = $articolo->quantita * $articolo->prezzo_unitario;
                            $aliquota_percentuale = $arr_aliquota[$articolo->aliquota] ?? 0;
                            $iva_riga = $imponibile_riga * ($aliquota_percentuale / 100);
                            
                            $totale_imponibile += $imponibile_riga;
                            $totale_iva += $iva_riga;
                            $totale_documento += $articolo->subtotale;
                        @endphp
                        <tr>
                            <td>{!! nl2br(e($articolo->descrizione)) !!}</td>
                            <td class="text-right">{{ number_format($articolo->quantita, 2, ',', '.') }}</td>
                            <td class="text-right">{{ number_format($articolo->prezzo_unitario, 2, ',', '.') }} €</td>
                            <td class="text-right">{{ $aliquota_percentuale }}%</td>
                            <td class="text-right">{{ number_format($articolo->subtotale, 2, ',', '.') }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="clearfix">
                <div class="total-section">
                    <table>
                        <tr>
                            <th>Imponibile:</th>
                            <td class="text-right">{{ number_format($totale_imponibile, 2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <th>IVA:</th>
                            <td class="text-right">{{ number_format($totale_iva, 2, ',', '.') }} €</td>
                        </tr>
                        <tr>
                            <th><strong>Totale Documento:</strong></th>
                            <td class="text-right"><strong>{{ number_format($totale_documento, 2, ',', '.') }} €</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            @if($note)
            <div style="margin-top: 30px;">
                <h4>Note:</h4>
                <p>{!! nl2br(e($note)) !!}</p>
            </div>
            @endif

        </div>

        <div class="footer">
            {{ $azienda_prop }}
        </div>
    </div>

</body>
</html>