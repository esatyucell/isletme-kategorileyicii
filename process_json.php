<?php
if (!isset($_GET['file']) || !isset($_GET['kategori'])) {
    die("Dosya veya kategori bilgisi eksik.");
}

$kategori = preg_replace('/[^a-zA-Z0-9-_]/', '', $_GET['kategori']); // Güvenli kategori ismi
$uploadFile = 'uploads/' . basename($_GET['file']); // Güvenli dosya ismi
$outputDir = "output/$kategori";

// Klasör yoksa oluştur
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0777, true);
}

// JSON dosyasını oku
if (!file_exists($uploadFile)) {
    die("Yüklenen dosya bulunamadı.");
}

$jsonData = file_get_contents($uploadFile);
$isletmeler = json_decode($jsonData, true);

// JSON verisi kontrolü
if (!$isletmeler || !is_array($isletmeler)) {
    die("Geçersiz JSON verisi.");
}

// index.php içeriği başlangıcı
$indexContent = "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$kategori Kategorisi</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; }
        .card { border-radius: 10px; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .card img { max-width: 100%; height: auto; border-radius: 10px; }
        .card-body { padding: 15px; }
        .card-title { font-size: 1.25rem; color: #333; }
        .card-text { font-size: 1rem; color: #555; }
        a { text-decoration: none; color: #007bff; }
        a:hover { color: #0056b3; }
    </style>
</head>
<body>
<div class='container py-4'>
    <h1 class='text-center mb-4'>$kategori Kategorisindeki İşletmeler</h1>
    <div class='row'>";

// İşletmeler üzerinde dön
foreach ($isletmeler as $isletme) {
    $id = uniqid();
    $title = isset($isletme['title']) ? htmlspecialchars($isletme['title']) : 'İsimsiz';
    $phone = isset($isletme['phone']) ? htmlspecialchars($isletme['phone']) : 'Belirtilmemiş';
    $address = isset($isletme['address']) ? htmlspecialchars($isletme['address']) : 'Adres yok';
    $lat = $isletme['location']['lat'] ?? '';
    $lng = $isletme['location']['lng'] ?? '';
    $mapUrl = ($lat && $lng) ? "https://www.google.com/maps?q=$lat,$lng" : '#';
    $image = $isletme['imageUrl'] ?? '';

    // Her işletme için bireysel HTML sayfası
    $html = "<!DOCTYPE html>
<html lang='tr'>
<head>
    <meta charset='UTF-8'>
    <title>$title</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
<div class='container py-4'>
    <div class='card'>
        <div class='card-body'>
            <h2 class='card-title'>$title</h2>
            <p class='card-text'><strong>Telefon:</strong> $phone</p>
            <p class='card-text'><strong>Adres:</strong> $address</p>
            <p><a href='$mapUrl' target='_blank'>📍 Haritada Gör</a></p>";
    if ($image) {
        $html .= "<img src='$image' alt='$title' class='img-fluid rounded'>";
    }
    $html .= "</div></div></div></body></html>";

    // HTML dosyasını yaz
    file_put_contents("$outputDir/$id.html", $html);

    // index.php içeriğine kart ekle
    $indexContent .= "<div class='col-md-4 col-sm-6'>
        <a href='$id.html'>
            <div class='card'>
                " . ($image ? "<img src='$image' class='card-img-top' alt='$title'>" : "") . "
                <div class='card-body'>
                    <h5 class='card-title'>$title</h5>
                    <p class='card-text'>$address</p>
                </div>
            </div>
        </a>
    </div>";
}

// index.php kapanışı
$indexContent .= "</div></div></body></html>";

// index.php dosyasını oluştur
file_put_contents("$outputDir/index.php", $indexContent);

// Kullanıcıya bağlantı ver
echo "
<div style='
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #ffffff;
    font-family: Arial, sans-serif;
'>
    <div style='
        background-color: #f0fdf4;
        border: 2px solid #228B22;
        border-radius: 12px;
        padding: 30px 40px;
        text-align: center;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
    '>
        <h2 style='color: #228B22; margin-bottom: 20px;'>✅ Veriler Başarıyla İşlendi</h2>
        <p style='color: #444; font-size: 16px; margin-bottom: 30px;'>İşlem tamamlandı. Aşağıdaki seçeneklerden birini kullanabilirsiniz.</p>
        <div style='display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;'>
            <a href='output/$kategori/index.php' style='
                background-color: #228B22;
                color: #fff;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 6px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            ' onmouseover=\"this.style.backgroundColor='#1a6d1a'\" onmouseout=\"this.style.backgroundColor='#228B22'\">
                📂 Kategoriyi Gör
            </a>
            <a href='/index.php' style='
                background-color: transparent;
                border: 2px solid #228B22;
                color: #228B22;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 6px;
                font-weight: bold;
                transition: all 0.3s ease;
            ' onmouseover=\"this.style.backgroundColor='#228B22'; this.style.color='#fff'\" onmouseout=\"this.style.backgroundColor='transparent'; this.style.color='#228B22'\">
                🏠 Anasayfaya Dön
            </a>
        </div>
    </div>
</div>";
?>