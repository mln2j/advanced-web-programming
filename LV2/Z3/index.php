<?php
// Učitaj XML datoteku
$xml = simplexml_load_file('../LV2.xml');

foreach ($xml->record as $record) {
    echo '<div style="border:1px solid #ccc; margin:1rem; padding:1rem;">';

    // Naslov: ime + prezime
    $ime = htmlspecialchars((string)$record->ime);
    $prezime = htmlspecialchars((string)$record->prezime);
    echo "<h2>$ime $prezime</h2>";

    // E-mail
    $email = htmlspecialchars((string)$record->email);
    echo "<strong>Email:</strong> $email<br>";

    // Spol
    $spol = htmlspecialchars((string)$record->spol);
    echo "<strong>Spol:</strong> $spol<br>";

    // Slika
    if (!empty($record->slika)) {
        $slika = htmlspecialchars((string)$record->slika);
        echo "<img src=\"$slika\" alt=\"$ime $prezime - slika\" style=\"border:0; margin:0.5rem;\" /><br>";
    }

    // Životopis
    echo '<strong>Životopis:</strong><br>';
    echo '<div style="margin:0.5rem 0; padding:0.5rem;">' . nl2br(htmlspecialchars((string)$record->zivotopis)) . '</div>';

    echo '</div>';
}
?>
