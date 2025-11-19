<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('DiplomskiRadovi.php');

function fetchPage($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $html = curl_exec($ch);

    if ($html === false) {
        echo 'Curl error: ' . curl_error($ch) . "<br>";
    }

    curl_close($ch);
    return $html;
}

function parsePageWithRegex($html) {
    $results = [];

    preg_match_all('/<h2 class="blog-shortcode-post-title entry-title">\s*<a href="([^"]+)"[^>]*>(.*?)<\/a>\s*<\/h2>/si', $html, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $link_rada = $match[1];
        $naziv_rada = strip_tags($match[2]);

        $naziv_rada = html_entity_decode($naziv_rada);

        $results[] = [
            'naziv_rada' => $naziv_rada,
            'link_rada' => $link_rada,
            'oib_tvrtke' => null,
            'tekst_rada' => null,
        ];
    }

    return $results;
}

function fetchDetailsFromLink($url) {
    $html = fetchPage($url);
    if (!$html) {
        return ['oib_tvrtke' => null, 'tekst_rada' => ''];
    }

    preg_match('/<div[^>]*class="fusion-image"[^>]*>.*?<img[^>]+src="([^"]+\d{11}[^"]+)"[^>]*>/is', $html, $imgMatch);
    $oib = null;
    if (!empty($imgMatch[1])) {
        preg_match('/\d{11}/', $imgMatch[1], $oibMatch);
        if (!empty($oibMatch[0])) {
            $oib = $oibMatch[0];
        }
    } else {
        preg_match('/<img[^>]+src="([^"]+\d{11}[^"]+)"[^>]*>/i', $html, $imgMatch);
        if (!empty($imgMatch[1])) {
            preg_match('/\d{11}/', $imgMatch[1], $oibMatch);
            $oib = $oibMatch[0] ?? null;
        }
    }

    preg_match('/<div[^>]*(fusion-post-content|post-content)[^>]*>(.*?)<\/div>/is', $html, $contentMatch);
    $tekst = '';
    if (!empty($contentMatch[2])) {
        $tekst = trim(strip_tags($contentMatch[2]));
    }
    $tekst = html_entity_decode($tekst);

    return ['oib_tvrtke' => $oib, 'tekst_rada' => $tekst];
}

$allRadovi = [];

for ($i = 1; $i <= 6; $i++) {
    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/$i/";
    $html = fetchPage($url);
    $radovi = parsePageWithRegex($html);

    foreach ($radovi as $radData) {
        $detalji = fetchDetailsFromLink($radData['link_rada']);
        $radData['oib_tvrtke'] = $detalji['oib_tvrtke'];
        $radData['tekst_rada'] = $detalji['tekst_rada'];

        $rad = new DiplomskiRadovi();
        $rad->create($radData);
        $rad->save();

        $allRadovi[] = $radData;
    }
}

$sviRadovi = DiplomskiRadovi::read();

foreach ($sviRadovi as $rad) {
    echo "<h3>" . htmlspecialchars($rad['naziv_rada'] ?? '') . "</h3>";
    echo "<p>Link: <a href='" . htmlspecialchars($rad['link_rada'] ?? '') . "'>" . htmlspecialchars($rad['link_rada'] ?? '') . "</a></p>";
    echo "<p>OIB tvrtke: " . htmlspecialchars($rad['oib_tvrtke'] ?? '') . "</p>";
    echo "<p>Tekst rada: " . nl2br(htmlspecialchars($rad['tekst_rada'] ?? '')) . "</p>";
    echo "<hr>";
}

?>
