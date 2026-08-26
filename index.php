```php
<?php
include 'koneksi.php';

// Query untuk mengambil semua data dari tabel pasien
$sql = "SELECT * FROM pasien ORDER BY idpasien DESC";
$result = $conn->query($sql);

// Hitung jumlah pasien
$totalPasien = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Daftar Pasien | Klinik</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 1250px;
            margin: 0 auto;
            padding: 35px 25px;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .header-left h1 {
            font-size: 28px;
            color: #111827;
            margin-bottom: 6px;
        }

        .header-left p {
            color: #6b7280;
            font-size: 14px;
        }

        .btn-tambah {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.18);
        }

        .btn-tambah:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        /* Summary Card */
        .summary-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
            width: fit-content;
            min-width: 220px;
        }

        .summary-icon {
            width: 48px;
            height: 48px;
            background: #eff6ff;
            color: #2563eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .summary-info span {
            display: block;
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 4px;
        }

        .summary-info strong {
            font-size: 24px;
            color: #111827;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            font-size: 18px;
            color: #111827;
        }

        .table-header span {
            color: #6b7280;
            font-size: 13px;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th {
            background: #f9fafb;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 15px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 16px;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f8fafc;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .number {
            color: #9ca3af;
            font-weight: 500;
        }

        .norm {
            font-weight: 600;
            color: #2563eb;
        }

        .nama {
            font-weight: 600;
            color: #111827;
        }

        /* Gender Badge */
        .gender {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .gender-laki {
            background: #eff6ff;
            color: #2563eb;
        }

        .gender-perempuan {
            background: #fdf2f8;
            color: #db2777;
        }

        /* Action */
        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-edit,
        .btn-hapus {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 7px 11px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-edit {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit:hover {
            background: #dbeafe;
        }

        .btn-hapus {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-hapus:hover {
            background: #fee2e2;
        }

        /* Empty State */
        .empty {
            text-align: center;
            padding: 50px 20px;
            color: #9ca3af;
        }

        .empty-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .empty p {
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 25px 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-tambah {
                width: 100%;
                justify-content: center;
            }

            .summary-card {
                width: 100%;
            }

            .header-left h1 {
                font-size: 24px;
            }

            .table-header {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="page-header">
        <div class="header-left">
            <h1>Daftar Pasien</h1>
            <p>Kelola dan lihat informasi data pasien klinik.</p>
        </div>

        <a href="tambah_pasien.php" class="btn-tambah">
            <span>＋</span>
            Tambah Pasien
        </a>
    </div>

    <!-- Summary -->
    <div class="summary-card">
        <div class="summary-icon">
            👤
        </div>

        <div class="summary-info">
            <span>Total Pasien</span>
            <strong><?= $totalPasien ?></strong>
        </div>
    </div>

    <!-- Table -->
    <div class="table-card">

        <div class="table-header">
            <div>
                <h2>Data Pasien</h2>
                <span>Daftar seluruh pasien yang terdaftar</span>
            </div>
        </div>

        <div class="table-wrapper">

            <?php if ($result->num_rows > 0): ?>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. RM</th>
                        <th>Nama Pasien</th>
                        <th>Jenis Kelamin</th>
                        <th>No. HP</th>
                        <th>Tempat, Tanggal Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $no = 1;

                while ($row = $result->fetch_assoc()):
                ?>

                    <tr>

                        <td class="number">
                            <?= $no++ ?>
                        </td>

                        <td class="norm">
                            <?= htmlspecialchars($row['norm']) ?>
                        </td>

                        <td class="nama">
                            <?= htmlspecialchars($row['namapasien']) ?>
                        </td>

                        <td>
                            <?php
                            $gender = strtolower(trim($row['jeniskelamin']));

                            if (
                                $gender === 'laki-laki' ||
                                $gender === 'laki laki' ||
                                $gender === 'l'
                            ):
                            ?>

                                <span class="gender gender-laki">
                                    Laki-laki
                                </span>

                            <?php else: ?>

                                <span class="gender gender-perempuan">
                                    Perempuan
                                </span>

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['notelpon']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['alamat']) ?>,
                            <?= date('d-m-Y', strtotime($row['tgllahir'])) ?>
                        </td>

                        <td>

                            <div class="actions">

                                <a
                                    href="edit_pasien.php?id=<?= $row['idpasien'] ?>"
                                    class="btn-edit"
                                >
                                    ✏ Edit
                                </a>

                                <a
                                    href="hapus_pasien.php?id=<?= $row['idpasien'] ?>"
                                    class="btn-hapus"
                                    onclick="return confirm('Yakin ingin menghapus data pasien ini?')"
                                >
                                    🗑 Hapus
                                </a>

                            </div>

                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>
            </table>

            <?php else: ?>

                <div class="empty">
                    <div class="empty-icon">👤</div>
                    <p>Belum ada data pasien.</p>
                </div>

            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>
```
