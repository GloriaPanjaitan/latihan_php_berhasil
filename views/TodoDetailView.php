<?php 
// File: views/TodoDetailView.php
// Variabel $todo dikirim dari TodoController::detail()

if (!isset($todo)) {
    header('Location: ' . BASE_URL);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Detail Todo: <?= htmlspecialchars($todo['title']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    <style>
        /* Ganti kode warna (#f0f8ff) sesuai dengan warna yang Anda inginkan */
        body {
            background-color: #f6f7a7ff; /* Contoh: Warna Biru Muda (AliceBlue) */
        }

        /* Opsi tambahan: Mengubah warna latar belakang Card utama agar sedikit transparan atau tetap putih */
        .card {
            background-color: #97eef0ff; /* Pastikan Card tetap putih agar kontras */
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Detail Todo</h3>
        </div>
        <div class="card-body">
            <h4 class="card-title"><?= htmlspecialchars($todo['title']) ?></h4>
            <hr>
            <p class="card-text">
                <strong>Deskripsi:</strong><br>
                <?= nl2br(htmlspecialchars($todo['description'])) ?>
            </p>
            <p>
    <strong>Status:</strong>
    <?php if (($todo['is_finished'] ?? 'f') === 't'): ?>
        <span class="badge bg-success">Selesai</span>
    <?php else: ?>
        <span class="badge bg-danger">Belum Selesai</span>
    <?php endif; ?>
</p>
            <p class="text-muted small">
                Dibuat: <?= date('d F Y H:i', strtotime($todo['created_at'])) ?><br>
                Diperbarui: <?= date('d F Y H:i', strtotime($todo['updated_at'])) ?>
            </p>
        </div>
        <div class="card-footer">
            <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary">Kembali ke Daftar</a>
        </div>
    </div>
</div>
<script src="<?= BASE_URL ?>assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>