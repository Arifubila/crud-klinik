<?php

// ======================================================
// KONEKSI DATABASE
// ======================================================
include 'config/koneksi.php';

// Pastikan koneksi menggunakan variabel $conn
if (!isset($conn) || !($conn instanceof mysqli)) {
    die("Koneksi database tidak tersedia. Periksa file config/koneksi.php");
}

// Aktifkan error MySQLi agar error database terlihat jelas
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$success = '';
$error = '';

// Nilai awal form
$idpasien     = '';
$norm         = '';
$namapasien   = '';
$jeniskelamin = '';
$nohp         = '';
$tgllahir     = '';
$tempatlahir  = '';


// ======================================================
// PROSES SIMPAN DATA
// ======================================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $idpasien     = trim($_POST['idpasien'] ?? '');
    $norm         = trim($_POST['norm'] ?? '');
    $namapasien   = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = trim($_POST['jeniskelamin'] ?? '');
    $nohp         = trim($_POST['nohp'] ?? '');
    $tgllahir     = trim($_POST['tgllahir'] ?? '');
    $tempatlahir  = trim($_POST['tempatlahir'] ?? '');


    // ==================================================
    // VALIDASI
    // ==================================================
    if (
        $idpasien === '' ||
        $norm === '' ||
        $namapasien === '' ||
        $jeniskelamin === '' ||
        $nohp === '' ||
        $tgllahir === '' ||
        $tempatlahir === ''
    ) {

        $error = "Semua data wajib diisi.";

    } else {

        try {

            // ==================================================
            // CEK ID PASIEN
            // ==================================================
            $cek = $conn->prepare(
                "SELECT idpasien FROM pasien WHERE idpasien = ?"
            );

            $cek->bind_param("s", $idpasien);
            $cek->execute();
            $hasilCek = $cek->get_result();

            if ($hasilCek->num_rows > 0) {

                $error = "ID pasien sudah digunakan.";

            } else {

                // ==================================================
                // CEK NOMOR REKAM MEDIS
                // ==================================================
                $cekNorm = $conn->prepare(
                    "SELECT norm FROM pasien WHERE norm = ?"
                );

                $cekNorm->bind_param("s", $norm);
                $cekNorm->execute();
                $hasilNorm = $cekNorm->get_result();

                if ($hasilNorm->num_rows > 0) {

                    $error = "Nomor Rekam Medis sudah digunakan.";

                } else {

                    // ==================================================
                    // INSERT DATA PASIEN
                    // ==================================================
                    $sql = "INSERT INTO pasien
                            (
                                idpasien,
                                norm,
                                namapasien,
                                jeniskelamin,
                                notelpon,
                                tgllahir,
                                tempatlahir
                            )
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $conn->prepare($sql);

                    $stmt->bind_param(
                        "sssssss",
                        $idpasien,
                        $norm,
                        $namapasien,
                        $jeniskelamin,
                        $nohp,
                        $tgllahir,
                        $tempatlahir
                    );

                    $stmt->execute();

                    $success = "Data pasien berhasil ditambahkan.";

                    // Kosongkan form
                    $idpasien     = '';
                    $norm         = '';
                    $namapasien   = '';
                    $jeniskelamin = '';
                    $nohp         = '';
                    $tgllahir     = '';
                    $tempatlahir  = '';

                    $stmt->close();
                }

                $cekNorm->close();
            }

            $cek->close();

        } catch (mysqli_sql_exception $e) {

            $error = "Database Error: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Tambah Pasien | Klinik</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {

            font-family:
                "Segoe UI",
                Arial,
                sans-serif;

            background: #f5f7fb;

            color: #1f2937;

            min-height: 100vh;
        }


        .container {

            max-width: 900px;

            margin: 0 auto;

            padding: 40px 20px;
        }


        /* =========================
           HEADER
        ========================= */

        .page-header {

            margin-bottom: 25px;
        }


        .page-header h1 {

            font-size: 28px;

            color: #111827;

            margin-bottom: 6px;
        }


        .page-header p {

            font-size: 14px;

            color: #6b7280;
        }


        /* =========================
           CARD
        ========================= */

        .form-card {

            background: #ffffff;

            border: 1px solid #e5e7eb;

            border-radius: 16px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.04);

            overflow: hidden;
        }


        .form-header {

            padding: 22px 25px;

            border-bottom:
                1px solid #e5e7eb;
        }


        .form-header h2 {

            font-size: 18px;

            color: #111827;

            margin-bottom: 5px;
        }


        .form-header p {

            color: #6b7280;

            font-size: 13px;
        }


        .form-body {

            padding: 25px;
        }


        /* =========================
           ALERT
        ========================= */

        .alert {

            padding: 13px 16px;

            border-radius: 9px;

            margin-bottom: 20px;

            font-size: 14px;
        }


        .alert-success {

            background: #ecfdf5;

            border:
                1px solid #a7f3d0;

            color: #047857;
        }


        .alert-error {

            background: #fef2f2;

            border:
                1px solid #fecaca;

            color: #dc2626;
        }


        /* =========================
           FORM GRID
        ========================= */

        .form-grid {

            display: grid;

            grid-template-columns:
                repeat(2, 1fr);

            gap: 20px;
        }


        .form-group {

            display: flex;

            flex-direction: column;
        }


        .form-group.full {

            grid-column: span 2;
        }


        label {

            font-size: 13px;

            font-weight: 600;

            color: #374151;

            margin-bottom: 8px;
        }


        .required {

            color: #ef4444;
        }


        input,
        select {

            width: 100%;

            padding: 12px 13px;

            border:
                1px solid #d1d5db;

            border-radius: 9px;

            background: #ffffff;

            color: #111827;

            font-family: inherit;

            font-size: 14px;

            outline: none;

            transition: 0.2s;
        }


        input::placeholder {

            color: #9ca3af;
        }


        input:focus,
        select:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37, 99, 235, 0.10);
        }


        /* =========================
           FOOTER
        ========================= */

        .form-footer {

            margin-top: 25px;

            padding-top: 20px;

            border-top:
                1px solid #e5e7eb;

            display: flex;

            justify-content:
                space-between;

            align-items: center;

            gap: 12px;
        }


        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 7px;

            padding: 11px 17px;

            border-radius: 9px;

            font-family: inherit;

            font-size: 14px;

            font-weight: 600;

            text-decoration: none;

            cursor: pointer;

            border: none;

            transition: 0.2s;
        }


        .btn-kembali {

            background: #f3f4f6;

            color: #374151;
        }


        .btn-kembali:hover {

            background: #e5e7eb;
        }


        .btn-simpan {

            background: #2563eb;

            color: white;

            box-shadow:
                0 4px 10px
                rgba(37, 99, 235, 0.18);
        }


        .btn-simpan:hover {

            background: #1d4ed8;

            transform:
                translateY(-1px);
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 650px) {

            .container {

                padding:
                    25px 15px;
            }


            .page-header h1 {

                font-size: 24px;
            }


            .form-grid {

                grid-template-columns: 1fr;
            }


            .form-group.full {

                grid-column: span 1;
            }


            .form-body {

                padding: 20px;
            }


            .form-footer {

                flex-direction:
                    column-reverse;
            }


            .form-footer .btn {

                width: 100%;
            }

        }

    </style>

</head>


<body>


<div class="container">


    <!-- HEADER -->

    <div class="page-header">

        <h1>
            Tambah Pasien
        </h1>

        <p>
            Tambahkan data pasien baru
            ke dalam sistem klinik.
        </p>

    </div>


    <!-- CARD -->

    <div class="form-card">


        <div class="form-header">

            <h2>
                Informasi Pasien
            </h2>

            <p>
                Lengkapi informasi pasien
                pada form berikut.
            </p>

        </div>


        <div class="form-body">


            <!-- SUCCESS -->

            <?php if ($success !== ''): ?>

                <div class="alert alert-success">

                    ✓
                    <?= htmlspecialchars($success) ?>

                </div>

            <?php endif; ?>


            <!-- ERROR -->

            <?php if ($error !== ''): ?>

                <div class="alert alert-error">

                    ⚠
                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action=""
            >


                <div class="form-grid">


                    <!-- ID PASIEN -->

                    <div class="form-group">

                        <label>
                            ID Pasien
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="idpasien"
                            placeholder="Contoh: P001"
                            value="<?= htmlspecialchars($idpasien) ?>"
                            required
                        >

                    </div>


                    <!-- NO RM -->

                    <div class="form-group">

                        <label>
                            Nomor Rekam Medis
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="norm"
                            placeholder="Contoh: RM001"
                            value="<?= htmlspecialchars($norm) ?>"
                            required
                        >

                    </div>


                    <!-- NAMA -->

                    <div class="form-group">

                        <label>
                            Nama Pasien
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="namapasien"
                            placeholder="Masukkan nama lengkap"
                            value="<?= htmlspecialchars($namapasien) ?>"
                            required
                        >

                    </div>


                    <!-- JENIS KELAMIN -->

                    <div class="form-group">

                        <label>
                            Jenis Kelamin
                            <span class="required">*</span>
                        </label>

                        <select
                            name="jeniskelamin"
                            required
                        >

                            <option value="">
                                Pilih jenis kelamin
                            </option>

                            <option
                                value="Laki-laki"
                                <?= $jeniskelamin === 'Laki-laki'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Laki-laki
                            </option>

                            <option
                                value="Perempuan"
                                <?= $jeniskelamin === 'Perempuan'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <!-- NO HP -->

                    <div class="form-group">

                        <label>
                            Nomor HP
                            <span class="required">*</span>
                        </label>

                        <input
                            type="tel"
                            name="nohp"
                            placeholder="Contoh: 081234567890"
                            value="<?= htmlspecialchars($nohp) ?>"
                            required
                        >

                    </div>


                    <!-- TANGGAL LAHIR -->

                    <div class="form-group">

                        <label>
                            Tanggal Lahir
                            <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="tgllahir"
                            value="<?= htmlspecialchars($tgllahir) ?>"
                            required
                        >

                    </div>


                    <!-- TEMPAT LAHIR -->

                    <div class="form-group full">

                        <label>
                            Tempat Lahir
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            name="tempatlahir"
                            placeholder="Contoh: Tasikmalaya"
                            value="<?= htmlspecialchars($tempatlahir) ?>"
                            required
                        >

                    </div>


                </div>


                <!-- FOOTER -->

                <div class="form-footer">


                    <a
                        href="index.php"
                        class="btn btn-kembali"
                    >
                        ← Kembali
                    </a>


                    <button
                        type="submit"
                        class="btn btn-simpan"
                    >
                        ✓ Simpan Pasien
                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


</body>

</html>