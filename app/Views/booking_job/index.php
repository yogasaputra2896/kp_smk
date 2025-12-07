<?= $this->extend('layouts/layout') ?>
<!-- Title -->
<?= $this->section('title') ?>Booking Job<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Booking Job</h3>

    <!-- DESKTOP (Versi lengkap) -->
    <p class="text-subtitle text-muted d-none d-sm-block">
        Modul Booking Job menyediakan fitur lengkap untuk mengelola data booking,
        mulai dari tambah, edit, hapus, cetak note, kirim data, hingga ekspor laporan ke Excel dan PDF secara periodik.
        Selain itu, tersedia fitur filter berdasarkan jenis booking dan pencarian data untuk memudahkan pengguna
        menemukan informasi dengan cepat dan akurat. Modul ini juga dilengkapi dengan softdelete untuk menyimpan
        data yang telah dihapus sebelum benar-benar dihapus permanen, serta refresh data untuk memperbarui tampilan
        data secara real-time.
    </p>

    <!-- MOBILE (Versi singkat) -->
    <p class="text-subtitle text-muted d-block d-sm-none">
        Modul Booking Job menyediakan fitur tambah, edit, hapus, ekspor, filter, dan pencarian data.
        Softdelete serta refresh data juga tersedia untuk memudahkan pengelolaan.
    </p>
</div>

<?= $this->endSection() ?>

<!-- ====================== CONTENT ====================== -->
<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">

        <!-- ====================== TOOLBAR ====================== -->
        <div class="d-flex justify-content-between mb-3">
            <!-- Tombol kiri -->
            <div class="d-flex gap-2 flex-wrap">
                <!-- Tambah Booking -->
                <button id="btnAdd" class="btn btn-primary">
                    <i class="bi bi-calendar-plus me-2"></i>
                    <span class="d-none d-sm-inline">Tambah Booking Job</span>
                    <span class="d-inline d-sm-none">Add</span>
                </button>

                <!-- Export Excel -->
                <?php if (in_groups('admin')): ?>
                    <button id="btnExport" class="btn btn-success">
                        <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                        <span class="d-none d-sm-inline">Export Excel</span>
                        <span class="d-inline d-sm-none">Xls</span>
                    </button>

                    <!-- Export PDF -->
                    <button id="btnExportPdf" class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        <span class="d-none d-sm-inline">Export Pdf</span>
                        <span class="d-inline d-sm-none">Pdf</span>
                    </button>
                <?php endif; ?>
            </div>


            <!-- Tombol kanan -->
            <div class="d-flex gap-2 flex-wrap ms-2">
                <button id="btnRefresh" class="btn btn-secondary" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <?php if (in_groups('admin')): ?>
                    <button id="btnTrash" class="btn btn-danger" title="Sampah Booking Job">
                        <i class="bi bi-trash"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <hr class="border-2 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Jenis Booking Job</h6>
        </div>

        <!-- ====================== FILTER JENIS JOB ====================== -->
        <div class="mb-3 btn-group flex-wrap" role="group" aria-label="Filter jenis job">

            <!-- Import LCL -->
            <button class="btn btn-outline-primary filter-btn" data-type="import_lcl">
                <span class="d-none d-sm-inline">
                    Import<br>LCL
                </span>
                <span class="d-inline d-sm-none">
                    LCL
                </span>
            </button>

            <!-- FCL Jaminan -->
            <button class="btn btn-outline-primary filter-btn" data-type="import_fcl_jaminan">
                <span class="d-none d-sm-inline">
                    Import<br>FCL JAMINAN
                </span>
                <span class="d-inline d-sm-none">
                    FCL JAMINAN
                </span>
            </button>

            <!-- FCL Non-Jaminan -->
            <button class="btn btn-outline-primary filter-btn" data-type="import_fcl_nonjaminan">
                <span class="d-none d-sm-inline">
                    Import<br>FCL NON-JAMINAN
                </span>
                <span class="d-inline d-sm-none">
                    FCL NON-JAMINAN
                </span>
            </button>

            <!-- Lain -->
            <button class="btn btn-outline-primary filter-btn" data-type="lain">
                <span class="d-none d-sm-inline">
                    Import<br>LAIN-LAIN
                </span>
                <span class="d-inline d-sm-none">
                    LAIN-LAIN
                </span>
            </button>

            <!-- Export -->
            <button class="btn btn-outline-primary filter-btn" data-type="export">
                <span class="d-none d-sm-inline">
                    Export
                </span>
                <span class="d-inline d-sm-none">
                    Export
                </span>
            </button>

        </div>

        <br>
        <br>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Daftar Booking Job</h6>
        </div>


        <!-- ====================== TABLE BOOKING ====================== -->
        <div class="table-responsive">
            <table id="tblBookings" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>No Job</th>
                        <th>No PIB/PEB/PO</th>
                        <th>Importir/Exportir</th>
                        <th>Party</th>
                        <th>ETA/ETD</th>
                        <th>POL/POD</th>
                        <th>Pelayaran</th>
                        <th>BL</th>
                        <th>Master BL</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<!-- ====================== MODAL: EXPORT EXCEL RANGE ====================== -->
<div class="modal fade" id="modalExportExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Export Laporan Booking Job (Excel)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExportExcel">

                    <!-- Jenis Booking -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Booking Job</label>
                        <select id="jenisExport" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="export">Export</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Import Lain-lain</option>
                            <option value="all">Semua Job</option>
                        </select>
                    </div>

                    <h6>Periode Tanggal</h6>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" id="startExport" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" id="endExport" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>

                <button type="button" class="btn btn-success" id="btnConfirmExportExcel">
                    <i class="bi bi-download me-1"></i> Export
                </button>
            </div>

        </div>
    </div>
</div>



<!-- ====================== MODAL: EXPORT PDF RANGE ====================== -->
<div class="modal fade" id="modalExportPdf" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Export Laporan Booking Job (PDF)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExportPdf">

                    <!-- Jenis Booking -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Booking Job</label>
                        <select id="jenisExportPdf" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="export">Export</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Import Lain-lain</option>
                            <option value="all">Semua Job</option>
                        </select>
                    </div>

                    <h6>Periode Tanggal</h6>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" id="startExportPdf" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Selesai</label>
                            <input type="date" id="endExportPdf" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>

                <button class="btn btn-danger" id="btnConfirmExportPdf">
                    <i class="bi bi-download me-1"></i> Export
                </button>
            </div>

        </div>
    </div>
</div>



<?= $this->endSection() ?>

<!-- ====================== PAGE SCRIPTS & ASSETS ====================== -->
<?= $this->section('pageScripts') ?>

<!-- CSS -->
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/bookingjob.css') ?>">


<!-- JavaScript Libraries -->
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>




<!-- Page Config -->
<script>
    window.BookingJobConfig = {
        listUrl: "<?= base_url('booking-job/list') ?>",
        nextNoUrl: "<?= base_url('booking-job/nextno') ?>",
        storeUrl: "<?= base_url('booking-job/store') ?>",
        baseUrl: "<?= base_url() ?>",
        csrfName: "<?= csrf_token() ?>",
        csrfHash: "<?= csrf_hash() ?>",
        autoAdd: <?= session()->getFlashdata('autoAdd') ? 'true' : 'false' ?>
    };

    window.BookingJobFlash = {
        success: "<?= session()->getFlashdata('success') ?>",
        error: "<?= session()->getFlashdata('errors') ?>",
    };
</script>

<!-- Page Script -->
<script src="<?= base_url('assets/js/pages/booking-job.js') ?>"></script>

<?= $this->endSection() ?>