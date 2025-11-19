<?php
session_start();
$store_dir = "encrypted_uploads";

// Osiguraj direktorij
if (!is_dir($store_dir)) {
    mkdir($store_dir, 0777, true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['document'])) {
    $encryption_key = md5('jed4n j4k0 v3l1k1 kljuc');
    $cipher = "AES-128-CTR";
    $options = 0;
    $iv_length = openssl_cipher_iv_length($cipher);
    $encryption_iv = random_bytes($iv_length);

    $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($_FILES['document']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed_types)) {
        $file_data = file_get_contents($_FILES['document']['tmp_name']);
        // Kriptiraj dokument
        $encrypted_data = openssl_encrypt($file_data, $cipher, $encryption_key, $options, $encryption_iv);

        $original_name = $_FILES['document']['name'];
        $base_name = uniqid();

        // Spremi kriptirane podatke (RAW), IV (base64), meta (ime)
        file_put_contents("$store_dir/{$base_name}.enc", $encrypted_data);
        file_put_contents("$store_dir/{$base_name}.iv", base64_encode($encryption_iv));
        file_put_contents("$store_dir/{$base_name}.meta", $original_name);

        echo "<p>Dokument je kriptiran i spremljen pod nazivom: {$original_name}</p>";
    } else {
        echo "<p>Dozvoljeni formati su: pdf, jpg, jpeg, png.</p>";
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="document" required />
    <button type="submit">Upload & Encrypt</button>
</form>
