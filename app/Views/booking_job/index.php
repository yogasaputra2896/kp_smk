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

<!-- ====================== MODAL: TAMBAH BOOKING ====================== -->
<div class="modal fade" id="modalBooking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formBooking" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Booking Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="formErrors" class="alert alert-danger d-none"></div>

                <div class="row g-3">
                    <!-- Jenis Job -->
                    <div class="col-md-6">
                        <label for="jobType" class="form-label">Jenis Job</label>
                        <select name="type" id="jobType" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Import Lain-lain</option>
                            <option value="export">Export</option>
                        </select>
                    </div>

                    <!-- Nomor Job (auto-generate) -->
                    <div class="col-md-6">
                        <label for="noJob" class="form-label">Nomor Job</label>
                        <input type="text" name="no_job" id="noJob" class="form-control" required>
                    </div>

                    <!-- Input Nomor -->
                    <div class="col-md-6">
                        <label for="noPibPo" class="form-label">Nomor</label>
                        <select name="jenis_nomor" id="jenisNomor" class="form-select mb-1" required>
                            <option value="">-- Pilih Jenis Nomor --</option>
                            <option value="PIB">No PIB/PEB</option>
                            <option value="PIB-SENDIRI">No PIB/PEB Sendiri</option>
                            <option value="PO-SENDIRI">No PO Sendiri</option>
                        </select>
                        <input type="text" name="no_pib_po" id="noPibPo" class="form-control"
                            placeholder="Masukkan Nomor" required>
                    </div>

                    <!-- Consignee -->
                    <div class="col-md-6">
                        <label for="namaConsignee" class="form-label">Importir / Exportir</label>
                        <select name="consignee" id="namaConsignee" class="form-control"></select>
                    </div>


                    <!-- Party -->
                    <div class="col-md-6">
                        <label for="party" class="form-label">Party</label>
                        <input type="text" name="party" id="party" class="form-control"
                            placeholder="1 X 20 / LCL 1 PK" required>
                    </div>

                    <!-- ETA -->
                    <div class="col-md-6">
                        <label for="eta" class="form-label">ETA/ETD</label>
                        <input type="date" name="eta" id="eta" class="form-control" required>
                    </div>

                    <!-- POL -->
                    <div class="col-md-6">
                        <label for="namaPort" class="form-label">POL/POD</label>
                        <select name="pol" id="namaPort" class="form-control"></select>
                    </div>

                    <!-- Shipping Line -->
                    <div class="col-md-6">
                        <label for="namaPelayaran" class="form-label">Pelayaran / Shipping Line</label>
                        <select name="shipping_line" id="namaPelayaran" class="form-control"></select>
                    </div>

                    <!-- BL -->
                    <div class="col-md-6">
                        <label for="bl" class="form-label">Bill of Lading (BL)</label>
                        <input type="text" name="bl" id="bl" class="form-control" placeholder="Nomor BL" required>
                    </div>

                    <!-- Master BL -->
                    <div class="col-md-6">
                        <label for="masterBl" class="form-label">Master BL</label>
                        <input type="text" name="master_bl" id="masterBl" class="form-control"
                            placeholder="Nomor Master BL" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- ====================== MODAL: EDIT BOOKING ====================== -->
<div class="modal fade" id="modalEditBooking" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditBooking" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Booking Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="editFormErrors" class="alert alert-danger d-none"></div>

                <input type="hidden" name="id" id="editId">

                <div class="row g-3">
                    <!-- Jenis Job -->
                    <div class="col-md-6">
                        <label for="editJobType" class="form-label">Jenis Job</label>
                        <select name="type" id="editJobType" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="export">Export</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Lain-lain</option>
                        </select>
                    </div>

                    <!-- Nomor Job -->
                    <div class="col-md-6">
                        <label for="editNoJob" class="form-label">Nomor Job</label>
                        <input type="text" name="no_job" id="editNoJob" class="form-control" required>
                    </div>

                    <!-- No PIB/PO -->
                    <div class="col-md-6">
                        <label class="form-label">No PIB/PEB/PO</label>

                        <!-- Dropdown jenis nomor -->
                        <select name="edit_jenis_nomor" id="editJenisNomor" class="form-select mb-1" required>
                            <option value="">-- Pilih Jenis Nomor --</option>
                            <option value="PIB">No PIB/PEB</option>
                            <option value="PIB-SENDIRI">No PIB/PEB Sendiri</option>
                            <option value="PO-SENDIRI">No PO Sendiri</option>
                        </select>

                        <!-- Input nomor -->
                        <input type="text" name="no_pib_po" id="editNoPibPo" class="form-control" required>
                    </div>


                    <!-- Consignee -->
                    <div class="col-md-6">
                        <label for="namaConsignee" class="form-label">Importir / Exportir</label>
                        <select name="consignee" id="namaConsignee" class="form-select" style="width: 100%"></select>
                    </div>

                    <!-- Party -->
                    <div class="col-md-6">
                        <label for="editParty" class="form-label">Party</label>
                        <input type="text" name="party" id="editParty" class="form-control" required>
                    </div>

                    <!-- ETA -->
                    <div class="col-md-6">
                        <label for="editEta" class="form-label">ETA/ETD</label>
                        <input type="date" name="eta" id="editEta" class="form-control" required>
                    </div>

                    <!-- POL -->
                    <div class="col-md-6">
                        <label for="editPol" class="form-label">POL/POD</label>
                        <input type="text" name="pol" id="editPol" class="form-control" required>
                    </div>

                    <!-- Shipping Line -->
                    <div class="col-md-6">
                        <label for="editShippingLine" class="form-label">Pelayaran / Shipping Line</label>
                        <input type="text" name="shipping_line" id="editShippingLine" class="form-control" required>
                    </div>

                    <!-- BL -->
                    <div class="col-md-6">
                        <label for="editBl" class="form-label">Bill of Lading (BL)</label>
                        <input type="text" name="bl" id="editBl" class="form-control" required>
                    </div>

                    <!-- Master BL -->
                    <div class="col-md-6">
                        <label for="editMasterBl" class="form-label">Master BL</label>
                        <input type="text" name="master_bl" id="editMasterBl" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button type="submit" class="btn btn-warning text-dark"><i class="bi bi-floppy2-fill me-1"></i> Update Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- ====================== MODAL: EXPORT EXCEL ====================== -->
<div class="modal fade" id="modalExportExcel" tabindex="-1" aria-labelledby="modalExportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportExcelLabel">Export Booking Job To Excel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formExportExcel">
                    <!-- Jenis export -->
                    <div class="mb-3">
                        <label for="jenisExport" class="form-label fw-semibold">Pilih Jenis Booking Job</label>
                        <select id="jenisExport" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="export">Export</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Import Lain-lain</option>
                            <option value="all">Semua Booking Job</option>
                        </select>
                    </div>

                    <div class="row">
                        <h6>Periode</h6>
                        <hr>
                        <!-- Tahun -->
                        <div class="col-md-6 mb-3">
                            <label for="exportYear" class="form-label fw-semibold">Tahun</label>
                            <select id="exportYear" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                                <!-- akan diisi otomatis dari database -->
                            </select>
                        </div>

                        <!-- Bulan -->
                        <div class="col-md-6 mb-3">
                            <label for="exportMonth" class="form-label fw-semibold">Bulan</label>
                            <select id="exportMonth" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                                <!-- akan diisi otomatis via JS -->
                            </select>
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

<!-- ====================== MODAL: EXPORT PDF ====================== -->
<div class="modal fade" id="modalExportPdf" tabindex="-1" aria-labelledby="modalExportPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportPdfLabel">Export Booking Job To PDF</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="formExportPdf">
                    <!-- Jenis export -->
                    <div class="mb-3">
                        <label for="jenisExportPdf" class="form-label fw-semibold">Pilih Jenis Booking Job</label>
                        <select id="jenisExportPdf" class="form-select" required>
                            <option value="">-- Pilih Jenis Job --</option>
                            <option value="export">Export</option>
                            <option value="import_lcl">Import LCL</option>
                            <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                            <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                            <option value="lain">Import Lain-lain</option>
                            <option value="all">Semua Booking Job</option>
                        </select>
                    </div>

                    <div class="row">
                        <h6>Periode</h6>
                        <hr>
                        <!-- Tahun -->
                        <div class="col-md-6 mb-3">
                            <label for="exportYearPdf" class="form-label fw-semibold">Tahun</label>
                            <select id="exportYearPdf" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                                <!-- akan diisi otomatis dari database -->
                            </select>
                        </div>

                        <!-- Bulan -->
                        <div class="col-md-6 mb-3">
                            <label for="exportMonthPdf" class="form-label fw-semibold">Bulan</label>
                            <select id="exportMonthPdf" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                                <!-- akan diisi otomatis via JS -->
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btnConfirmExportPdf">
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
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/bookingjob.css') ?>">

<!-- JavaScript Libraries -->
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>

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
</script>

<!-- Page Script -->
<script src="<?= base_url('assets/js/pages/booking-job.js') ?>"></script>

<?= $this->endSection() ?>