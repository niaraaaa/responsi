<?php
$host = 'localhost';
$dbname = 'responsi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['Nama'])) {
        $Nama = $_GET['Nama'];

        // Ambil data dari database
        $stmt = $pdo->prepare("SELECT * FROM kritikdansaran WHERE Nama = :Nama LIMIT 1");
        $stmt->bindParam(':Nama', $Nama);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            die("Data untuk Nama tidak ditemukan!");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_Nama = trim($_POST['Nama']);
            $Email = trim($_POST['Email']);
            $Kritik_danSaran = trim($_POST['Kritik_danSaran']);

            // Perbarui data di database
            $update_sql = "UPDATE kritikdansaran SET Nama = :new_Nama, Email = :Email, Kritik_danSaran = :Kritik_danSaran WHERE Nama = :Nama";
            $stmt = $pdo->prepare($update_sql);
            $stmt->bindParam(':new_Nama', $new_Nama);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Kritik_danSaran', $Kritik_danSaran);
            $stmt->bindParam(':Nama', $Nama);

            if ($stmt->execute()) {
                header("Location: table.php?update=success");
                exit();
            } else {
                $error_message = "Gagal memperbarui data!";
            }
        }
    } else {
        die("Parameter 'Nama' tidak ditemukan dalam URL!");
    }
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kritik dan Saran</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            background: url('icon/background.jpg') no-repeat center center fixed;
            background-size: cover;
            /* Membuat gambar menutupi seluruh halaman */
        }
        .container mt-5{
            background-color: rgba(216, 202, 159, 0.8)
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Kritik dan Saran</h2>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="Nama" class="form-label">Nama</label>
                <input type="text" id="Nama" name="Nama" class="form-control" value="<?= htmlspecialchars($data['Nama']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="Email" class="form-label">Email</label>
                <input type="email" id="Email" name="Email" class="form-control" value="<?= htmlspecialchars($data['Email']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="Kritik_danSaran" class="form-label">Kritik dan Saran</label>
                <textarea id="Kritik_danSaran" name="Kritik_danSaran" class="form-control" rows="4" required><?= htmlspecialchars($data['Kritik_danSaran']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="input.php" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
