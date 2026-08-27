```php
<?php

// ======================================================
// KONEKSI DATABASE
// ======================================================
include 'koneksi.php';


// ======================================================
// CEK ID
// ======================================================
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = trim($_GET['id']);


// ======================================================
// AMBIL DATA PASIEN
// ======================================================
$stmt = $conn->prepare(
    "SELECT * FROM pasien WHERE idpasien = ?"
);

$stmt->bind_param("s", $id);
$stmt->execute();

$result = $stmt->get_result();


// Jika pasien tidak ditemukan
if ($result->num_rows === 0) {
    $stmt->close();

    header("Location: index.php");
    exit();
}

$data = $result->fetch_assoc();

$stmt->close();


// ======================================================
// NILAI AWAL FORM
// ======================================================
$namapasien   = $data['namapasien'] ?? '';
$jeniskelamin = $data['jeniskelamin'] ?? '';
$nohp         = $data['notelpon'] ?? '';
$tempatlahir  = $data['tempatlahir'] ?? '';
$tgllahir     = $data['tgllahir'] ?? '';

$error = '';


// ======================================================
// PROSES UPDATE
// ======================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $namapasien   = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = trim($_POST['jeniskelamin'] ?? '');
    $nohp         = trim($_POST['nohp'] ?? '');
    $tempatlahir  = trim($_POST['tempatlahir'] ?? '');
    $tgllahir     = trim($_POST['tgllahir'] ?? '');


    // ==================================================
    // VALIDASI
    // ==================================================
    if (
        $namapasien === '' ||
        $jeniskelamin === '' ||
        $nohp === '' ||
        $tempatlahir === '' ||
        $tgllahir === ''
    ) {

        $error = "Semua data wajib diisi.";

    } else {

        try {

            // ==================================================
            // UPDATE DATA
            // ==================================================
            $update = $conn->prepare(
                "UPDATE pasien SET
                    namapasien = ?,
                    jeniskelamin = ?,
                    notelpon = ?,
                    tempatlahir = ?,
                    tgllahir = ?
                 WHERE idpasien = ?"
            );

            $update->bind_param(
                "ssssss",
                $namapasien,
                $jeniskelamin,
                $nohp,
                $tempatlahir,
                $tgllahir,
                $id
            );

            $update->execute();

            $update->close();

            // ==================================================
            // KEMBALI KE DAFTAR PASIEN
            // ==================================================
            header("Location: index.php");
            exit();

        } catch (mysqli_sql_exception $e) {

            $error = "Gagal mengupdate data: " . $e->getMessage();
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

    <title>Edit Pasien | Klinik</title>


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

            border:
                1px solid #e5e7eb;

            border-radius: 16px;

            box-shadow:
                0 5px 20px
                rgba(0, 0, 0, 0.04);

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
           INFO ID PASIEN
        ========================= */

        .patient-info {

            background: #eff6ff;

            border:
                1px solid #dbeafe;

            border-radius: 10px;

            padding: 14px 16px;

            margin-bottom: 22px;

            font-size: 13px;

            color: #1e40af;
        }


        .patient-info strong {

            color: #1d4ed8;
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

            grid-column:
                span 2;
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


        input:focus,
        select:focus {

            border-color:
                #2563eb;

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

            color: #ffffff;

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

                grid-template-columns:
                    1fr;
            }


            .form-group.full {

                grid-column:
                    span 1;
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
            Edit Pasien
        </h1>

        <p>
            Perbarui informasi data pasien
            yang sudah terdaftar.
        </p>

    </div>


    <!-- CARD -->

    <div class="form-card">


        <div class="form-header">

            <h2>
                Informasi Pasien
            </h2>

            <p>
                Ubah data pasien sesuai
                dengan informasi terbaru.
            </p>

        </div>


        <div class="form-body">


            <!-- ID PASIEN -->

            <div class="patient-info">

                ID Pasien:
                <strong>
                    <?= htmlspecialchars($id) ?>
                </strong>

                &nbsp; | &nbsp;

                No. RM:
                <strong>
                    <?= htmlspecialchars($data['norm'] ?? '-') ?>
                </strong>

            </div>


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


                    <!-- NAMA -->

                    <div class="form-group">

                        <label>

                            Nama Pasien

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            name="namapasien"
                            value="<?= htmlspecialchars($namapasien) ?>"
                            placeholder="Masukkan nama lengkap"
                            required
                        >

                    </div>


                    <!-- JENIS KELAMIN -->

                    <div class="form-group">

                        <label>

                            Jenis Kelamin

                            <span class="required">
                                *
                            </span>

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
                                <?= (
                                    $jeniskelamin === 'Laki-laki' ||
                                    $jeniskelamin === 'L'
                                ) ? 'selected' : '' ?>
                            >
                                Laki-laki
                            </option>


                            <option
                                value="Perempuan"
                                <?= (
                                    $jeniskelamin === 'Perempuan' ||
                                    $jeniskelamin === 'P'
                                ) ? 'selected' : '' ?>
                            >
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <!-- NO HP -->

                    <div class="form-group">

                        <label>

                            Nomor HP

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="tel"
                            name="nohp"
                            value="<?= htmlspecialchars($nohp) ?>"
                            placeholder="Contoh: 081234567890"
                            required
                        >

                    </div>


                    <!-- TANGGAL LAHIR -->

                    <div class="form-group">

                        <label>

                            Tanggal Lahir

                            <span class="required">
                                *
                            </span>

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

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="text"
                            name="tempatlahir"
                            value="<?= htmlspecialchars($tempatlahir) ?>"
                            placeholder="Contoh: Tasikmalaya"
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

                        ✓ Simpan Perubahan

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


</body>

</html>
```
