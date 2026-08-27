<?php

include 'koneksi.php';

// ==========================================
// CEK ID
// ==========================================

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = trim($_GET['id']);


// ==========================================
// AMBIL DATA PASIEN
// ==========================================

$sql = "SELECT * FROM pasien WHERE idpasien = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query gagal: " . $conn->error);
}

$stmt->bind_param("s", $id);

$stmt->execute();

$result = $stmt->get_result();


// Cek apakah data ditemukan
if ($result->num_rows === 0) {
    $stmt->close();

    echo "<script>
            alert('Data pasien tidak ditemukan.');
            window.location.href='index.php';
          </script>";

    exit();
}

$pasien = $result->fetch_assoc();

$stmt->close();


// ==========================================
// PROSES UPDATE
// ==========================================

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $namapasien   = trim($_POST['namapasien'] ?? '');
    $jeniskelamin = trim($_POST['jeniskelamin'] ?? '');
    $notelpon     = trim($_POST['notelpon'] ?? '');
    $tempatlahir  = trim($_POST['tempatlahir'] ?? '');
    $tgllahir     = trim($_POST['tgllahir'] ?? '');


    // Validasi
    if (
        $namapasien === '' ||
        $jeniskelamin === '' ||
        $notelpon === '' ||
        $tempatlahir === '' ||
        $tgllahir === ''
    ) {

        $error = "Semua data wajib diisi.";

    } else {

        // ==========================================
        // UPDATE DATA
        // ==========================================

        $sql = "UPDATE pasien SET
                    namapasien = ?,
                    jeniskelamin = ?,
                    notelpon = ?,
                    tempatlahir = ?,
                    tgllahir = ?
                WHERE idpasien = ?";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $error = "Query update gagal: " . $conn->error;

        } else {

            $stmt->bind_param(
                "ssssss",
                $namapasien,
                $jeniskelamin,
                $notelpon,
                $tempatlahir,
                $tgllahir,
                $id
            );


            if ($stmt->execute()) {

                $stmt->close();

                echo "<script>
                        alert('Data pasien berhasil diperbarui.');
                        window.location.href='index.php';
                      </script>";

                exit();

            } else {

                $error = "Gagal memperbarui data: " . $stmt->error;

                $stmt->close();
            }
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


        /* HEADER */

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


        /* CARD */

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

            font-size: 13px;

            color: #6b7280;
        }


        .form-body {

            padding: 25px;
        }


        /* INFO PASIEN */

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


        /* ALERT */

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


        /* FORM */

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


        /* FOOTER */

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


        /* RESPONSIVE */

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
            Perbarui informasi pasien
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


            <!-- INFO -->

            <div class="patient-info">

                ID Pasien:

                <strong>
                    <?= htmlspecialchars($pasien['idpasien']) ?>
                </strong>

                &nbsp;&nbsp; | &nbsp;&nbsp;

                No. RM:

                <strong>
                    <?= htmlspecialchars($pasien['norm']) ?>
                </strong>

            </div>


            <!-- ERROR -->

            <?php if ($error !== ''): ?>

                <div class="alert alert-error">

                    ⚠️

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <!-- FORM -->

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
                            value="<?= htmlspecialchars($pasien['namapasien']) ?>"
                            placeholder="Masukkan nama pasien"
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
                                -- Pilih Jenis Kelamin --
                            </option>


                            <option
                                value="Laki-laki"
                                <?= (
                                    $pasien['jeniskelamin']
                                    === 'Laki-laki'
                                )
                                ? 'selected'
                                : '' ?>
                            >
                                Laki-laki
                            </option>


                            <option
                                value="Perempuan"
                                <?= (
                                    $pasien['jeniskelamin']
                                    === 'Perempuan'
                                )
                                ? 'selected'
                                : '' ?>
                            >
                                Perempuan
                            </option>

                        </select>

                    </div>


                    <!-- NO TELEPON -->

                    <div class="form-group">

                        <label>

                            Nomor Telepon

                            <span class="required">
                                *
                            </span>

                        </label>


                        <input
                            type="tel"
                            name="notelpon"
                            value="<?= htmlspecialchars($pasien['notelpon']) ?>"
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
                            value="<?= htmlspecialchars($pasien['tgllahir']) ?>"
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
                            value="<?= htmlspecialchars($pasien['tempatlahir']) ?>"
                            placeholder="Contoh: Tasikmalaya"
                            required
                        >

                    </div>


                </div>


                <!-- BUTTON -->

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