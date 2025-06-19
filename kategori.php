<?php
$kategori = $_GET['kategori'] ?? '';
$klasorYolu = "output/$kategori/";
$dosyalar = [];

if (is_dir($klasorYolu)) {
    $dosyalar = array_diff(scandir($klasorYolu), ['.', '..']);
}

// Varsa kategorileri burada tanımla
$mevcutKategoriler = ['market', 'kafe', 'eczane']; // örnek
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Kategori: <?php echo htmlspecialchars($kategori); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .card {
            background-color: #fff;
            border: 1px solid #228B22;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(34, 139, 34, 0.15);
            width: 100%;
            min-height: 600px;
            /* daha uzun kart */
            display: flex;
            flex-direction: column;
            padding: 25px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 32px rgba(34, 139, 34, 0.2);
        }

        .img-fluid {
            width: 100%;
            height: 250px;
            /* daha büyük görsel */
            object-fit: cover;
            border-radius: 12px;
            border: 1px solid #ddd;
            margin-bottom: 16px;
            flex-shrink: 0;
        }

        .card-title {
            font-size: 22px;
            /* büyütüldü */
            font-weight: 800;
            color: #228B22;
            margin-bottom: 14px;
            border-left: 6px solid #228B22;
            padding-left: 14px;
            flex-shrink: 0;
        }

        .card-text {
            font-size: 16px;
            /* büyütüldü */
            line-height: 1.8;
            margin-bottom: 16px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 5;
            /* daha fazla satır göster */
            -webkit-box-orient: vertical;
            flex-grow: 1;
        }

        a {
            color: #228B22;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 16px;
        }

        a:hover {
            color: #145c14;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #228B22;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">İşletme Kategorileyici</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGreen"
                aria-controls="navbarGreen" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarGreen">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="index.php">Anasayfa</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Kategoriler
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="kategoriDropdown">
                            <?php foreach ($mevcutKategoriler as $kat): ?>
                                <li><a class="dropdown-item"
                                        href="kategori.php?kategori=<?= urlencode($kat) ?>"><?= ucfirst($kat) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#">Yükle</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4 text-center">Kategori: <?php echo htmlspecialchars(ucfirst($kategori)); ?></h1>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php
            foreach ($dosyalar as $dosya) {
                $dosyaYolu = $klasorYolu . $dosya;
                if (pathinfo($dosyaYolu, PATHINFO_EXTENSION) === 'html') {
                    $icerik = file_get_contents($dosyaYolu);
                    echo "<div class='col d-flex'><div class='card w-100'>{$icerik}</div></div>";
                }
            }
            ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>