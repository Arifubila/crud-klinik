<?php
include 'config/koneksi.php'; // Koneksi database

$id = $_GET['id']; // Menangkap ID dari URL

// Ambil data dosen lama berdasarkan ID
$sql = "SELECT * FROM dosen WHERE id_dosen = ?";
$stmt = $conn->prepare($sql); // Persiapkan query
$stmt->bind_param("i", $id);  // Ikat parameter ID (tipe integer)
$stmt->execute();             // Eksekusi statement
$result = $stmt->get_result(); // Ambil hasilnya
$dosen = $result->fetch_assoc(); // Ambil data sebagai array asosiatif

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nip      = $_POST['nip'];
    $nama     = $_POST['nama_dosen'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $no_hp    = $_POST['no_hp'];
    $alamat   = $_POST['alamat'];

    // Update data ke database
    $sql = "UPDATE dosen SET nip=?, nama_dosen=?, email=?, username=?, no_hp=?, alamat=? WHERE id_dosen=?";
    $stmt = $conn->prepare($sql); // Siapkan query
    $stmt->bind_param("ssssssi", $nip, $nama, $email, $username, $no_hp, $alamat, $id); // Ikat parameter
    $stmt->execute(); // Jalankan query

    // Cek apakah data berhasil diubah
    if ($stmt->affected_rows > 0) {
        echo "Data berhasil diupdate.";
    } else {
        echo "Tidak ada perubahan.";
    }
}
?>

<!-- Form Edit Dosen -->
<h2>Edit Dosen</h2>
<form method="POST" action="">
    NIP: <input type="text" name="nip" value="<?= $dosen['nip'] ?>" required><br>
    Nama: <input type="text" name="nama_dosen" value="<?= $dosen['nama_dosen'] ?>" required><br>
    Email: <input type="email" name="email" value="<?= $dosen['email'] ?>"><br>
    Username: <input type="text" name="username" value="<?= $dosen['username'] ?>" required><br>
    No HP: <input type="text" name="no_hp" value="<?= $dosen['no_hp'] ?>"><br>
    Alamat: <textarea name="alamat"><?= $dosen['alamat'] ?></textarea><br>
    <input type="submit" value="Update">
</form>

<a href="daftar_dosen.php">Kembali ke Daftar</a>
