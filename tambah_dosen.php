
<?php
// Menghubungkan ke database
include 'config/koneksi.php';

// Mengecek apakah form dikirim (submit) dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menangkap data dari form input
    $idpasien       = $_POST['idPasien'];
    $norm       = $_POST['norm'];
    $namapasien     = $_POST['namapasien'];
    $jeniskelamin   = $_POST['jeniskelamin'];
    $nohp =password_hash (password: $_POST['nohp'], PASSWORD_DEFAULT); // Mengenskripsi password
    $tgllahir     = $_POST['tgllahir'];
    $tempatlahir     = $_POST['tempatlahir'];

    // Query untuk menyimpan data ke dalam tabel dosen
    $sql = "INSERT INTO dosen (idpasien, norm, namapasien, jeniskelamin, notelpon, tgllahir, tempatlahir)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql); // Mempersiapkan statement SQL untuk dieksekusi
    $stmt->bind_param("sssssss", $nip, $nama, $email, $username, $password, $no_hp, $alamat); 
    // Mengikat variabel ke placeholder (tanda ?) dalam query, dengan tipe string (s)

    $stmt->execute(); // Menjalankan statement yang telah disiapkan

    // Mengecek apakah ada data yang berhasil dimasukkan
    if ($stmt->affected_rows > 0) {
        echo "Data dosen berhasil ditambahkan.";
    } else {
        echo "Gagal menambahkan data.";
    }

    $stmt->close();  // Menutup statement
    $conn->close();  // Menutup koneksi database
}
?>

<!-- Form input data dosen -->
<form method="POST" action="">
    idpasien: <input type="text" name="idPasien" required><br>
    norm: <input type="text" name="norm" required><br>
    namapasien: <input type="email" name="namapasien"><br>
    jeniskelamin: <input type="text" name="jeniskelamin" required><br>
    nohp: <input type="password" name="nohp" required><br>
    tgllahir: <input type="text" name="tgllahir"><br>
    tempatlahir: <textarea name="tempatlahir"></textarea><br>
    <input type="submit" value="Simpan">
</form>

<a href="daftar_dosen.php">Kembali ke Daftar</a>