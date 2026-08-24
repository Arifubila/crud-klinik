<?php
include 'koneksi.php';

$id = $_GET['id'];
$query = "SELECT * FROM pasien WHERE idPasien = '$id'";
$result = $conn->query($query);
$data = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namapasien = $_POST['namapasien'];
    $jeniskelamin = $_POST['jeniskelamin'];
    $nohp = $_POST['nohp'];
    $tempatlahir = $_POST['tempatlahir'];
    $tgllahir = $_POST['tgllahir']; 

    $update = "UPDATE pasien SET 
        namapasien = '$namapasien',
        jeniskelamin = '$jeniskelamin',
        nohp = '$nohp',
        tempatlahir = '$tempatlahir',
        tgllahir = '$tgllahir'
        WHERE idPasien = '$id'";

    if ($conn->query($update)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal mengupdate data!";
    }
}
?>

<h2>Edit Pasien</h2>
<form method="POST">
    Nama: <input type="text" name="namapasien" value="<?= $data['namapasien'] ?>"><br>
    Jenis Kelamin:
    <select name="jeniskelamin">
        <option value="L" <?= $data['jeniskelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
        <option value="P" <?= $data['jeniskelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
    </select><br>
    No HP: <input type="text" name="nohp" value="<?= $data['nohp'] ?>"><br>
    Tempat Lahir: <input type="text" name="tempatlahir" value="<?= $data['tempatlahir'] ?>"><br>
    Tanggal Lahir: <input type="date" name="tgllahir" value="<?= $data['tgllahir'] ?>"><br>
    <input type="submit" value="Simpan Perubahan">
</form>
