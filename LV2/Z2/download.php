<?php
// Prikaz i download kriptiranih dokumenata — LV2 finalni kod

// --- Postavke ---
$store_dir = "encrypted_uploads";
$encryption_key = md5('jed4n j4k0 v3l1k1 kljuc');
$cipher = "AES-128-CTR";
$options = 0;

// Funkcija za MIME tip prema ekstenziji
function get_mime_type($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    switch ($ext) {
        case 'pdf': return 'application/pdf';
        case 'jpg': case 'jpeg': return 'image/jpeg';
        case 'png': return 'image/png';
        default: return 'application/octet-stream';
    }
}

// --- Prikaz liste dokumenata ---
if (!isset($_GET['d'])) {
    echo "<h3>Kriptirani dokumenti:</h3>";
    $files = glob("$store_dir/*.enc");
    if ($files && count($files) > 0) {
        foreach ($files as $enc_file) {
            $base_name = basename($enc_file, '.enc');
            $meta_file = "$store_dir/$base_name.meta";
            if (file_exists($meta_file)) {
                $original_name = file_get_contents($meta_file);
                $safe_name = htmlspecialchars($original_name, ENT_QUOTES);
                echo "<p>
                    $safe_name
                    <a href='download.php?d={$base_name}'>Dekriptiraj & preuzmi</a>
                </p>";
            }
        }
    } else {
        echo "<p>Nema nijednog kriptiranog dokumenta.</p>";
    }
    exit; // Nakon liste — kraj skripte
}

// --- DOWNLOAD DEKRIPTIRANE DATOTEKE ---
$base_name = preg_replace('/[^a-zA-Z0-9._-]/', '', $_GET['d']);
$enc_file = "$store_dir/$base_name.enc";
$iv_file = "$store_dir/$base_name.iv";
$meta_file = "$store_dir/$base_name.meta";

if (file_exists($enc_file) && file_exists($iv_file) && file_exists($meta_file)) {
    $encrypted_data = file_get_contents($enc_file); // RAW podaci
    $encryption_iv  = base64_decode(file_get_contents($iv_file)); // IV u base64
    $decrypted_data = openssl_decrypt($encrypted_data, $cipher, $encryption_key, $options, $encryption_iv);

    $original_name = file_get_contents($meta_file);
    $mime_type = get_mime_type($original_name);

    // Provjeri je li dekripcija uspjela
    if ($decrypted_data === false) {
        header('Content-Type: text/plain');
        echo "Greška pri dekripciji: Provjeri ključ, IV, cipher!";
        exit;
    }

    // Osiguraj da ni jedan output nije prije headera
    if (ob_get_level()) ob_end_clean();

    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: attachment; filename="' . $original_name . '"');
    header('Content-Length: ' . strlen($decrypted_data));
    header('Connection: close');

    echo $decrypted_data;
    exit;
} else {
    header('Content-Type: text/plain');
    echo "Dokument ne postoji ili meta podatak nedostaje.";
    exit;
}
?>
