<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['json_file'])) {
    $kategori = strtolower(trim($_POST['kategori'])); // Boşlukları temizle, küçük harfe çevir
    $kategori = preg_replace('/[^a-z0-9-_]/', '', $kategori); // Güvenlik için sadece harf, rakam, tire, alt tire izin ver

    if (empty($kategori)) {
        echo "<div class='error'>Geçersiz kategori adı.</div>";
        exit;
    }

    $uploadDir = "uploads/";
    if (!is_dir($uploadDir))
        mkdir($uploadDir); // uploads klasörü yoksa oluştur

    $fileName = basename($_FILES['json_file']['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['json_file']['tmp_name'], $uploadFile)) {
        // output/kategori klasörünü kontrol et, yoksa oluştur
        $kategoriDir = "output/" . $kategori;
        if (!is_dir($kategoriDir)) {
            mkdir($kategoriDir, 0755, true);
        }

        // JSON dosyasını işleme sayfasına yönlendir
        header("Location: process_json.php?file=" . urlencode($fileName) . "&kategori=" . urlencode($kategori));
        exit;
    } else {
        echo "<div class='error'>Dosya yükleme başarısız.</div>";
    }
}

// Kategorileri al (output klasöründeki alt klasörler)
$mevcutKategoriler = array_map('basename', glob("output/*", GLOB_ONLYDIR));
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">



    <meta charset="UTF-8">
    <title>JSON Yükle</title>
    <style>
        /* style.css */

        :root {
            --green: #33cc33;
            --green-dark: #28a428;
            --background: #ffffff;
            --text: #222;
            --border: #e0e0e0;
            --input-bg: #f9f9f9;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--background);
            color: var(--text);

        }

        h1,
        h2 {
            color: black;
            margin-bottom: 20px;
            text-align: center;
        }

        form {
            background-color: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 16px;
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        form:hover {
            transform: scale(1.01);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background-color: #fff;
            margin-bottom: 20px;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="file"]:focus {
            border-color: var(--green);
            outline: none;
        }

        input[type="submit"] {
            background-color: var(--green);
            color: #fff;
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: var(--green-dark);
            transform: scale(1.02);
        }

        hr {
            margin: 40px auto;
            max-width: 500px;
            border: none;
            height: 1px;
            background-color: #ddd;
        }

        ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        li {
            margin: 10px 0;
        }

        a {
            text-decoration: none;
            color: var(--green);
            font-weight: 600;
            transition: color 0.3s ease;
        }

        a:hover {
            color: var(--green-dark);
            text-decoration: underline;
        }

        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            body {
                padding: 20px;
            }

            form {
                padding: 20px;
            }

            h1,
            h2 {
                font-size: 1.5rem;
            }
        }

        /* Hover ile dropdown açma */
        .nav-item.dropdown:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

        .dropdown-menu {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .dropdown-item:hover {
            background-color: #33cc33;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #228B22;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">İşletme Kategorileyici

            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarGreen"
                aria-controls="navbarGreen" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarGreen">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Anasayfa</a>
                    </li>

                    <!-- Dinamik Dropdown -->
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

                    <li class="nav-item">
                        <a class="nav-link" href="#">Yükle</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <br><br><br><br><br>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <h1>İşletme Verisi Yükle</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="kategori">Kategori Gir:</label>
        <input type="text" name="kategori" required placeholder="örnek: manav, kafe, kuaför">
        <br><br>
        <input type="file" name="json_file" accept=".json" required>
        <br><br>
        <input type="submit" value="Yükle">
    </form>

    <hr>


</body>

</html>