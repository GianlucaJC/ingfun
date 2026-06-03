<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ditte;
use App\Models\articoli_fattura;
use App\Models\aliquote_iva;
use App\Models\pagamenti;
use App\Models\fatture;
use App\Models\preventivi;
use App\Models\candidati;
use App\Models\servizi_ditte;
use App\Models\servizi;
use App\Models\appalti;
use App\Models\lavoratoriapp;
use App\Models\urgenze;
use App\Models\italy_cities;
use App\Models\prod_prodotti;
use App\Models\prod_magazzini;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use PDF;

class ControllerInvito extends Controller
{
public function __construct()
	{
		$this->middleware('auth')->except(['index']);
		$lista_c=italy_cities::select("comune","cap","provincia")->get();
		$comuni_ref=array();
		foreach ($lista_c as $l) {
			$c=$l->cap;
			$p=$l->provincia;
			$chiave="$c|$p";
			$com=$l->comune;
			$comuni_ref[$chiave]=$com;
		}
		$this->comuni_ref=$comuni_ref;
	}		

	public function generaFattureDaAppalti(Request $request) {
		Log::info('--- Inizio Generazione Fatture da Appalti ---');
		DB::enableQueryLog();

		$ids_giorno_appalto = $request->input('ids');
		Log::info('ID giorni appalto ricevuti: ' . json_encode($ids_giorno_appalto));
	
		if (empty($ids_giorno_appalto)) {
			Log::warning('Nessun ID giorno appalto fornito.');
			return response()->json(['status' => 'error', 'message' => 'Nessun giorno di appalto selezionato.']);
		}
	
		$fatture_generate = []; // Associative array [id_giorno_appalto => [invoices]]
	
		foreach ($ids_giorno_appalto as $id_giorno_appalto) {
			Log::info("Processing giorno appalto ID: $id_giorno_appalto");
			// Get all individual boxes that have a ditta and services assigned
			$boxes = DB::table('appaltinew_info as i')
				->join('appaltinew_altro as a', function($join) {
					$join->on('i.id_appalto', '=', 'a.idapp')
						 ->on('i.m_e', '=', 'a.m_e')
						 ->on('i.id_box', '=', 'a.box');
				})
				->where('i.id_appalto', '=', $id_giorno_appalto)
				->whereNotNull('a.ditta')
				->where('a.ditta', '!=', 0)
				->whereNotNull('i.servizi_svolti')
				->where('i.servizi_svolti', '!=', '')
				->where('i.hide', '!=', 1) // Don't include hidden boxes
				->select('i.*', 'a.ditta', 'a.box as box_number')
				->orderBy('i.m_e')
				->orderBy('i.id_box')
				->get();
			
			$fatture_per_giorno = [];

			// Iterate over each box, as each box is a separate invoice
			foreach ($boxes as $box) {
				$id_ditta = $box->ditta;
				$articoli_da_creare = [];
				$totale_fattura = 0;
	
				// Get services for THIS box and prepare invoice lines
				$service_entries = explode(',', $box->servizi_svolti);

				foreach ($service_entries as $entry) {
                    $parts = explode(':', $entry); // Analizza la stringa per ID e quantità
                    $id_servizio = $parts[0];
                    $quantita_servizio = isset($parts[1]) ? intval($parts[1]) : 1; // Default a 1 se non specificata

					// Get service details
					$servizio_dettagli = DB::table('servizi as s')
						->join('servizi_ditte as sd', 's.id', '=', 'sd.id_servizio')
						->where('s.id', $id_servizio) // Usa l'ID del servizio corretto
						->where('sd.id_ditta', $id_ditta)
						->select('s.id_cod_servizi_ext', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota', 's.da_moltiplicare')
						->first();
					Log::info('Query dettagli servizio: ', DB::getQueryLog());
	
					if ($servizio_dettagli) {
						Log::info('Dettagli servizio trovati: ' . json_encode($servizio_dettagli));
						$aliquota_db = aliquote_iva::find($servizio_dettagli->aliquota);
						$aliquota_val = $aliquota_db ? $aliquota_db->aliquota : 0;
						
						// Se il servizio è da moltiplicare, usa il numero di persone dell'appalto.
						// Altrimenti, usa la quantità salvata.
						if ($servizio_dettagli->da_moltiplicare == 1) {
							$quantita = $box->numero_persone > 0 ? $box->numero_persone : 1;
						} else {
							$quantita = $quantita_servizio;
						}
						
						$subtotale = ($servizio_dettagli->importo_ditta * $quantita) * (1 + $aliquota_val / 100);

						$descrizione_finale = $servizio_dettagli->descrizione;
						if (!empty($box->nome_salma)) {
							$descrizione_finale .= " - Salma: " . $box->nome_salma;
						}
						if (!empty($box->note_fatturazione)) {
							$descrizione_finale .= " - Note: " . $box->note_fatturazione;
						}

						// Add to an array to be created later
                        $riga_fattura = [
                            'codice' => $servizio_dettagli->id_cod_servizi_ext,
                            'descrizione' => $descrizione_finale,
                            'quantita' => $quantita,
                            'prezzo_unitario' => $servizio_dettagli->importo_ditta,
                            'aliquota' => $servizio_dettagli->aliquota,
                            'subtotale' => $subtotale
                        ];
						$articoli_da_creare[] = $riga_fattura;
						Log::info('Riga fattura preparata: ' . json_encode($riga_fattura));
	
						$totale_fattura += $subtotale;
					} else {
						Log::warning("Nessun dettaglio servizio trovato per ID: $id_servizio e Ditta ID: $id_ditta");
					}
				}

				// Only create the invoice if there are lines to add
                if (!empty($articoli_da_creare)) {
                    Log::info('Creazione fattura per ditta ID ' . $id_ditta . ' con ' . count($articoli_da_creare) . ' righe. Totale: ' . $totale_fattura);
                    // Create one invoice per box
                    $fattura = new fatture;
                    $fattura->id_ditta = $id_ditta;
                    $fattura->data_invito = now()->toDateString();
                    $fattura->id_sezionale = 1; // Assuming default
                    $fattura->totale = $totale_fattura;
                    $fattura->save();
                    $id_doc = $fattura->id;
                    Log::info("Fattura creata con ID: $id_doc");

                    foreach ($articoli_da_creare as $dati_articolo) {
                        $articolo = new articoli_fattura;
                        $articolo->id_doc = $id_doc;
                        $articolo->codice = $dati_articolo['codice'];
                        $articolo->descrizione = $dati_articolo['descrizione'];
                        $articolo->quantita = $dati_articolo['quantita'];
                        $articolo->prezzo_unitario = $dati_articolo['prezzo_unitario'];
                        $articolo->aliquota = $dati_articolo['aliquota'];
                        $articolo->subtotale = $dati_articolo['subtotale'];
                        $articolo->save();
						Log::info('Riga articolo salvata per fattura ID ' . $id_doc . ': ' . json_encode($dati_articolo));
                    }

                    // Add to response
                    $ditta_info = DB::table('ditte')->where('id', $id_ditta)->first();
                    $ditta_name = $ditta_info ? $ditta_info->denominazione : "Sconosciuta";
                    $shift_name = ($box->m_e == 'M') ? 'Mattina' : 'Pomeriggio';
                    
                    $fatture_per_giorno[] = [
                        'id_fattura' => $id_doc,
                        'ditta_name' => $ditta_name . " (Appalto $shift_name / Box " . ($box->box_number + 1) . ")"
                    ];
                } else {
					Log::warning('Nessuna riga da fatturare per il Box ' . $box->box_number);
				}
				Log::info('--- Fine elaborazione Box ' . $box->box_number . ' ---');
			}

			// Iterate over each urgency, as each could be a separate invoice
			$urgenze = DB::table('appaltinew_urgenze')
				->where('idapp', $id_giorno_appalto)
				->whereNotNull('id_ditta')->where('id_ditta', '!=', 0)
				->whereNotNull('id_servizio')
				->get();

			foreach ($urgenze as $urgenza) {
				$id_ditta = $urgenza->id_ditta;
				$articoli_da_creare = [];
				$totale_fattura = 0;
				$id_servizio = $urgenza->id_servizio;

				$servizio_dettagli = DB::table('servizi as s')
					->join('servizi_ditte as sd', 's.id', '=', 'sd.id_servizio')
					->where('s.id', $id_servizio)
					->where('sd.id_ditta', $id_ditta)
					->select('s.id_cod_servizi_ext', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota')
					->first();
				
				if ($servizio_dettagli) {
					$aliquota_db = aliquote_iva::find($servizio_dettagli->aliquota);
					$aliquota_val = $aliquota_db ? $aliquota_db->aliquota : 0;
					
					$lavoratori_ids = explode(',', $urgenza->id_lavoratore);
					$quantita = count($lavoratori_ids) > 0 ? count($lavoratori_ids) : 1;

					$subtotale = ($servizio_dettagli->importo_ditta * $quantita) * (1 + $aliquota_val / 100);

					$descrizione_finale = $servizio_dettagli->descrizione;
					if (!empty($urgenza->descrizione)) {
						$descrizione_finale .= " - Urgenza: " . $urgenza->descrizione;
					}

					$riga_fattura = [
						'codice' => $servizio_dettagli->id_cod_servizi_ext,
						'descrizione' => $descrizione_finale,
						'quantita' => $quantita,
						'prezzo_unitario' => $servizio_dettagli->importo_ditta,
						'aliquota' => $servizio_dettagli->aliquota,
						'subtotale' => $subtotale
					];
					$articoli_da_creare[] = $riga_fattura;
					$totale_fattura += $subtotale;
				} else {
					Log::warning("Nessun dettaglio servizio trovato per urgenza ID: {$urgenza->id}, Servizio ID: {$id_servizio}, Ditta ID: {$id_ditta}");
				}

				if (!empty($articoli_da_creare)) {
                    $fattura = new fatture;
                    $fattura->id_ditta = $id_ditta;
                    $fattura->data_invito = now()->toDateString();
                    $fattura->id_sezionale = 1; // Assuming default
                    $fattura->totale = $totale_fattura;
                    $fattura->save();
                    $id_doc = $fattura->id;
                    Log::info("Fattura creata per urgenza con ID: $id_doc");

                    foreach ($articoli_da_creare as $dati_articolo) {
                        $articolo = new articoli_fattura;
                        $articolo->id_doc = $id_doc;
                        $articolo->codice = $dati_articolo['codice'];
                        $articolo->descrizione = $dati_articolo['descrizione'];
                        $articolo->quantita = $dati_articolo['quantita'];
                        $articolo->prezzo_unitario = $dati_articolo['prezzo_unitario'];
                        $articolo->aliquota = $dati_articolo['aliquota'];
                        $articolo->subtotale = $dati_articolo['subtotale'];
                        $articolo->save();
						Log::info('Riga articolo salvata per fattura ID ' . $id_doc . ': ' . json_encode($dati_articolo));
                    }

                    $ditta_info = DB::table('ditte')->where('id', $id_ditta)->first();
                    $ditta_name = $ditta_info ? $ditta_info->denominazione : "Sconosciuta";
                    
                    $fatture_per_giorno[] = [
                        'id_fattura' => $id_doc,
                        'ditta_name' => $ditta_name . " (Urgenza ID: " . $urgenza->id . ")"
                    ];
				}
			}

			if (!empty($fatture_per_giorno)) {
                $fatture_generate[$id_giorno_appalto] = $fatture_per_giorno;
            }
		}
	
		Log::info('--- Fine Generazione Fatture da Appalti ---');
		return response()->json(['status' => 'ok', 'message' => 'Fatture generate con successo.', 'fatture' => $fatture_generate]);
	}

	public function esportaFattureCsv(Request $request) {
		Log::info('--- Inizio Esportazione CSV da Appalti ---');
		DB::enableQueryLog();

		$ids_giorno_appalto = $request->input('ids');
		Log::info('ID giorni appalto ricevuti per export: ' . json_encode($ids_giorno_appalto));
	
		if (empty($ids_giorno_appalto)) {
			Log::warning('Nessun ID giorno appalto fornito per export.');
			return response()->json(['status' => 'error', 'message' => 'Nessun giorno di appalto selezionato.']);
		}
	
		$ditte_processate = [];
		$generated_files = [];

		// Use Laravel's Storage facade for consistency
		$temp_dir = 'csv_exports';
		Storage::disk('public')->makeDirectory($temp_dir);

		try {
			$all_boxes_query = DB::table('appaltinew_info as i')
				->join('appaltinew_altro as a', function($join) {
					$join->on('i.id_appalto', '=', 'a.idapp')
						 ->on('i.m_e', '=', 'a.m_e')
						 ->on('i.id_box', '=', 'a.box');
				})
				->whereIn('i.id_appalto', $ids_giorno_appalto)
				->whereNotNull('a.ditta')->where('a.ditta', '!=', 0)
				->whereNotNull('i.servizi_svolti')->where('i.servizi_svolti', '!=', '')
				->where('i.hide', '!=', 1)
				->orderBy('i.id_appalto')
				->orderBy('i.m_e')
				->orderBy('i.id_box');

			// Generate Orders CSV for each box
			$all_boxes = $all_boxes_query->select('i.*', 'a.ditta', 'a.box as box_number')->get();
			Log::info('Trovati ' . count($all_boxes) . ' box totali da esportare.');

			foreach ($all_boxes as $box) {
				Log::info('--- Inizio elaborazione export Box ' . ($box->box_number + 1) . ' per ditta ID: ' . $box->ditta . ' ---');
				$id_ditta = $box->ditta;
				$ditta_info = DB::table('ditte')->where('id', $id_ditta)->first();
				if (!$ditta_info) {
					Log::warning("Ditta non trovata per ID: $id_ditta nel ciclo dei box.");
					continue;
				}

				$articoli_per_csv = [];
				$service_entries = explode(',', $box->servizi_svolti);

				foreach ($service_entries as $entry) {
					$parts = explode(':', $entry);
					$id_servizio = $parts[0];
					$quantita_servizio = isset($parts[1]) ? intval($parts[1]) : 1;

					$servizio_dettagli = DB::table('servizi as s')
						->join('servizi_ditte as sd', 's.id', '=', 'sd.id_servizio')
						->where('s.id', $id_servizio)
						->where('sd.id_ditta', $id_ditta)
						->select('s.id_cod_servizi_ext', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota', 's.da_moltiplicare', 's.barcode')
						->first();

					if ($servizio_dettagli) {
						$aliquota_db = aliquote_iva::find($servizio_dettagli->aliquota);
						$aliquota_val = $aliquota_db ? $aliquota_db->aliquota : 0;

						if ($servizio_dettagli->da_moltiplicare == 1) {
							$quantita = $box->numero_persone > 0 ? $box->numero_persone : 1;
						} else {
							$quantita = $quantita_servizio;
						}

						$descrizione_finale = $servizio_dettagli->descrizione;
						if (!empty($box->nome_salma)) $descrizione_finale .= " - Salma: " . $box->nome_salma;
						if (!empty($box->note_fatturazione)) $descrizione_finale .= " - Note: " . $box->note_fatturazione;

						$articoli_per_csv[] = [
							'barcode' => $servizio_dettagli->barcode, // Add barcode here
							'codice' => $servizio_dettagli->id_cod_servizi_ext,
							'descrizione' => $descrizione_finale,
							'quantita' => $quantita,
							'prezzo_unitario' => $servizio_dettagli->importo_ditta,
							'aliquota_val' => $aliquota_val,
						];
					}
				}

				if (!empty($articoli_per_csv)) {
					$data_fattura = Carbon::parse($box->data_servizio)->format('Ymd'); // yyyymmdd
					$box_suffix = 0;
					if ($box->m_e == 'M') { // Mattina
						$box_suffix = 100 + ($box->box_number + 1);
					} else { // Pomeriggio
						$box_suffix = 200 + ($box->box_number + 1);
					}
					$basename = 'ordini_' . $data_fattura . '_' . $box->id_appalto . '_' . $box_suffix . '.csv';

					$filename = Storage::disk('public')->path($temp_dir . '/' . $basename);
					$file = fopen($filename, 'w');
					fputcsv($file, ["Nr", "N_Riga", "Data", "Barcode", "CodArt", "QTA_Impegnata", "Prezzo", "Al_iva", "des_nome", "codice_conto"], ';');
					
					foreach ($articoli_per_csv as $index => $articolo) {
						$data_riga = [
							$box->id, $index + 1, $box->data_servizio, $articolo['barcode'], $articolo['codice'], $articolo['quantita'],
							number_format($articolo['prezzo_unitario'], 2, "", ""), $articolo['aliquota_val'],
							$ditta_info->nome,
							$ditta_info->codice_conto
						];
						fputcsv($file, $data_riga, ';');
					}
					fclose($file);
					$generated_files[] = $basename;
					Log::info("Generato file ordini per box: $basename");
				}
			}
	
			$all_urgenze = DB::table('appaltinew_urgenze')
				->whereIn('idapp', $ids_giorno_appalto)
				->whereNotNull('id_ditta')->where('id_ditta', '!=', 0)
				->whereNotNull('id_servizio')
				->orderBy('idapp')
				->orderBy('id') // Ensure consistent ordering for urgency counter


				->get();
			Log::info('Trovate ' . count($all_urgenze) . ' urgenze totali da esportare.');

			foreach ($all_urgenze as $urgenza) {
				Log::info('--- Inizio elaborazione export Urgenza ID: ' . $urgenza->id . ' per ditta ID: ' . $urgenza->id_ditta . ' ---');
				$id_ditta = $urgenza->id_ditta;
				$ditta_info = DB::table('ditte')->where('id', $id_ditta)->first();
				if (!$ditta_info) {
					Log::warning("Ditta non trovata per ID: $id_ditta nel ciclo delle urgenze.");
					continue;
				}

				$articoli_per_csv = [];
				$id_servizio = $urgenza->id_servizio;

				$servizio_dettagli = DB::table('servizi as s')
					->join('servizi_ditte as sd', 's.id', '=', 'sd.id_servizio')
					->where('s.id', $id_servizio)->where('sd.id_ditta', $id_ditta)
					->select('s.id_cod_servizi_ext', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota')
					->select('s.id_cod_servizi_ext', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota', 's.barcode')->first();

				if ($servizio_dettagli) {
					$aliquota_db = aliquote_iva::find($servizio_dettagli->aliquota);
					$aliquota_val = $aliquota_db ? $aliquota_db->aliquota : 0;

					$lavoratori_ids = explode(',', $urgenza->id_lavoratore);
					$quantita = count($lavoratori_ids) > 0 ? count($lavoratori_ids) : 1;

					$descrizione_finale = $servizio_dettagli->descrizione;
					if (!empty($urgenza->descrizione)) $descrizione_finale .= " - Urgenza: " . $urgenza->descrizione;

					$riga_csv = [
						'barcode' => $servizio_dettagli->barcode, // Add barcode here
						'codice' => $servizio_dettagli->id_cod_servizi_ext,
						'descrizione' => $descrizione_finale,
						'quantita' => $quantita,
						'prezzo_unitario' => $servizio_dettagli->importo_ditta,
						'aliquota_val' => $aliquota_val,
					];
					$articoli_per_csv[] = $riga_csv;
					Log::info('Riga CSV per urgenza preparata: ' . json_encode($riga_csv));
				} else {
					Log::warning("Nessun dettaglio servizio trovato per export per urgenza ID: {$urgenza->id}, Servizio ID: {$id_servizio}, Ditta ID: {$id_ditta}");
				}

				if (!empty($articoli_per_csv)) {
					$data_servizio_urgenza = DB::table('appaltinew')->where('id', $urgenza->idapp)->value('data_appalto');
					$data_fattura = Carbon::parse($data_servizio_urgenza)->format('Ymd'); // yyyymmdd

					// Calculate urgency suffix (301..399)
					static $current_appalto_id = null;
					static $urgency_counter = 0;
					if ($urgenza->idapp !== $current_appalto_id) { $current_appalto_id = $urgenza->idapp; $urgency_counter = 0; }
					$urgency_suffix = 300 + (++$urgency_counter);
					$basename = 'ordini_' . $data_fattura . '_' . $urgenza->idapp . '_' . $urgency_suffix . '.csv';

					$filename = Storage::disk('public')->path($temp_dir . '/' . $basename);
					$file = fopen($filename, 'w');
					fputcsv($file, ["Nr", "N_Riga", "Data", "Barcode", "CodArt", "QTA_Impegnata", "Prezzo", "Al_iva", "des_nome", "codice_conto"], ';');
					

					foreach ($articoli_per_csv as $index => $articolo) {
						$data_riga = [
							$urgenza->id, $index + 1, $data_servizio_urgenza, $articolo['barcode'], $articolo['codice'], $articolo['quantita'],
							number_format($articolo['prezzo_unitario'], 2, "", ""), $articolo['aliquota_val'],
							$ditta_info->nome,
							$ditta_info->codice_conto
						];
						fputcsv($file, $data_riga, ';');
					}
					fclose($file);
					$generated_files[] = $basename;
					Log::info("Generato file ordini per urgenza: $basename");
				}
			}

			// FTP Upload Attempt
			if (!empty($generated_files)) {
				$host = env('FTP_HOST');
				$port = (int) env('FTP_PORT', 21);
				$user = env('FTP_USERNAME');
				$pass = env('FTP_PASSWORD');
				$timeout = (int) env('FTP_TIMEOUT', 30);
				$root = env('FTP_ROOT', '/');

				$conn_id = ftp_connect($host, $port, $timeout);
				if ($conn_id === false) {
					throw new \Exception("Connessione FTP fallita verso $host:$port");
				}

				if (ftp_login($conn_id, $user, $pass) === false) {
					ftp_close($conn_id);
					throw new \Exception("Autenticazione FTP fallita per l'utente $user");
				}

				// Imposta l'opzione per problemi con server dietro NAT
				if (ftp_set_option($conn_id, FTP_USEPASVADDRESS, false) === false) {
					// Non è un errore fatale, ma logghiamo un avviso
					\Log::warning("Impossibile impostare l'opzione FTP FTP_USEPASVADDRESS.");
				}

				// Abilita la modalità passiva, cruciale per superare i firewall
				if (ftp_pasv($conn_id, true) === false) {
					ftp_close($conn_id);
					throw new \Exception("Impossibile abilitare la modalità passiva FTP.");
				}

				foreach($generated_files as $basename) {
					$local_file_path = Storage::disk('public')->path($temp_dir . '/' . $basename);
					$remote_file_path = rtrim($root, '/') . '/Impegni/' . $basename;

					if (file_exists($local_file_path) && !@ftp_put($conn_id, $remote_file_path, $local_file_path, FTP_ASCII)) {
						throw new \Exception("Upload FTP fallito per il file $basename. Controllare i log del server FTP e le regole del firewall.");
					}
				}
				ftp_close($conn_id);
			}

			// Mark as exported ONLY if FTP upload was successful (and files were generated)
			if (count($generated_files) > 0) {
				DB::table('appaltinew')->whereIn('id', $ids_giorno_appalto)->update(['data_esportazione' => now()]);
			}
		} catch (\Exception $e) {
			Log::error('Errore durante esportazione CSV o FTP: ' . $e->getMessage());
			return response()->json([
				'status' => 'error', 
				'message' => 'Errore durante l\'upload FTP: ' . $e->getMessage() . ". I file CSV sono stati comunque generati e sono scaricabili.",
				'files' => $generated_files
			]);
		}
	
		Log::info('--- Fine Esportazione CSV da Appalti ---');
		return response()->json([
			'status' => 'ok', 
			'message' => count($generated_files) . " file CSV generati e caricati con successo.",
			'files' => $generated_files
		]);
	}

	public function svuotaListaCsv()
	{
		try {
			$files = Storage::disk('public')->files('csv_exports');
			foreach ($files as $file) {
				// We only want to delete csv files
				if (pathinfo($file, PATHINFO_EXTENSION) === 'csv') {
					Storage::disk('public')->delete($file);
				}
			}
			return response()->json(['status' => 'ok', 'message' => 'Elenco file CSV svuotato con successo.']);
		} catch (\Exception $e) {
			return response()->json(['status' => 'error', 'message' => 'Si è verificato un errore durante la pulizia dei file CSV: ' . $e->getMessage()]);
		}
	}

	public function save_edit_aliquote($request) {
		$edit_elem=0;
		if ($request->has("edit_elem")) $edit_elem=$request->input("edit_elem");
		$descr_contr=$request->input("descr_contr");
		$aliquota=$request->input("aliquota");
		
		
		$dele_contr=$request->input("dele_contr");
		$restore_contr=$request->input("restore_contr");
		
		
		$data=['dele'=>0, 'descrizione' => $descr_contr,'aliquota'=>$aliquota];
		

		//Creazione nuovo elemento
		if (strlen($descr_contr)!=0 && $edit_elem==0) {
			DB::table("aliquote_iva")->insert($data);
		}
		
		//Modifica elemento
		if (strlen($descr_contr)!=0 && $edit_elem!=0) {
			aliquote_iva::where('id', $edit_elem)			
			  ->update($data);
		}
		if (strlen($dele_contr)!=0) {
			aliquote_iva::where('id', $dele_contr)
			  ->update(['dele' => 1]);			
		}
		if (strlen($restore_contr)!=0) {
			aliquote_iva::where('id', $restore_contr)
			  ->update(['dele' => 0]);			
		}

	}
	
	public function aliquote(Request $request) {
		$esito_saves=$this->save_edit_aliquote($request);
		$view_dele=$request->input("view_dele");
		
		if (strlen($view_dele)==0) $view_dele=0;
		if ($view_dele=="on") $view_dele=1;
		
		$aliquote=DB::table('aliquote_iva')
		->select("*")
		->when($view_dele=="0", function ($aliquote) {
			return $aliquote->where('dele', "=","0");
		})
		->orderBy('aliquota')->get();

		return view('all_views/invitofatt/aliquote')->with('aliquote', $aliquote)->with("view_dele",$view_dele)->with('esito_saves',$esito_saves);		
	}
	
	public function import_from_urgenze() {
		$request=request();
		$urg_sel=$request->input('urg_sel');
		$id_doc=$request->input('id_doc');
		$importi=array();
		$range_da_u=$request->input('range_da_u');
		$range_a_u=$request->input('range_a_u');

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}

		if (is_array($urg_sel)) {
			$indice=0;
			for ($sca=0;$sca<=count($urg_sel)-1;$sca++) {
				
				$info_urg=$urg_sel[$sca];
				$arr_urg=explode("|",$info_urg);
				$id_urg=$arr_urg[0];
				$id_servizio=$arr_urg[1];
				$id_ditta=$arr_urg[2];
				$deleted = articoli_fattura::where('id_urgenza', $id_urg)
				->where('id_doc',$id_doc)
				->delete();
		
				$info_servizio=DB::table('servizi_ditte as sd')
				->join('servizi as s','sd.id_servizio','s.id')
				->select('sd.importo_ditta','sd.aliquota','s.descrizione','s.id_cod_servizi_ext')
				->where('sd.id_servizio', "=",$id_servizio)	
				->where('sd.id_ditta', "=",$id_ditta)	
				->first();
				if ($info_servizio) {
					$descr = $info_servizio->descrizione;
					$importo_ditta = $info_servizio->importo_ditta;
					$aliquota = $info_servizio->aliquota;
					$id_cod_servizi_ext = $info_servizio->id_cod_servizi_ext;
					if (isset($arr_aliquota[$aliquota])) 
						$subtotale=$importo_ditta*(($arr_aliquota[$aliquota]/100)+1);
						
					DB::table('articoli_fattura')->insert([
						'id_doc' => $id_doc,
						'codice' => $id_cod_servizi_ext,
						'id_urgenza' => $id_urg,
						'descrizione' =>$descr,
						'quantita' => 1,
						'prezzo_unitario' =>$importo_ditta,
						'aliquota' =>$aliquota,
						'subtotale' =>$subtotale,
						'created_at'=>now(),
						'updated_at'=>now()
					]);					
				}
				//segna l'id urgenza come fatturata per non ripresentarla
				urgenze::where('id', $id_urg)->update(['fatturato' => 1]);					

			}
		}
		return $importi;		
	}		



	public function import_from_appalti() {
		$request=request();
		$app_sel=$request->input('app_sel');
		$id_doc=$request->input('id_doc');
		$importi=array();
		$range_da=$request->input('range_da');
		$range_a=$request->input('range_a');

		$aliquote_iva=aliquote_iva::select('id','aliquota','descrizione')
		->get();
		
		$arr_aliquota=array();
		foreach ($aliquote_iva as $aliquota) {
			if (isset($aliquota->id))
				$arr_aliquota[$aliquota->id]=$aliquota->aliquota;
		}
		
		if (is_array($app_sel)) {
			$indice=0;
			for ($sca=0;$sca<=count($app_sel)-1;$sca++) {
				$id_app=$app_sel[$sca];
				$deleted = articoli_fattura::where('id_appalto', $id_app)
				->where('id_doc',$id_doc)
				->delete();
		
				$appalti=DB::table('appalti as a')
				->join("serviziapp as s","a.id","s.id_appalto")
				->select("a.id_ditta",DB::raw("DATE_FORMAT(a.data_ref,'%d-%m-%Y') as data_ref"),"s.id_servizio","a.km_percorrenza","a.orario_fine_servizio","a.testo_libero")
				->where('a.id', "=",$id_app)	
				->get();

				appalti::where('id', $id_app)->update(['status' => 1]);					
				
				$num_lav=lavoratoriapp::where('id_appalto',$id_app)->where('status','=',1)->count();

				foreach ($appalti as $appalto) {
					$data_ref=$appalto->data_ref;
					$id_ditta=$appalto->id_ditta;
					$id_servizio=$appalto->id_servizio;
					$km=$appalto->km_percorrenza;
					$testo_libero=$appalto->testo_libero;					
					$servizi_ditte=DB::table('servizi_ditte as sd')
					->join('servizi as s','sd.id_servizio','s.id')
					->select("s.id_cod_servizi_ext","s.descrizione","sd.importo_ditta","sd.aliquota")
					->where('sd.id_ditta', "=",$id_ditta)	
					->where('sd.id_servizio', "=",$id_servizio)	
					->get(); 
					foreach ($servizi_ditte as $servizio) {
						$importo_ditta=$servizio->importo_ditta;
						$codice=$servizio->id_cod_servizi_ext;
						$aliquota=$servizio->aliquota;
						$descr=$servizio->descrizione."($data_ref) ";
						$subtotale=$importo_ditta;
						if(strpos($descr,'RIMBORSO KM') !== false) {
							$descr.=" ".$km."*".$importo_ditta;
							$importo_ditta=floatval($km)*floatval($importo_ditta);
							$subtotale=$importo_ditta;
						}
						if ($codice=="S") {
							//SERVIZI FUNEBRI ANNESSI E DISGIUNTI DA QUELLI DI ONORANZE FUNEBRI
							$descr.=" ".$num_lav."*".$importo_ditta;
							$importo_ditta=floatval($num_lav)*floatval($importo_ditta);
							$subtotale=$importo_ditta;

						}						
						if (strlen($testo_libero)!=0) $descr.=" ($testo_libero)";
						if (isset($arr_aliquota[$aliquota])) 
							$subtotale=$importo_ditta*(($arr_aliquota[$aliquota]/100)+1);
						
						DB::table('articoli_fattura')->insert([
							'id_appalto' => $id_app,
							'codice' =>$codice,
							'id_doc' => $id_doc,
							'descrizione' =>$descr,
							'quantita' => 1,
							'prezzo_unitario' =>$importo_ditta,
							'aliquota' =>$aliquota,
							'subtotale' =>$subtotale,
							'testo_libero_appalti'=>$testo_libero,
							'created_at'=>now(),
							'updated_at'=>now()
						]);
	
					}	
				}
			}
		}
		return $importi;
	}

	/**
	 * Retrieves appalto IDs for the previous month that are eligible for invoicing.
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getAppaltiIdsForPreviousMonth(Request $request)
	{
        $targetDate = Carbon::now()->subMonth();
        $startDate = $targetDate->copy()->startOfMonth()->toDateString();
        $endDate = $targetDate->copy()->endOfMonth()->toDateString();

		// Imposta la locale a italiano prima di formattare
		$dateRange = $targetDate->locale('it')->translatedFormat('F Y');

		// 1. Trova ID da appalti "box"
		$detailsFromBoxes = DB::table('appaltinew_info as i')
			->join('appaltinew_altro as a', function ($join) {
				$join->on('i.id_appalto', '=', 'a.idapp')
					->on('i.m_e', '=', 'a.m_e')
					->on('i.id_box', '=', 'a.box');
			})
			->join('appaltinew as an', 'i.id_appalto', '=', 'an.id')
			->whereBetween('i.data_servizio', [$startDate, $endDate])
			->whereNotNull('a.ditta')->where('a.ditta', '!=', 0)
			->whereNotNull('i.servizi_svolti')
			->where('i.servizi_svolti', '!=', '')
			->where('i.hide', '!=', 1)
			->distinct()
			->select('i.id_appalto', 'an.data_appalto', 'i.m_e', 'i.id_box')
			->get();

		// 2. Trova ID da "urgenze"
		$detailsFromUrgenze = DB::table('appaltinew_urgenze as u')
			->join('appaltinew as an', 'u.idapp', '=', 'an.id')
			->whereBetween('an.data_appalto', [$startDate, $endDate])
			->whereNotNull('u.id_ditta')->where('u.id_ditta', '!=', 0)
			->whereNotNull('u.id_servizio')
			->distinct()
			->select('u.idapp as id_appalto', 'an.data_appalto', 'u.id as id_urgenza')
			->get();

		// 3. Raggruppa per id_appalto per il conteggio
		$idsFromBoxes = $detailsFromBoxes->pluck('id_appalto')->unique();
		$idsFromUrgenze = $detailsFromUrgenze->pluck('id_appalto')->unique();

		// 4. Calcola i conteggi e i dettagli per la risposta
		$count_boxes = $idsFromBoxes->count();
		$urgenze_only_ids = $idsFromUrgenze->diff($idsFromBoxes);
		$count_urgenze = $urgenze_only_ids->count();

		// Filtra i dettagli per la risposta
		$details_urgenze_filtered = $detailsFromUrgenze->whereIn('id_appalto', $urgenze_only_ids);

		// Conta solo le urgenze che non sono già presenti nei box

		// 4. Unisci e rendi unici gli ID per l'esportazione
		$eligibleAppaltiIds = $idsFromBoxes->merge($idsFromUrgenze)->unique()->values()->toArray();

		return response()->json([
			'status' => 'ok',
			'ids' => $eligibleAppaltiIds,
			'date_range' => $dateRange,
			'count_boxes' => $count_boxes,
			'count_urgenze' => $count_urgenze,
			'total_count' => count($eligibleAppaltiIds),
			'details_boxes' => $detailsFromBoxes,
			'details_urgenze' => $details_urgenze_filtered->values()
		]);
	}

	public function getDettagliAppaltiPerPreventivo(Request $request) {
		$ids_giorno_appalto = $request->input('ids');
	
		if (empty($ids_giorno_appalto)) {
			return response()->json(['status' => 'error', 'message' => 'Nessun giorno di appalto selezionato.']);
		}
	
		$appalti_valorizzati = DB::table('appaltinew_info as i')
			->join('appaltinew_altro as a', function($join) {
				$join->on('i.id_appalto', '=', 'a.idapp')
					 ->on('i.m_e', '=', 'a.m_e')
					 ->on('i.id_box', '=', 'a.box');
			})
			->join('ditte as d', 'a.ditta', '=', 'd.id')
			->whereIn('i.id_appalto', $ids_giorno_appalto)
			->whereNotNull('a.ditta')
			->where('a.ditta', '!=', 0)
			->where('i.hide', '!=', 1)
			->select(
				'i.id as id_appalto_info',
				'i.id_appalto', 
				'i.data_servizio', 
				'd.denominazione as ditta_name', 
				'i.m_e', 
				'a.box as box_number'
			)
			->orderBy('i.id_appalto')
			->orderBy('i.data_servizio')
			->orderBy('i.m_e')
			->orderBy('a.box')
			->get();
	
		if ($appalti_valorizzati->isEmpty()) {
			return response()->json(['status' => 'info', 'message' => 'Nessun appalto con ditta associata trovato nei giorni selezionati.']);
		}
	
		return response()->json(['status' => 'ok', 'appalti' => $appalti_valorizzati]);
	}

	public function generaPreventivoPdf(Request $request)
	{
		$ids_info = $request->input('ids'); // These are appaltinew_info IDs
	
		if (empty($ids_info)) {
			return response()->json(['status' => 'error', 'message' => 'Nessun appalto selezionato.']);
		}
	
		// --- 1. Fetch all necessary data ---
		// Fetch all data in one go to reduce queries
		$appalti = DB::table('appaltinew_info as i')
			->join('appaltinew_altro as a', function($join) {
				$join->on('i.id_appalto', '=', 'a.idapp')
						->on('i.m_e', '=', 'a.m_e')
						->on('i.id_box', '=', 'a.box');
			})
			->join('ditte as d', 'a.ditta', '=', 'd.id')
			->join('appaltinew as an', 'i.id_appalto', '=', 'an.id')
			->whereIn('i.id', $ids_info)
			->select(
				'i.*', 'a.ditta', 'an.data_appalto',
				'd.denominazione as ditta_denominazione', 'd.indirizzo as ditta_indirizzo',
				'd.cap as ditta_cap', 'd.comune as ditta_comune', 'd.provincia as ditta_provincia',
				'd.piva as ditta_piva', 'd.cf as ditta_cf'
			)->get();
	
		if ($appalti->isEmpty()) {
			return response()->json(['status' => 'error', 'message' => 'Nessun dato valido trovato per gli appalti selezionati.']);
		}
	
		$appalti_per_ditta = $appalti->groupBy('ditta');
		
		// Fetch service details in bulk
		$all_servizi_ids = $appalti->pluck('servizi_svolti')->map(function($item) {
			return explode(',', $item);
		})->flatten()->map(function($item) {
			return explode(':', $item)[0];
		})->unique()->filter()->values();
	
		$servizi_details = DB::table('servizi as s')
			->join('servizi_ditte as sd', 's.id', '=', 'sd.id_servizio')
			->whereIn('s.id', $all_servizi_ids)
			->select('s.id', 's.descrizione', 'sd.importo_ditta', 'sd.aliquota', 's.da_moltiplicare', 'sd.id_ditta')
			->get()->groupBy('id_ditta');
	
		$aliquote_iva = aliquote_iva::pluck('aliquota', 'id');
	
		// --- 2. Setup for PDF/ZIP generation ---
		$azienda_emittente = [
			'nome' => 'LA MISERICORDIA S.R.L.',
			'indirizzo' => 'VIA GIOVANNI BATTISTA TIEPOLO 21 – 00196 ROMA',
			'piva' => 'IT01922690670', 'cf' => '01922690670', 'rea' => 'RM-1725305',
			'telefono' => '08528879', 'sdi' => '1N74KED', 'pec' => 'LAMISERICORDIA15@PEC.IT',
		];
		$storage_path = 'preventivi_generati';
		Storage::disk('public')->makeDirectory($storage_path);
		
		$generated_quotes_info = [];
	
		// --- 3. Loop through each client and generate their quote ---
		$preventivi_data = [];
		foreach ($appalti_per_ditta as $ditta_id => $appalti_del_cliente) {
			// ... (logic to build $servizi_list, $totali, etc. - same as before)
			// ...
			$servizi_list = [];
			$totale_imponibile = 0;
			$totale_iva = 0;
			$riferimenti_salma = [];
			$ids_info_per_questo_preventivo = [];
			$associated_days = [];
			$riferimenti_gruppati = [];
			$nota_corpo = "";
	
			foreach ($appalti_del_cliente as $appalto) {
				$ids_info_per_questo_preventivo[] = $appalto->id;
				$associated_days[] = $appalto->id_appalto;

				$id_appalto_giorno = $appalto->id_appalto;
                $box_number = $appalto->id_box + 1;
                if (!isset($riferimenti_gruppati[$id_appalto_giorno])) $riferimenti_gruppati[$id_appalto_giorno] = [];
                if (!in_array($box_number, $riferimenti_gruppati[$id_appalto_giorno])) $riferimenti_gruppati[$id_appalto_giorno][] = $box_number;

				if (isset($appalto->prezzo_a_corpo) && $appalto->prezzo_a_corpo > 0) {
					if (empty($nota_corpo)) $nota_corpo = "Corrispettivo a corpo come da accordi";
					// Logica "a corpo": elenco servizi con prezzo a 0 e aggiungo riga per il totale
					$prezzo_corpo = $appalto->prezzo_a_corpo;

					// Elenca i servizi con prezzo zero
					if (!empty($appalto->servizi_svolti)) {
						$service_entries = explode(',', $appalto->servizi_svolti);
						foreach ($service_entries as $entry) {
							$parts = explode(':', $entry);
							$id_servizio = $parts[0];
							if (empty($id_servizio)) continue;
							$quantita_servizio = isset($parts[1]) ? intval($parts[1]) : 1;
		
							$servizio_dettaglio = $servizi_details->get($ditta_id, collect())->firstWhere('id', (int)$id_servizio);
		
							if ($servizio_dettaglio) {
								$quantita = ($servizio_dettaglio->da_moltiplicare == 1 && $appalto->numero_persone > 0) ? $appalto->numero_persone : $quantita_servizio;
								$data_servizio_f = Carbon::parse($appalto->data_servizio)->format('d/m/Y');
								$riferimento_appalto = $appalto->id_appalto . "/" . ($appalto->id_box + 1);
								$descrizione_servizio = $servizio_dettaglio->descrizione . " (Rif. Appalto {$riferimento_appalto} del {$data_servizio_f})";

								$servizi_list[] = [
									'descrizione' => $descrizione_servizio,
									'quantita' => $quantita,
									'prezzo_unitario' => 0,
									'imponibile' => 0,
									'aliquota' => 0,
								];
							}
						}
					}

					$totale_imponibile += $prezzo_corpo;

				} else {
					// Logica esistente per i servizi riga per riga
					if (!empty($appalto->servizi_svolti)) {
						$service_entries = explode(',', $appalto->servizi_svolti);
						foreach ($service_entries as $entry) {
							$parts = explode(':', $entry);
							$id_servizio = $parts[0];
							if (empty($id_servizio)) continue;
							$quantita_servizio = isset($parts[1]) ? intval($parts[1]) : 1;

							$servizio_dettaglio = $servizi_details->get($ditta_id, collect())->firstWhere('id', (int)$id_servizio);

							if ($servizio_dettaglio) {
								$quantita = ($servizio_dettaglio->da_moltiplicare == 1 && $appalto->numero_persone > 0) ? $appalto->numero_persone : $quantita_servizio;
								$prezzo = $servizio_dettaglio->importo_ditta;
								$imponibile = $prezzo * $quantita;
								$aliquota_percent = $aliquote_iva->get($servizio_dettaglio->aliquota, 0);
								$iva = $imponibile * ($aliquota_percent / 100);

								$data_servizio_f = Carbon::parse($appalto->data_servizio)->format('d/m/Y');
								$riferimento_appalto = $appalto->id_appalto . "/" . ($appalto->id_box + 1);
								$descrizione_servizio = $servizio_dettaglio->descrizione . " (Rif. Appalto {$riferimento_appalto} del {$data_servizio_f})";
								
								$servizi_list[] = [
									'descrizione' => $descrizione_servizio,
									'quantita' => $quantita,
									'prezzo_unitario' => $prezzo,
									'imponibile' => $imponibile,
									'aliquota' => $aliquota_percent,
								];
								$totale_imponibile += $imponibile;
								$totale_iva += $iva;
							}
						}
					}
				}

				if (!empty($appalto->nome_salma)) {
					$riferimenti_salma[] = $appalto->nome_salma;
				}
			}
			$primo_appalto = $appalti_del_cliente->first();
			$comune_ditta = $primo_appalto->ditta_comune;
			if (isset($this->comuni_ref[$primo_appalto->ditta_cap.'|'.$primo_appalto->ditta_provincia])) {
				$comune_ditta = $this->comuni_ref[$primo_appalto->ditta_cap.'|'.$primo_appalto->ditta_provincia];
			}
			$ids_info_cliente = $appalti_del_cliente->pluck('id')->unique()->implode(', ');

			$final_ref_strings = [];
            foreach ($riferimenti_gruppati as $id_appalto_giorno => $box_numbers) {
                sort($box_numbers);
                $final_ref_strings[] = $id_appalto_giorno . '/' . implode('-', $box_numbers);
            }
			$numero_preventivo_string = implode(', ', $final_ref_strings);

			$preventivo_data_single = [
				'appalto' => $primo_appalto,
				'comune_ditta' => $comune_ditta,
				'servizi' => $servizi_list,
				'totale_imponibile' => $totale_imponibile,
				'totale_iva' => $totale_iva,
				'totale_documento' => $totale_imponibile + $totale_iva,
				'riferimenti_salma' => array_unique($riferimenti_salma),
				'ids_appalto_info' => $ids_info_cliente,
				'numero_preventivo' => $numero_preventivo_string,
				'nota_corpo' => $nota_corpo,
			];
	
			// --- 4. Generate and Save the PDF ---
			$pdf_data = [
				'preventivi' => [$preventivo_data_single], // Pass it as an array with one element to reuse the view
				'azienda_emittente' => $azienda_emittente
			];
			
			$pdf = PDF::loadView('all_views.preventivi.pdf', $pdf_data);
			
			$filename = "preventivo_" . $primo_appalto->ditta_denominazione . "_" . date('YmdHis') . ".pdf";
			$filename = preg_replace("/[^a-zA-Z0-9_.-]/", "", $filename); // Sanitize filename
			
			Storage::disk('public')->put($storage_path . '/' . $filename, $pdf->output());
	
			// --- 5. Save to DB (assuming tables `preventivi_generati` and `preventivo_appalto_info` exist) ---
			/*
			$preventivo_generato_id = DB::table('preventivi_generati')->insertGetId([
				'id_ditta' => $ditta_id,
				'data_generazione' => now(),
				'filename' => $filename,
				'path' => $storage_path,
				'created_at' => now(),
				'updated_at' => now(),
			]);
	
			$pivot_data = [];
			foreach ($ids_info_per_questo_preventivo as $info_id) {
				$pivot_data[] = [
					'preventivo_generato_id' => $preventivo_generato_id,
					'appalto_info_id' => $info_id
				];
			}
			DB::table('preventivo_appalto_info')->insert($pivot_data);
			*/
	
			// --- 6. Prepare data for JSON response ---
			$generated_quotes_info[] = [
				'pdf_url' => Storage::url($storage_path . '/' . $filename),
				'ditta_name' => $primo_appalto->ditta_denominazione,
				'associated_days' => array_unique($associated_days)
			];
		}
	
		// --- 7. Create ZIP if needed and finalize response ---
		$message = "";
		$count = count($generated_quotes_info);
	
		if ($count > 1) {
			$message = "$count preventivi sono stati generati.";
		} elseif ($count == 1) {
			$message = "1 preventivo è stato generato.";
		} else {
			return response()->json(['status' => 'info', 'message' => 'Nessun preventivo da generare.']);
		}
	
		return response()->json([
			'status' => 'ok',
			'message' => $message,
			'quotes' => $generated_quotes_info
		]);
	}


}
