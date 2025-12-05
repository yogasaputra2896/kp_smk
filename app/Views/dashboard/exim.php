<?= $this->extend('layouts/layout') ?>
<?= $this->section('title') ?>Exim Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="page-heading">
    <h3>Dashboard Export-Import</h3>
    <p class="text-subtitle text-muted">Selamat Datang Di Dashboard Exim - Trustway System Management Kepabeanan</p>
</div>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">


<!-- Booking Job Statistics -->
<div class="category-title mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Statistik Booking Job</div>
<div class="row g-3">
    <!-- Total Booking Job -->
    <div class="col-lg-3 col-md-6 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-calendar-check-fill fs-2 text-primary"></i>
            </div>
            <div class="stat-title mb-1">Total Booking Job</div>
            <div class="stat-value fw-bold"><?= $totalBooking ?></div>
        </div>
    </div>

    <!-- Total Open Job -->
    <div class="col-lg-3 col-md-6 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-calendar-check fs-2 text-primary"></i>
            </div>
            <div class="stat-title mb-1">Status Open Job</div>
            <div class="stat-value fw-bold"><?= $totalOpenJob ?></div>
        </div>
    </div>

    <!-- Total Open Job -->
    <div class="col-lg-3 col-md-6 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-journal-text fs-2 text-success"></i>
            </div>
            <div class="stat-title mb-1">Status Terkirim</div>
            <div class="stat-value fw-bold"><?= $totalWorksheetStatus ?></div>
        </div>
    </div>

    <!-- Deleted Booking Job -->
    <div class="col-lg-3 col-md-6 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-trash3-fill fs-2 text-danger"></i>
            </div>
            <div class="stat-title mb-1">Deleted Booking Job</div>
            <div class="stat-value fw-bold"><?= $totalTrashBooking ?></div>
        </div>
    </div>

    <!-- Booking Job by Type -->
    <?php foreach ($bookingByType as $b): ?>
        <?php
        // Tentukan ukuran kolom
        if ($b['type'] == 'import_fcl_nonjaminan' || $b['type'] == 'import_fcl_jaminan') {
            $colClass = 'col-md-3 col-6';
        } else {
            $colClass = 'col-md-2 col-6';
        }

        // Tentukan label dan warna icon
        switch ($b['type']) {

            case 'import_fcl_jaminan':
                $label = 'FCL Jaminan';
                $icon = 'bi-truck text-warning';
                break;
            case 'import_fcl_nonjaminan':
                $label = 'FCL Non-Jaminan';
                $icon = 'bi-truck text-info';
                break;
            case 'export':
                $label = 'Export';
                $icon = 'bi-box-arrow-up-right text-success';
                break;
            case 'import_lcl':
                $label = 'LCL';
                $icon = 'bi-archive text-secondary';
                break;
            default:
                $label = 'Lain-lain';
                $icon = 'bi-question-circle text-muted';
        }
        ?>
        <div class="<?= $colClass ?>">
            <div class="card text-center stat-card p-3">
                <div class="icon mb-2">
                    <i class="bi <?= $icon ?> fs-2"></i>
                </div>
                <div class="stat-title mb-1"><?= $label ?></div>
                <div class="stat-value fw-bold"><?= $b['total'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Grafik -->
<div class="row mt-4 g-3">

    <!-- Grafik Booking Job (Bar) -->
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart-fill me-2"></i>Visualisasi Total Booking Job</h5>
            </div>
            <div class="card-body">
                <canvas id="chartBookingJob"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Booking Job Per Hari (Line Chart) -->
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header">
                <h5><i class="bi bi-graph-up-arrow me-2"></i>Visualisasi Booking Job Per Hari</h5>
            </div>
            <div class="card-body">
                <canvas id="chartBookingPerDay"></canvas>
            </div>
        </div>
    </div>

</div>

<!-- Log Aktivitas -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm log-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Log Aktivitas Terbaru
                </h5>
                <small class="text-muted">Role: <?= ucfirst(in_groups('admin') ? 'Admin' : (in_groups('exim') ? 'Exim' : 'Document')) ?></small>
            </div>
            
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                <?php if (!empty($logs)): ?>
                    <?php foreach ($logs as $log): ?>
                        <div class="log-item border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong class="text-primary"><?= esc($log['role']) ?></strong>
                                    <span class="text-dark">- <?= esc($log['activity']) ?></span>
                                </div>
                                <small class="text-muted">
                                    <?= date('d M Y H:i', strtotime($log['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-info-circle"></i> Tidak ada aktivitas.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/pages/dashboard/chart.js') ?>"></script>

<script>
    window.chartBookingLabels = <?= json_encode(array_column($chartBookingJob, 'type')) ?>;
    window.chartBookingData = <?= json_encode(array_column($chartBookingJob, 'total')) ?>;
    window.bookingPerDayLabels = <?= json_encode(array_column($bookingPerDay, 'date')) ?>;
    window.bookingPerDayTotals = <?= json_encode(array_column($bookingPerDay, 'total')) ?>;
</script>

<?= $this->endSection() ?>