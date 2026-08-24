<?php
include 'koneksi.php'; // Memasukkan file koneksi

// Query untuk mengambil semua data dari tabel pasien
$sql = "SELECT * FROM pasien ORDER BY idpasien DESC";
$result = $conn->query($sql); // Menjalankan query dan menyimpan hasilnya dalam $result
?>

<h2>Daftar Pasien</h2>
<a href="tambah_pasien.php">+ Tambah Pasien</a><br><br>

<!-- Menampilkan data dalam tabel HTML -->
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No</th>
        <th>No RM</th>
        <th>Nama Pasien</th>
        <th>Jenis Kelamin</th>
        <th>No HP</th>
        <th>Tempat, Tanggal Lahir</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    while ($row = $result->fetch_assoc()):
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['norm']) ?></td>
        <td><?= htmlspecialchars($row['namapasien']) ?></td>
        <td><?= htmlspecialchars($row['jeniskelamin']) ?></td>
        <td><?= htmlspecialchars($row['notelpon']) ?></td>
        <td><?= htmlspecialchars($row['alamat']) . ', ' . date('d-m-Y', strtotime($row['tgllahir'])) ?></td>
        <td>
            <a href="edit_pasien.php?id=<?= $row['idpasien'] ?>">Edit</a> | 
            <a href="hapus_pasien.php?id=<?= $row['idpasien'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
