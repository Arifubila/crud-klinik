<?php
include 'koneksi.php';

$id = $_GET['id'];

$delete = "DELETE FROM pasien WHERE idPasien = '$id'";
if ($conn->query($delete)) {
    header("Location: index.php");
    exit();
} else {
    echo "Gagal menghapus data!";
}
?>
