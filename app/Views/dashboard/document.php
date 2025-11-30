<?= $this->extend('layouts/layout') ?>
<?= $this->section('title') ?>Document Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Dashboard Staff Document</h3>
    <p class="text-subtitle text-muted">Selamat Datang Di Dashboard Document - Trustway System Management Kepabeanan</p>
</div>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">

<!-- Worksheet Statistics -->
<div class="category-title mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Statistik Worksheet</div>
<div class="row g-3">
    <!-- Total Worksheet -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-journal-text fs-2 text-primary"></i>
            </div>
            <div class="stat-title mb-1">Total Worksheet</div>
            <div class="stat-value fw-bold"><?= $totalWorksheet ?></div>
        </div>
    </div>

    <!-- Worksheet Import -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-box-arrow-in-down-right fs-2 text-warning"></i>
            </div>
            <div class="stat-title mb-1">Worksheet Import</div>
            <div class="stat-value fw-bold"><?= $totalWorksheetImport ?></div>
        </div>
    </div>

    <!-- Worksheet Export -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-box-arrow-up-left fs-2 text-success"></i>
            </div>
            <div class="stat-title mb-1">Worksheet Export</div>
            <div class="stat-value fw-bold"><?= $totalWorksheetExport ?></div>
        </div>
    </div>

    <!-- Deleted Worksheet -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-trash3-fill fs-2 text-danger"></i>
            </div>
            <div class="stat-title mb-1">Deleted Worksheet</div>
            <div class="stat-value fw-bold"><?= $totalDeletedWorksheet ?></div>
        </div>
    </div>
</div>

<!-- Grafik -->
<div class="row mt-4 g-3">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header">
                <h5><i class="bi bi-pie-chart-fill"> Visualisasi Worksheet Import vs Export</i></h5>
            </div>
            <div class="card-body d-flex justify-content-center">
                <canvas id="chartWorksheet" style="max-width: 200px; max-height: 225px;"></canvas>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/pages/dashboard/chart.js') ?>"></script>

<script>
    window.chartWorksheetData = [<?= $chartWorksheet['import'] ?>, <?= $chartWorksheet['export'] ?>];
</script>
<?= $this->endSection() ?>