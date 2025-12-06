<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Admin Dashboard<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Dashboard Admin</h3>
    <p class="text-subtitle text-muted">Selamat Datang Di Dashboard Admin - Trustway System Management Kepabeanan</p>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/pages/dashboard.css') ?>">

<!-- User Statistics -->
<div class="category-title mb-3"><i class="bi bi-bar-chart-fill me-2"></i>Statistik User</div>
<div class="row g-3">
    <!-- Total User -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-people-fill fs-2 text-primary"></i>
            </div>
            <div class="stat-title mb-1">Total User</div>
            <div class="stat-value fw-bold"><?= $totalUser ?></div>
        </div>
    </div>

    <!-- User by Role -->
    <?php foreach ($userByRole as $u): ?>
        <div class="col-md-2 col-6">
            <div class="card text-center stat-card p-3">
                <div class="icon mb-2">
                    <i class="bi bi-person-fill fs-2 text-secondary"></i>
                </div>
                <div class="stat-title mb-1"><?= ucfirst($u['role']) ?></div>
                <div class="stat-value fw-bold"><?= $u['total'] ?></div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Deleted User -->
    <div class="col-md-3 col-6">
        <div class="card text-center stat-card p-3">
            <div class="icon mb-2">
                <i class="bi bi-trash3-fill fs-2 text-danger"></i>
            </div>
            <div class="stat-title mb-1">Deleted User</div>
            <div class="stat-value fw-bold"><?= $totalDeletedUser ?></div>
        </div>
    </div>
</div>


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
            <div class="stat-title mb-1">Status Worksheet</div>
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




<!-- Top 5 Consignee -->
<div class="card mt-4">
    <div class="card-header">
        <h5><i class="bi bi-fire">Top 5 Consignee</i></h5>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Consignee</th>
                    <th>Total Booking</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topConsignees as $key => $c): ?>
                    <tr>
                        <td><?= $key + 1 ?></td>
                        <td><?= $c['consignee'] ?></td>
                        <td><?= $c['total'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Grafik -->
<div class="row mt-4 g-3">
    <div class="col-md-6">
        <div class="card stat-card">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart-fill me-2"> Visualisasi Booking Job</i></h5>
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
<!-- Log Aktivitas -->
<div class="row mt-4 g-3">
    <div class="col-md-12">
        <div class="card stat-card log-card">

            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Log Aktivitas Terbaru
                </h5>
            </div>

            <div class="card-body p-2">

                <div class="table-log-wrapper"> <!-- scroll hanya disini -->
                    <table id="tblLog" class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Aktivitas</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><strong class="text-primary"><?= esc($log['username']) ?></strong></td>

                                    <td>
                                        <?php if ($log['role'] === 'admin'): ?>
                                            <span class="badge bg-danger badge-role">Admin</span>
                                        <?php elseif ($log['role'] === 'exim'): ?>
                                            <span class="badge bg-success badge-role">Exim</span>
                                        <?php else: ?>
                                            <span class="badge bg-info badge-role">Document</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="timeline-item"><?= esc($log['activity']) ?></div>
                                    </td>

                                    <td><?= date('d M Y H:i', strtotime($log['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="<?= base_url('assets/js/pages/dashboard/chart.js') ?>"></script>

<script>
    window.chartBookingLabels = <?= json_encode(array_column($chartBookingJob, 'type')) ?>;
    window.chartBookingData = <?= json_encode(array_column($chartBookingJob, 'total')) ?>;
    window.bookingPerDayLabels = <?= json_encode(array_column($bookingPerDay, 'date')) ?>;
    window.bookingPerDayTotals = <?= json_encode(array_column($bookingPerDay, 'total')) ?>;
    window.chartWorksheetData = [<?= $chartWorksheet['import'] ?>, <?= $chartWorksheet['export'] ?>];
</script>
<?= $this->endSection() ?>