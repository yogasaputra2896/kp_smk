<?= $this->extend('layouts/layout') ?>
<!-- Title -->
<?= $this->section('title') ?>Booking Job<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Booking Job</h3>
    <p class="text-subtitle text-muted">
        Modul Booking Job menyediakan fitur lengkap untuk mengelola data booking,
        mulai dari tambah, edit, hapus, cetak note, kirim data, hingga ekspor laporan ke Excel dan PDF secara periodik.
        Selain itu, tersedia fitur filter berdasarkan jenis booking dan pencarian data untuk memudahkan pengguna
        menemukan informasi dengan cepat dan akurat. Modul ini juga dilengkapi dengan softdalete untuk menyimpan
        data yang telah dihapus sebelum benar-benar dihapus permanen, serta refresh data untuk memperbarui tampilan
        data secara real-time.
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
            <div class="d-flex gap-2">
                <button id="btnAdd" class="btn btn-primary">
                    <i class="bi bi-calendar-plus me-2"></i> Tambah Booking Job
                </button>
                <button id="btnExport" class="btn btn-success">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i> Export Excel
                </button>
                <button id="btnExportPdf" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf me-2"></i> Export Pdf
                </button>
            </div>

            <!-- Tombol kanan -->
            <div class="d-flex gap-2">
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


        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Jenis List Job</h6>
        </div>

        <!-- ====================== FILTER JENIS JOB ====================== -->
        <div class="mb-3 btn-group" role="group" aria-label="Filter jenis job">
            <button class="btn btn-outline-primary filter-btn" data-type="import_lcl">
                List Job Import <br>LCL
            </button>
            <button class="btn btn-outline-primary filter-btn" data-type="import_fcl_jaminan">
                List Job Import <br>FCL JAMINAN
            </button>
            <button class="btn btn-outline-primary filter-btn" data-type="import_fcl_nonjaminan">
                List Job Import <br>FCL NON-JAMINAN
            </button>
            <button class="btn btn-outline-primary filter-btn" data-type="lain">
                List Job Import <br>LAIN-LAIN
            </button>
            <button class="btn btn-outline-primary filter-btn" data-type="export">
                List Job Export
            </button>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Daftar Booking</h6>
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

                    <!-- No PIB/PO -->
                    <div class="col-md-6">
                        <label for="noPibPo" class="form-label">No PIB/PEB/PO</label>
                        <input type="text" name="no_pib_po" id="noPibPo" class="form-control"
                            placeholder="Nomor PIB/PEB/PO" required>
                    </div>

                    <!-- Consignee -->
                    <div class="col-md-6">
                        <label for="consignee" class="form-label">Importir/Exportir</label>
                        <input type="text" name="consignee" id="consignee" class="form-control"
                            placeholder="Nama Importir / Exportir" required>
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
                        <label for="pol" class="form-label">POL/POD</label>
                        <input type="text" name="pol" id="pol" class="form-control"
                            placeholder="Nama POL / POD" required>
                    </div>

                    <!-- Shipping Line -->
                    <div class="col-md-6">
                        <label for="shippingLine" class="form-label">Pelayaran / Shipping Line</label>
                        <input type="text" name="shipping_line" id="shippingLine" class="form-control"
                            placeholder="Nama pelayaran" required>
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
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Booking</button>
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
                        <label for="editNoPibPo" class="form-label">No PIB/PEB/PO</label>
                        <input type="text" name="no_pib_po" id="editNoPibPo" class="form-control" required>
                    </div>

                    <!-- Consignee -->
                    <div class="col-md-6">
                        <label for="editConsignee" class="form-label">Importir/Exportir</label>
                        <input type="text" name="consignee" id="editConsignee" class="form-control" required>
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
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Update Booking</button>
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
                    <i class="bi bi-download me-1"></i> Export Sekarang
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
                    <i class="bi bi-download me-1"></i> Export Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- ====================== PAGE SCRIPTS & ASSETS ====================== -->
<?= $this->section('pageScripts') ?>
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>

<!-- ====================== INLINE CSS ====================== -->
<style>
    .filter-btn {
        position: relative;
        overflow: hidden;
        background-color: #fff !important;
        color: #435ebe !important;
        border: 1px solid #435ebe !important;
        transition: all 0.3s ease-in-out;
    }

    .filter-btn:hover {
        background-color: #435ebe !important;
        color: #fff !important;
    }

    .filter-btn.active {
        background-color: #435ebe !important;
        color: #fff !important;
        border-color: #435ebe !important;
        font-weight: 600;
        box-shadow: 0 0.25rem 0.5rem rgba(67, 94, 190, 0.4);
    }

    #tblBookings th,
    #tblBookings td {
        font-size: 15px;
    }

    #tblBookings th:nth-child(3),
    #tblBookings td:nth-child(3) {
        min-width: 75px !important;
    }

    #tblBookings th:nth-child(4),
    #tblBookings td:nth-child(4) {
        min-width: 200px !important;
    }

    #tblBookings th:nth-child(6),
    #tblBookings td:nth-child(6) {
        min-width: 75px !important;
    }

    #tblBookings th:nth-child(7),
    #tblBookings td:nth-child(7) {
        min-width: 125px !important;
    }

    #tblBookings th:nth-child(9),
    #tblBookings td:nth-child(9) {
        min-width: 150px !important;
    }

    #tblBookings th:nth-child(10),
    #tblBookings td:nth-child(10) {
        min-width: 150px !important;
    }

    #tblBookings {
        width: 100% !important;
    }

    #tblBookings th,
    #tblBookings td {
        white-space: nowrap;
        /* biar teks tidak turun ke bawah */
        font-size: 15px;
    }

    .dataTables_wrapper .dataTables_scroll {
        overflow: auto;
    }

    #tblBookings tbody tr:hover {
        background-color: #f3f6ff !important;
        transition: background 0.2s ease-in-out;
    }

    .btn {
        transition: 0.2s;
    }

    .btn:hover {
        transform: translateY(-4px);
    }
</style>

<!-- ====================== JAVASCRIPT ====================== -->
<script>
    (function() {
        // ====================== CONFIG ======================
        const LIST_URL = "<?= base_url('booking-job/list') ?>";
        const NEXTNO_URL = "<?= base_url('booking-job/nextno') ?>";
        const STORE_URL = "<?= base_url('booking-job/store') ?>";
        const BASE_URL = "<?= base_url() ?>";
        const CSRF_NAME = "<?= csrf_token() ?>";
        const CSRF_HASH = "<?= csrf_hash() ?>";

        let currentType = 'import_lcl';

        // ====================== INIT DATATABLES ======================
        const tbl = $('#tblBookings').DataTable({
            fixedColumns: {
                left: 2, // fix 2 kolom paling kiri
                right: 2 // fix 1 kolom paling kanan
            },
            ajax: {
                url: LIST_URL,
                data: function(d) {
                    d.type = currentType;
                },
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'no_job'
                },
                {
                    data: 'no_pib_po'
                },
                {
                    data: 'consignee'
                },
                {
                    data: 'party'
                },
                {
                    data: 'eta'
                },
                {
                    data: 'pol'
                },
                {
                    data: 'shipping_line'
                },
                {
                    data: 'bl'
                },
                {
                    data: 'master_bl'
                },
                {
                    data: 'status',
                    render: function(data) {
                        if (data === 'open job') {
                            return '<span class="badge bg-primary">Open Job</span>';
                        } else if (data === 'worksheet') {
                            return '<span class="badge bg-success">Worksheet</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        // Jika status sudah "sudah kirim worksheet", maka aksi kosong
                        if (row.status === 'worksheet') {
                            return `
                            <!-- Tombol Note -->
                            <button class="btn btn-sm btn-primary btn-note" data-id="${data}" title="Print Note">
                                <i class="bi bi-sticky"></i>
                            </button>
                            <!-- Tombol Delete -->
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${data}" title="Delete Job">
                                <i class="bi bi-trash"></i>
                            </button>
                            <span class="badge bg-secondary"> Terkirim </span>
                            `;
                        }

                        return `
              <!-- Tombol Edit -->
              <button class="btn btn-sm btn-warning btn-edit" data-id="${data}" title="Edit Job">
                <i class="bi bi-pencil-square"></i>
              </button>

              <!-- Tombol Delete -->
              <button class="btn btn-sm btn-danger btn-delete" data-id="${data}" title="Delete Job">
                <i class="bi bi-trash"></i>
              </button>

              <!-- Tombol Note -->
              <button class="btn btn-sm btn-primary btn-note" data-id="${data}" title="Print Note">
                <i class="bi bi-sticky"></i>
              </button>

              <!-- Tombol Send -->
              <button class="btn btn-sm btn-success btn-send" data-id="${data}" title="Kirim ke Worksheet">
                <i class="bi bi-send"></i>
              </button>
            `;
                    }
                }
            ],
            order: [
                [0, 'desc']
            ]
        });

        // ====================== DELETE DATA ======================
        $('#tblBookings').on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang sudah dihapus akan dipindahkan ke sampah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {

                    // tampilkan loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: BASE_URL + '/booking-job/delete/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'ok') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: res.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                tbl.ajax.reload();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: res.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseText
                            });
                        }
                    });
                }
            });
        });

        // ====================== TOMBOL JENIS-JENIS JOB ======================
        $('.filter-btn').on('click', function() {
            currentType = $(this).data('type');

            // Ubah tampilan tombol aktif
            $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary active');

            // Reset pencarian & pagination, lalu reload tabel
            tbl.search('').page('first').ajax.reload(function() {}, true); // true = reset pagination ke halaman pertama
        });

        // ====================== BUKA MODAL TAMBAH ======================
        $('#btnAdd').on('click', function() {
            $('#formBooking')[0].reset();
            $('#noJob').val('');
            $('#formErrors').addClass('d-none').html('');
            $('#modalBooking').modal('show');
        });

        // ====================== GENERATE NOMOR JOB ======================
        $('#jobType').on('change', function() {
            const type = $(this).val();
            if (!type) {
                $('#noJob').val('');
                return;
            }
            $.get(NEXTNO_URL, {
                    type: type
                })
                .done(function(res) {
                    if (res.status === 'ok') {
                        $('#noJob').val(res.no_job);
                    } else {
                        $('#noJob').val('');
                        alert(res.message || 'Gagal membuat nomor job');
                    }
                })
                .fail(function() {
                    alert('Gagal request nomor job. Cek koneksi atau route.');
                });
        });

        // ====================== SUBMIT FORM TAMBAH ======================
        $('#formBooking').on('submit', function(e) {
            e.preventDefault();
            const data = $(this).serialize();

            $.ajax({
                url: STORE_URL,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modalBooking').modal('hide');
                        tbl.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal!',
                            text: res.message
                        });
                    }
                },
                error: function(xhr) {
                    let msg = 'Terjadi kesalahan saat menyimpan booking.';
                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (json.message) msg = json.message;
                    } catch (e) {
                        msg = xhr.responseText || msg;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: msg
                    });
                }
            });
        });

        // ====================== EDIT BOOKING: BUKA FORM ======================
        $('#tblBookings').on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            // tampilkan loading
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: BASE_URL + '/booking-job/edit/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    Swal.close();

                    if (res.status === 'ok') {
                        const d = res.data;

                        // isi data ke form edit
                        $('#editId').val(d.id);
                        $('#editJobType').val(d.type);
                        $('#editNoJob').val(d.no_job);
                        $('#editNoPibPo').val(d.no_pib_po);
                        $('#editConsignee').val(d.consignee);
                        $('#editParty').val(d.party);
                        $('#editEta').val(d.eta);
                        $('#editPol').val(d.pol);
                        $('#editShippingLine').val(d.shipping_line);
                        $('#editBl').val(d.bl);
                        $('#editMasterBl').val(d.master_bl);

                        // reset error
                        $('#editFormErrors').addClass('d-none').html('');

                        // buka modal
                        $('#modalEditBooking').modal('show');

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseText || 'Terjadi kesalahan saat mengambil data.'
                    });
                }
            });
        });

        // ====================== UPDATE BOOKING ======================
        $('#formEditBooking').on('submit', function(e) {
            e.preventDefault();

            const id = $('#editId').val();
            const data = $(this).serialize();

            Swal.fire({
                title: 'Menyimpan...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: BASE_URL + '/booking-job/update/' + id,
                type: 'POST',
                data: data,
                dataType: 'json',
                success: function(res) {
                    Swal.close();

                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#modalEditBooking').modal('hide');
                        tbl.ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Gagal!',
                            text: res.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    let msg = 'Terjadi kesalahan saat update booking.';
                    try {
                        const json = JSON.parse(xhr.responseText);
                        if (json.message) msg = json.message;
                    } catch (e) {
                        msg = xhr.responseText || msg;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: msg
                    });
                }
            });
        });

        // ====================== EXPORT EXCEL: OPEN MODAL & LOAD YEARS ======================
        document.getElementById('btnExport').addEventListener('click', function(e) {
            e.preventDefault();
            $('#modalExportExcel').modal('show');

            // Ambil daftar tahun dari server (otomatis dari data booking_job)
            fetch('<?= base_url('booking-job/get-years') ?>')
                .then(res => res.json())
                .then(data => {
                    const selectYear = document.getElementById('exportYear');
                    selectYear.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                    data.years.forEach(y => {
                        selectYear.innerHTML += `<option value="${y}">${y}</option>`;
                    });
                })
                .catch(() => console.error('Gagal memuat daftar tahun'));
        });

        // ====================== EXPORT EXCEL: LOAD MONTHS AFTER SELECT YEAR ======================
        document.getElementById('exportYear').addEventListener('change', function() {
            const year = this.value;
            const selectMonth = document.getElementById('exportMonth');

            // reset dulu
            selectMonth.innerHTML = '<option value="">-- Semua Bulan --</option>';

            if (!year) return;

            fetch(`<?= base_url('booking-job/get-months') ?>/${year}`)
                .then(res => res.json())
                .then(data => {
                    data.months.forEach(m => {
                        selectMonth.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                    });
                })
                .catch(() => console.error('Gagal memuat daftar bulan'));
        });

        // ====================== RESET MODAL KETIKA DITUTUP ======================
        $('#modalExportExcel').on('hidden.bs.modal', function() {
            // Reset semua field di modal
            document.getElementById('jenisExport').value = '';
            document.getElementById('exportYear').value = '';
            document.getElementById('exportMonth').innerHTML = '<option value="">-- Semua Bulan --</option>';
        });

        // ====================== EXPORT EXCEL: KONFIRMASI & DOWNLOAD ======================
        document.getElementById('btnConfirmExportExcel').addEventListener('click', function() {
            const jenis = document.getElementById('jenisExport').value;
            const tahun = document.getElementById('exportYear').value;
            const bulan = document.getElementById('exportMonth').value;

            if (!jenis || !tahun) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap!',
                    text: 'Silakan pilih jenis booking job dan tahun terlebih dahulu.'
                });
                return;
            }

            $('#modalExportExcel').modal('hide');

            // Buat URL dengan parameter
            let url = `<?= base_url('booking-job/export-excel') ?>/${jenis}?year=${tahun}`;
            if (bulan) {
                url += `&month=${bulan}`;
            }

            // Langsung redirect ke URL untuk download
            window.location.href = url;

            // Tampilkan notifikasi
            Swal.fire({
                icon: 'success',
                title: 'Export Dimulai',
                text: 'File Excel akan segera diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        });

        // ====================== EXPORT PDF: OPEN MODAL & LOAD YEARS ======================
        document.getElementById('btnExportPdf').addEventListener('click', function(e) {
            e.preventDefault();
            $('#modalExportPdf').modal('show');

            // Ambil daftar tahun dari server (otomatis dari data booking_job)
            fetch('<?= base_url('booking-job/get-years') ?>')
                .then(res => res.json())
                .then(data => {
                    const selectYear = document.getElementById('exportYearPdf');
                    selectYear.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                    data.years.forEach(y => {
                        selectYear.innerHTML += `<option value="${y}">${y}</option>`;
                    });
                })
                .catch(() => console.error('Gagal memuat daftar tahun'));
        });

        // ====================== EXPORT PDF: LOAD MONTHS AFTER SELECT YEAR ======================
        document.getElementById('exportYearPdf').addEventListener('change', function() {
            const year = this.value;
            const selectMonth = document.getElementById('exportMonthPdf');

            // reset dulu
            selectMonth.innerHTML = '<option value="">-- Semua Bulan --</option>';

            if (!year) return;

            fetch(`<?= base_url('booking-job/get-months') ?>/${year}`)
                .then(res => res.json())
                .then(data => {
                    data.months.forEach(m => {
                        selectMonth.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                    });
                })
                .catch(() => console.error('Gagal memuat daftar bulan'));
        });

        // ====================== RESET MODAL KETIKA DITUTUP ======================
        $('#modalExportPdf').on('hidden.bs.modal', function() {
            // Reset semua field di modal
            document.getElementById('jenisExportPdf').value = '';
            document.getElementById('exportYearPdf').value = '';
            document.getElementById('exportMonthPdf').innerHTML = '<option value="">-- Semua Bulan --</option>';
        });

        // ====================== EXPORT PDF: KONFIRMASI & DOWNLOAD ======================
        document.getElementById('btnConfirmExportPdf').addEventListener('click', function() {
            const jenis = document.getElementById('jenisExportPdf').value;
            const tahun = document.getElementById('exportYearPdf').value;
            const bulan = document.getElementById('exportMonthPdf').value;

            if (!jenis || !tahun) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data belum lengkap!',
                    text: 'Silakan pilih jenis booking job dan tahun terlebih dahulu.'
                });
                return;
            }

            $('#modalExportPdf').modal('hide');

            // Buat URL dengan parameter
            let url = `<?= base_url('booking-job/export-pdf') ?>/${jenis}?year=${tahun}`;
            if (bulan) {
                url += `&month=${bulan}`;
            }

            // Langsung redirect ke URL untuk download
            window.location.href = url;

            // Tampilkan notifikasi
            Swal.fire({
                icon: 'success',
                title: 'Export Dimulai',
                text: 'File PDF akan segera diunduh.',
                timer: 2000,
                showConfirmButton: false
            });
        });


        // ====================== PRINT NOTE ======================
        document.addEventListener("click", function(e) {
            if (e.target.closest(".btn-note")) {
                e.preventDefault();

                const btn = e.target.closest(".btn-note");
                const id = btn.getAttribute("data-id");

                // Gabungkan ID dengan timestamp
                const timestamp = Date.now();
                const encodedId = btoa(id + '-' + timestamp).replace(/=/g, '');

                window.open(`<?= base_url('booking-job/print-note') ?>/${encodedId}`, '_blank');
            }
        });

        // ====================== KIRIM KE WORKSHEET ======================
        $('#tblBookings').on('click', '.btn-send', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Kirim ke Worksheet?',
                text: "Data booking job ini akan dikirim ke Worksheet.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: BASE_URL + '/booking-job/send-to-worksheet/' + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {
                            Swal.close();

                            if (res.status === 'ok') {
                                // Pertama tampilkan notifikasi sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    tbl.ajax.reload();

                                    // Tanyakan apakah ingin pergi ke modul Worksheet
                                    Swal.fire({
                                        title: 'Ingin pergi ke modul Worksheet?',
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#28a745',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Ya',
                                        cancelButtonText: 'Tidak'
                                    }).then((choice) => {
                                        if (choice.isConfirmed) {
                                            window.location.href = BASE_URL + '/worksheet';
                                        }
                                        // Jika batal, tetap di halaman booking job
                                    });
                                });

                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal!',
                                    text: res.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.close();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseText || 'Terjadi kesalahan saat mengirim data ke worksheet.'
                            });
                        }
                    });
                }
            });
        });

        // ====================== REFRESH DATA ======================
        $('#btnRefresh').on('click', function() {
            // Menampilkan loading sementara (opsional)
            Swal.fire({
                title: 'Memuat data...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Reload data table
            tbl.ajax.reload(function() {
                // Tutup loading dan tampilkan notifikasi sukses setelah reload selesai
                Swal.fire({
                    icon: 'success',
                    title: 'Data sudah terbaru!',
                    timer: 1500,
                    showConfirmButton: false
                });
            }, false); // false = jangan reset pagination
        });

        // ====================== SAMPAH DATA ======================
        $('#btnTrash').on('click', function() {
            Swal.fire({
                title: 'Apakah Ingin Pidah Ke Halaman Sampah Booking Job ?',
                text: "Untuk melihat dan merestore data yang sudah kamu delete, silahkan pergi ke sampah booking job",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#435ebe',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Pindah',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Redirect...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    window.location.href = '/booking-job-trash';
                }
            });
        });

        $(document).ready(function() {
            <?php if (session()->getFlashdata('autoAdd')): ?>
                setTimeout(() => {
                    $('#btnAdd').trigger('click');
                }, 400);
            <?php endif; ?>
        });

    })();
</script>
<?= $this->endSection() ?>