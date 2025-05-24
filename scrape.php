<?php
// scrape.php
header('Content-Type: application/json');

// Contoh scraping sederhana dari situs film (gunakan URL sesuai target sebenarnya)
$url = 'https://contoh-situs-lk21.com/terbaru';

// Fungsi ambil konten HTML
function getHTML($url) {
    $options = [
        "http" => ["header" => "User-Agent: PHP"]
    ];
    $context = stream_context_create($options);
    return file_get_contents($url, false, $context);
}

// Fungsi parse HTML dan ambil data film
function scrapeFilms($html) {
    $films = [];
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    // Sesuaikan selector dengan struktur situs target
    $nodes = $xpath->query("//div[contains(@class,'film-item')]");
    foreach ($nodes as $node) {
        $title = $xpath->evaluate(".//h2", $node)->item(0)?->nodeValue ?? 'Tanpa Judul';
        $poster = $xpath->evaluate(".//img/@src", $node)->item(0)?->nodeValue ?? '';
        $year = $xpath->evaluate(".//span[contains(@class,'year')]", $node)->item(0)?->nodeValue ?? '';

        $films[] = [
            'title' => trim($title),
            'poster' => $poster,
            'year' => trim($year)
        ];
    }
    return $films;
}

$html = getHTML($url);
$filmList = scrapeFilms($html);
echo json_encode($filmList, JSON_PRETTY_PRINT);
