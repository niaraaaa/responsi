<?php
$host = 'localhost';
$dbname = 'responsi';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $pdo->query("SELECT Nama, Email, Kritik_danSaran FROM kritikdansaran");
    $data = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}

// Menghapus data berdasarkan ID
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];
    $delete_sql = "DELETE FROM kritikdansaran WHERE Nama = :Nama LIMIT 1";
    $stmt = $pdo->prepare($delete_sql);
    $stmt->bindParam(':Nama', $delete_id);

    if ($stmt->execute()) {
        $message = "<div style='color: green; margin: 10px 0;'>Data berhasil dihapus!</div>";
    } else {
        $message = "<div style='color: red; margin: 10px 0;'>Error menghapus data: " . $stmt->errorInfo()[2] . "</div>";
    }
    echo $message;
}

// Menambah data ke database
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $kritik = $_POST['kritik'];

    $insert_sql = "INSERT INTO kritikdansaran (Nama, Email, Kritik_danSaran) VALUES (:Nama, :Email, :Kritik_danSaran)";
    $stmt_insert = $pdo->prepare($insert_sql);
    $stmt_insert->bindParam(':Nama', $nama);
    $stmt_insert->bindParam(':Email', $email);
    $stmt_insert->bindParam(':Kritik_danSaran', $kritik);

    if ($stmt_insert->execute()) {
        $message = "<div style='color: green;'>Data berhasil ditambahkan!</div>";
    } else {
        $message = "<div style='color: red;'>Gagal menambahkan data!</div>";
    }
    echo $message;
}
?>

<head>
    <style>
        table {
            width: 1480px;
            border-collapse: collapse;
            margin-left: 20px;
            margin-right: 20px;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
        }
        th {
            background-color: rgba(216, 202, 159, 0.8);
        }
        td {
            background-color: #f2f2f2;
        }
        button {
            background-color: #ff6b6b;
            padding: 10px;
            border: none;
            color: white;
        }
        button:hover {
            cursor: pointer;
        }
        .btn-tambah {
            background-color: rgba(103, 97, 78, 0.8);
            color: white;
            padding: 10px;
            text-decoration: none;
        }
        .btn-tambah:hover {
            background-color: #45a049;
        }
        form input[type="text"], form input[type="email"], form textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }
        form button {
            background-color:rgb(131, 121, 79);
            color: white;
        }
        body {
            background: url('icon/background.jpg') no-repeat center center fixed;
            background-size: cover;
            /* Membuat gambar menutupi seluruh halaman */
        }
    </style>
</head>

<body>

    <h2>Kritik dan Saran</h2>

    <!-- Tabel Data -->
    <div id="table-container">
        <table>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Kritik dan Saran</th>
                <th>Aksi</th>
            </tr>

            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Nama']) ?></td>
                    <td><?= htmlspecialchars($row['Email']) ?></td>
                    <td><?= htmlspecialchars($row['Kritik_danSaran']) ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="edit.php?Nama=<?= urlencode($row['Nama']) ?>" style="color: blue; text-decoration: none;">Edit</a>

                        <!-- Delete Button -->
                        <form method="GET" style="display:inline;" onsubmit="return confirm('Hapus data: <?= htmlspecialchars($row['Nama']) ?>?');">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['Nama']) ?>">
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>

        <!-- Button untuk menambah data (letakkan di bawah tabel) -->
        <a href="#" onclick="toggleForm()" class="btn-tambah" style="display: inline-block; margin-top: 30px;">Tambah Kritik dan Saran</a>

        <!-- Form untuk menambah data (disembunyikan awal) -->
        <div id="form-container" style="display:none; margin-top: 20px; padding: 10px; border: 1px solid rgba(243, 231, 196, 0.8);">
            <h3>Form Kritik dan Saran</h3>
            <form method="POST">
                <input type="text" name="nama" placeholder="Nama" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="kritik" placeholder="Kritik dan Saran" rows="4" required></textarea>
                <br>
                <button type="submit" name="tambah">Tambah</button>
            </form>
        </div>

    </div>
    <script>
        function toggleForm() {
            var formContainer = document.getElementById('form-container');
            if (formContainer.style.display === 'none') {
                formContainer.style.display = 'block';
            } else {
                formContainer.style.display = 'none';
            }
        }
    </script>

</body>

