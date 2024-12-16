<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil nilai yang diinputkan dari form dan mencegah SQL Injection
$Nama = mysqli_real_escape_string($conn, $_POST['Nama']);
$Email = mysqli_real_escape_string($conn, $_POST['Email']);
$Kritik_danSaran = mysqli_real_escape_string($conn, $_POST['Kritik_danSaran']);

// Query untuk memasukkan data ke tabel
$sql = "INSERT INTO penduduk (Nama, Email, Kritik_danSaran) 
        VALUES ('$Nama', $Email, $Kritik_danSaran)";

// Eksekusi query<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsi";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan data POST tersedia
if (isset($_POST['Nama'], $_POST['Email'], $_POST['Kritik_danSaran'])) {
    $Nama = $_POST['Nama'];
    $Email = $_POST['Email'];
    $Kritik_danSaran = $_POST['Kritik_danSaran'];

    // Menggunakan prepared statements untuk menghindari SQL Injection
    $stmt = $conn->prepare("INSERT INTO penduduk (Nama, Email, Kritik_danSaran) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $Nama, $Email, $Kritik_danSaran);

    if ($stmt->execute()) {
        echo "Data berhasil ditambahkan";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Menutup statement
    $stmt->close();
} else {
    echo "Form input tidak lengkap!";
}

// Menutup koneksi
$conn->close();
?>
