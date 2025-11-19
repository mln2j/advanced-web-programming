<?php
// Naziv baze podataka i direktorij za backup
$db_name = 'radovi';
$dir = "backup/$db_name";

// Ako direktorij ne postoji, stvori ga
if (!is_dir($dir)) {
    if (!@mkdir($dir, 0777, true)) {
        die("<p>Ne možemo stvoriti direktorij $dir.</p></body></html>");
    }
}

// Spoji se na bazu podataka
$dbc = @mysqli_connect('localhost', 'root', '', $db_name) or die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");

// Dohvati sve tablice iz baze
$r = mysqli_query($dbc, 'SHOW TABLES');

// Backup svake tablice
if (mysqli_num_rows($r) > 0) {
    echo "<p>Backup baze podataka '$db_name'.</p>";
    while (list($table) = mysqli_fetch_array($r, MYSQLI_NUM)) {
        // Dohvati podatke iz tablice
        $q = "SELECT * FROM $table";
        $r2 = mysqli_query($dbc, $q);

        if (mysqli_num_rows($r2) > 0) {
            // Dohvati nazive stupaca
            $columns_res = mysqli_query($dbc, "SHOW COLUMNS FROM $table");
            $columns = [];
            while ($col = mysqli_fetch_assoc($columns_res)) {
                $columns[] = $col['Field'];
            }
            $columns_line = implode(', ', $columns);

            // Kreiraj .txt datoteku
            $filename = "$dir/{$table}_" . date("Ymd_His") . ".txt";
            $fp = fopen($filename, 'w');
            if (!$fp) {
                echo "<p>Ne može se otvoriti $filename za pisanje.</p>";
                continue;
            }

            // Formatiraj podatke iz tablice kao INSERT INTO ... VALUES ...
            while ($row = mysqli_fetch_assoc($r2)) {
                $values = [];
                foreach ($columns as $col) {
                    $values[] = "'" . addslashes($row[$col]) . "'";
                }
                $line = "INSERT INTO $table ($columns_line) VALUES (" . implode(', ', $values) . ");\n";
                fwrite($fp, $line);
            }
            fclose($fp);

            // Komprimiraj txt datoteku
            $gz_file = $filename . ".gz";
            $fp_in = fopen($filename, 'r');
            $fp_out = gzopen($gz_file, 'w9');
            while (!feof($fp_in)) {
                gzwrite($fp_out, fread($fp_in, 1024));
            }
            fclose($fp_in);
            gzclose($fp_out);

            echo "<p>Tablica '$table' uspješno backupirana.</p>";
        }
    }
} else {
    echo "<p>Baza $db_name ne sadrži tablice.</p>";
}
?>
