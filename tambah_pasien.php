<?php
include 'koneksi.php'; // koneksi ke database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idpasien     = $_POST['idPasien'];
    $norm         = $_POST['norm'];
    $namapasien   = $_POST['namapasien'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $notelpon        = $_POST['notelpon'];
    $tgllahir     = $_POST['tgllahir'];
    $tempatlahir  = $_POST['tempatlahir'];

    $sql = "INSERT INTO pasien (idpasien, norm, namapasien, jeniskelamin, notelpon, tgllahir, tempatlahir) 
            VALUES ('$idpasien', '$norm', '$namapasien', '$jeniskelamin', '$notelpon', '$tgllahir', '$tempatlahir')";

$norm = $_POST['norm']; // atau sesuai variabel kamu

$check_sql = "SELECT * FROM pasien WHERE norm = '$norm'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    echo "<script>alert('Nomor rekam medis $norm sudah ada. Silakan gunakan nomor lain.'); window.location.href='tambah_pasien.php';</script>";
} else {
    $sql = "INSERT INTO pasien (norm, nama, ...) VALUES ('$norm', '$nama', ...)";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data pasien berhasil ditambahkan.'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

}
?>


<h2>Tambah Data Pasien</h2>
<a href="index.php"><< Kembali ke Daftar Pasien</a><br><br>

<form method="POST" action="">
    <label for="idPasien">ID Pasien:</label><br>
    <input type="text" name="idPasien" required><br><br>

    <label for="norm">No. RM:</label><br>
    <input type="text" name="norm" required><br><br>


    <label for="namapasien">Nama Pasien:</label><br>
    <input type="text" name="namapasien" required><br><br>

    <label for="jeniskelamin">Jenis Kelamin:</label><br>
    <select name="jeniskelamin" required>
        <option value="">-- Pilih --</option>
        <option value="Laki-laki">Laki-laki</option>
        <option value="Perempuan">Perempuan</option>
    </select><br><br>

    <label for="notelpon">notelpon:</label><br>
    <input type="number" name="notelpon" required><br><br>

    <label for="tgllahir">tgllahir:</label><br>
    <input type="date" name="tgllahir" required><br><br>

    <label for="tempatlahir">tempatlahir:</label><br>
    <input type="text" name="tempatlahir" required><br><br>

    <input type="submit" value="Simpan">
</form>
