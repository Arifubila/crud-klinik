<?php
include 'config/koneksi.php'; // Koneksi database

$id = $_GET['id']; // Ambil ID dari URL

// Query untuk menghapus data berdasarkan ID
$sql = "DELETE FROM dosen WHERE id_dosen = ?";
$stmt = $conn->prepare($sql); // Persiapkan query
$stmt->bind_param("i", $id);  // Ikat parameter ID bertipe integer
$stmt->execute();             // Eksekusi query

// Tampilkan hasilnya
if ($stmt->affected_rows > 0) {
    echo "Data berhasil dihapus.";
} else {
    echo "Gagal menghapus data.";
}
?>
<br><a href="daftar_dosen.php">← Kembali ke Daftar</a>

<a href="daftar_dosen.php">Kembali ke Daftar</a>
