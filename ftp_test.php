<?php

/**
 * Script di test per la connessione e l'upload di file via FTP.
 *
 * Questo script utilizza le funzioni FTP native di PHP per:
 * 1. Definire le credenziali di connessione.
 * 2. Creare un file di testo locale temporaneo.
 * 3. Connettersi al server FTP sulla porta specificata.
 * 4. Eseguire il login.
 * 5. Abilitare la modalità passiva (spesso necessaria).
 * 6. Caricare il file locale sul server remoto.
 * 7. Chiudere la connessione e pulire il file locale.
 *
 * Vengono stampati a schermo i messaggi di stato per ogni operazione.
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain; charset=utf-8');

// --- Parametri di connessione FTP ---
$ftp_host = "cli-ingenious_sancarlo.horizontelecom.it";
$ftp_user = "IngFun";
$ftp_pass = "Ingen2025!@1";
$ftp_port = 4000;
$ftp_timeout = 10;

// --- File di test ---
$local_file = 'test_ftp_locale.txt';
$remote_file = 'test_ftp_remoto.txt';
$file_content = 'Questo è un file di test per la connessione FTP generato il ' . date('Y-m-d H:i:s');

// 1. Crea il file locale
if (!file_put_contents($local_file, $file_content)) {
    die("ERRORE: Impossibile creare il file locale '$local_file'. Controlla i permessi della cartella.");
}
echo "OK: File locale '$local_file' creato con successo.\n";

// 2. Connessione al server FTP
echo "INFO: Tentativo di connessione a $ftp_host sulla porta $ftp_port...\n";
$conn_id = ftp_connect($ftp_host, $ftp_port, $ftp_timeout);

if (!$conn_id) {
    unlink($local_file); // Pulisce il file locale
    die("ERRORE: Connessione FTP fallita! Controlla host e porta.");
}
echo "OK: Connessione FTP stabilita.\n";

// 3. Login
echo "INFO: Tentativo di login con l'utente '$ftp_user'...\n";
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "OK: Login FTP effettuato con successo.\n";
} else {
    echo "ERRORE: Login FTP fallito! Controlla username e password.\n";
    ftp_close($conn_id);
    unlink($local_file);
    exit;
}

// 4. Abilita la modalità passiva (generalmente raccomandata)
ftp_pasv($conn_id, true);
echo "INFO: Modalità passiva abilitata.\n";

// 5. Upload del file
echo "INFO: Tentativo di upload del file '$local_file' come '$remote_file'...\n";
if (ftp_put($conn_id, $remote_file, $local_file, FTP_ASCII)) {
    echo "OK: File '$remote_file' caricato con successo sul server FTP.\n";
} else {
    echo "ERRORE: Upload del file fallito.\n";
}

// 6. Chiusura della connessione e pulizia
ftp_close($conn_id);
unlink($local_file);
echo "INFO: Connessione FTP chiusa e file locale eliminato.\n";

?>