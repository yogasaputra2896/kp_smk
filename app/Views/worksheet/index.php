<?= $this->extend('layouts/layout') ?>

<!-- Title -->
<?= $this->section('title') ?>Worksheet<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Worksheet</h3>

    <!-- DESKTOP: versi lengkap -->
    <p class="text-subtitle text-muted d-none d-sm-block">
        Modul Worksheet merupakan lanjutan dari booking job yang menyediakan fitur lengkap untuk mengelola data worksheet,
        mulai dari edit, hapus, cetak note, hingga ekspor laporan ke Excel dan PDF secara periodik.
        Selain itu, tersedia fitur filter berdasarkan jenis worksheet dan pencarian data untuk memudahkan pengguna
        menemukan informasi dengan cepat dan akurat. Modul ini juga dilengkapi dengan softdelete untuk menyimpan
        data yang telah dihapus sebelum benar-benar dihapus permanen, serta refresh data untuk memperbarui tampilan
        data secara real-time.
    </p>

    <!-- MOBILE: versi ringkas -->
    <p class="text-subtitle text-muted d-block d-sm-none">
        Modul Worksheet menyediakan fitur edit, hapus, cetak note, ekspor laporan, filter, serta pencarian data.
        Softdelete & refresh data juga tersedia.
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

                <?php if (in_groups('admin') || in_groups('staff')): ?>
                    <!-- Tambah Worksheet -->
                    <button id="btnAdd" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i>
                        <span class="d-none d-sm-inline">Tambah Worksheet</span>
                        <span class="d-inline d-sm-none">Add</span>
                    </button>
                <?php endif; ?>

                <!-- Export Excel -->
                <button class="btn btn-success" id="btnExportWorksheet">
                    <i class="bi bi-file-earmark-spreadsheet me-2"></i>
                    <span class="d-none d-sm-inline">Export Excel</span>
                    <span class="d-inline d-sm-none">Xls</span>
                </button>

                <!-- Export PDF -->
                <button id="btnExportWorksheePdf" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf me-2"></i>
                    <span class="d-none d-sm-inline">Export Pdf</span>
                    <span class="d-inline d-sm-none">Pdf</span>
                </button>

            </div>

            <!-- Tombol kanan -->
            <div class="d-flex gap-2 flex-wrap ms-2">
                <button id="btnRefresh" class="btn btn-secondary" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <button id="btnTrash" class="btn btn-danger" title="Sampah Worksheet">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

        </div>
        <hr class="border-2 border-primary">

        <!-- ======== TITLE FILTER ======== -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Jenis Worksheet</h6>
        </div>

        <!-- ====================== FILTER WORKSHEET (responsive) ====================== -->
        <div class="mb-3 btn-group flex-wrap" role="group" aria-label="Filter worksheet">

            <!-- Import -->
            <button type="button" class="btn btn-outline-primary filter-btn active" data-type="import">
                <span class="d-none d-sm-inline">Worksheet Import</span>
                <span class="d-inline d-sm-none">Import</span>
            </button>

            <!-- Export -->
            <button type="button" class="btn btn-outline-primary filter-btn" data-type="export">
                <span class="d-none d-sm-inline">Worksheet Export</span>
                <span class="d-inline d-sm-none">Export</span>
            </button>

        </div>

        <br>
        <br>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Daftar Worksheet</h6>
        </div>


        <!-- ====================== TABLE WORKSHEET ====================== -->
        <div class="table-responsive">
            <table id="tblWorksheet" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead></thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<!-- ====================== MODAL: EXPORT WORKSHEET (GABUNGAN) ====================== -->
<div class="modal fade" id="modalExportExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Export Worksheet (Excel)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExportWorksheet">

                    <!-- Pilih Jenis Worksheet -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Worksheet</label>
                        <select id="wsType" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="import">Worksheet Import</option>
                            <option value="export">Worksheet Export</option>
                        </select>
                    </div>

                    <!-- Periode -->
                    <div class="row">
                        <h6>Periode</h6>
                        <hr>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tahun</label>
                            <select id="wsYear" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bulan</label>
                            <select id="wsMonth" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>

                <button class="btn btn-success" id="btnExportCombined">
                    <i class="bi bi-download me-1"></i> Export
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ====================== MODAL EXPORT EXCEL ====================== -->
<div class="modal fade" id="modalExportExcel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Export Worksheet (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExportWorksheet">

                    <!-- PILIH JENIS WORKSHEET -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Worksheet</label>
                        <select id="wsType" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="import">Worksheet Import</option>
                            <option value="export">Worksheet Export</option>
                        </select>
                    </div>

                    <!-- PERIODE -->
                    <h6>Periode</h6>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tahun</label>
                            <select id="wsYear" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bulan</label>
                            <select id="wsMonth" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-danger" id="btnExportPDF">
                    <i class="bi bi-download me-1"></i> Export
                </button>
            </div>

        </div>
    </div>
</div>


<!-- ====================== MODAL EXPORT PDF ====================== -->
<div class="modal fade" id="modalExportPDF" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="modal-title">Export Worksheet (PDF)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="formExportWorksheetPDF">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Worksheet</label>
                        <select id="wsTypePDF" class="form-select" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="import">Worksheet Import</option>
                            <option value="export">Worksheet Export</option>
                        </select>
                    </div>

                    <h6>Periode</h6>
                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tahun</label>
                            <select id="wsYearPDF" class="form-select" required>
                                <option value="">-- Pilih Tahun --</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Bulan</label>
                            <select id="wsMonthPDF" class="form-select">
                                <option value="">-- Semua Bulan --</option>
                            </select>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-danger" id="btnExportPDFNow">
                    <i class="bi bi-download me-1"></i> Export
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

    #tblWorksheet th,
    #tblWorksheet td {
        font-size: 15px;
    }

    #tblWorksheet th:nth-child(3),
    #tblWorksheet td:nth-child(3) {
        min-width: 250px !important;
    }

    #tblWorksheet th:nth-child(4),
    #tblWorksheet td:nth-child(4) {
        min-width: 200px !important;
    }

    #tblWorksheet th:nth-child(6),
    #tblWorksheet td:nth-child(6) {
        min-width: 75px !important;
    }


    #tblWorksheet th:nth-child(9),
    #tblWorksheet td:nth-child(9) {
        min-width: 150px !important;
    }

    #tblWorksheet {
        width: 100% !important;
    }

    #tblWorksheet th,
    #tblWorksheet td {
        white-space: nowrap;
        /* biar teks tidak turun ke bawah */
        font-size: 15px;
    }

    .dataTables_wrapper .dataTables_scroll {
        overflow: auto;
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
        const BASE_URL = "<?= base_url() ?>";
        const LIST_URL = "<?= base_url('worksheet/list') ?>";
        let tbl;

        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        let currentType = getQueryParam('type') || 'import';

        $('.filter-btn').removeClass('btn-primary active').addClass('btn-outline-primary');
        $(`.filter-btn[data-type="${currentType}"]`)
            .removeClass('btn-outline-primary')
            .addClass('btn-primary active');



        function loadTable(type) {
            if (tbl) {
                tbl.destroy();
                $('#tblWorksheet').empty(); // reset header & body
            }

            // definisi kolom import
            const columnsImport = [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    title: 'No'
                },
                {
                    data: 'no_ws',
                    title: 'No Worksheet'
                },
                {
                    data: 'no_aju',
                    title: 'No Aju PIB'
                },
                {
                    data: 'consignee',
                    title: 'Consignee'
                },
                {
                    data: 'party',
                    title: 'Party'
                },
                {
                    data: 'eta',
                    title: 'ETA'
                },
                {
                    data: 'pol',
                    title: 'POL'
                },
                {
                    data: 'bl',
                    title: 'BL'
                },
                {
                    data: 'master_bl',
                    title: 'Master BL'
                },
                {
                    data: 'shipping_line',
                    title: 'Shipping Line'
                },
                {
                    data: 'status',
                    title: 'Status',
                    render: function(data) {
                        if (data === 'not completed') {
                            return '<span class="badge bg-warning">Not Completed</span>';
                        } else if (data === 'completed') {
                            return '<span class="badge bg-success">Completed</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: null,
                    title: 'Aksi',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                        <!-- Tombol Edit -->
                        <button class="btn btn-sm btn-warning btn-edit me-1" 
                                data-id="${row.id}" 
                                title="Edit Worksheet">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Tombol Delete -->
                        <button class="btn btn-sm btn-danger btn-delete me-1" 
                                data-id="${row.id}" data-no_ws="${row.no_ws}" title="Delete Worksheet">
                            <i class="bi bi-trash"></i>
                        </button>

                        <!-- Tombol Print -->
                        <button class="btn btn-sm btn-primary btn-print" 
                                data-id="${row.id}" 
                                title="Print Worksheet">
                            <i class="bi bi-printer"></i>
                        </button>

                        `;
                    }
                }
            ];



            // definisi kolom export
            const columnsExport = [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    title: 'No'
                },
                {
                    data: 'no_ws',
                    title: 'No Worksheet'
                },
                {
                    data: 'no_aju',
                    title: 'No Aju PEB'
                },
                {
                    data: 'shipper',
                    title: 'Shipper/Exportir'
                },
                {
                    data: 'party',
                    title: 'Party'
                },
                {
                    data: 'etd',
                    title: 'ETD'
                },
                {
                    data: 'pod',
                    title: 'POD'
                },
                {
                    data: 'bl',
                    title: 'BL'
                },
                {
                    data: 'master_bl',
                    title: 'Master BL'
                },
                {
                    data: 'shipping_line',
                    title: 'Shipping Line'
                },
                {
                    data: 'status',
                    title: 'Status',
                    render: function(data) {
                        if (data === 'not completed') {
                            return '<span class="badge bg-warning">Not Completed</span>';
                        } else if (data === 'completed') {
                            return '<span class="badge bg-success">Completed</span>';
                        } else {
                            return data;
                        }
                    }
                },
                {
                    data: null,
                    title: 'Aksi',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}" title="Edit Worksheet">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete me-1" 
                                    data-id="${row.id}" data-no_ws="${row.no_ws}" title="Delete Worksheet">
                                <i class="bi bi-trash"></i>
                            </button>

                            <!-- Tombol print -->
                            <button class="btn btn-sm btn-primary btn-print" data-id="${row.id}" title="Print Worksheet">
                                <i class="bi bi-printer"></i>
                            </button>
                        `;
                    }
                }
            ];

            // ====================== INIT DATATABLES ======================
            let isMobile = $(window).width() < 768;

            tbl = $('#tblWorksheet').DataTable({
                fixedColumns: isMobile ? false : {
                    left: 2,  // fix kolom kiri
                    right: 2  // fix kolom kanan
                },
                scrollX: true, // WAJIB agar mobile tetap bisa scroll
                ajax: {
                    url: LIST_URL,
                    data: {
                        type: type
                    },
                    dataSrc: 'data'
                },
                columns: type === 'import' ? columnsImport : columnsExport,
                order: [[0, 'DESC']]
            });

        }

        // load default import
        loadTable(currentType);

        // event filter
        $('.filter-btn').on('click', function() {
            currentType = $(this).data('type');
            $('.filter-btn').removeClass('btn-primary active').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
            loadTable(currentType);
        });

        // ====================== TAMBAH WORKSHEET ======================
        $('#btnAdd').on('click', function() {
            Swal.fire({
                title: 'Apakah Ingin Pindah Ke Halaman Booking Job ?',
                text: "Untuk menambahkan worksheet anda harus membuat booking job terlebih dahulu dan mengirimkan datanya ke worksheet.",
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
                    window.location.href = '/worksheet/redirectToBooking';
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

        // ====================== SAMPAH WORKSHEET ======================
        $('#btnTrash').on('click', function() {
            let trashUrl = currentType === 'export'
                ? '<?= base_url('worksheet-export-trash') ?>'
                : '<?= base_url('worksheet-import-trash') ?>';

            // Redirect ke halaman trash
            window.location.href = trashUrl;
        });


        // ====================== EDIT WORKSHEET ======================
        $('#tblWorksheet').on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            // Pastikan tipe worksheet aktif (import/export)
            const type = currentType;

            Swal.fire({
                title: 'Memuat Halaman Edit...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Redirect ke halaman edit sesuai jenis worksheet
            if (type === 'import') {
                window.location.href = `/worksheet/import/edit/${id}`;
            } else if (type === 'export') {
                window.location.href = `/worksheet/export/edit/${id}`;
            }
        });

        // ====================== PRINT WORKSHEET ======================
        document.addEventListener("click", function(e) {
            if (e.target.closest(".btn-print")) {
                e.preventDefault();

                const btn = e.target.closest(".btn-print");
                const id = btn.getAttribute("data-id");

                // Gabungkan ID dengan timestamp
                const timestamp = Date.now();
                const encodedId = btoa(id + '-' + timestamp).replace(/=/g, '');

                // cek apakah yang aktif import atau export
                if (currentType === 'import') {
                    window.open(`<?= base_url('worksheet/print-import') ?>/${encodedId}`, '_blank');
                } else {
                    window.open(`<?= base_url('worksheet/print-export') ?>/${encodedId}`, '_blank');
                }
            }
        });

        // ====================== DELETE WORKSHEET ======================
        $('#tblWorksheet').on('click', '.btn-delete', function () {
            const id = $(this).data('id');
            const no_ws = $(this).data('no_ws'); // jika tombol punya data-no_ws

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Data worksheet dengan nomor: '${no_ws}' akan dihapus!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (!result.isConfirmed) return;

                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                // pilih controller berdasarkan jenis (import/export)
                const deleteUrl =
                    currentType === 'import'
                        ? `${BASE_URL}/worksheet/import/delete/${id}`
                        : `${BASE_URL}/worksheet/export/delete/${id}`;

                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        Swal.close();

                        if (res.status === 'ok') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            tbl.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message || 'Terjadi kesalahan.'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseText || 'Terjadi kesalahan pada server.'
                        });
                    }
                });
            });
        });

         // ====================== OPEN MODAL ======================
        document.getElementById('btnExportWorksheet').addEventListener('click', function () {
            $('#modalExportExcel').modal('show');

            // Default = Import
            fetch('<?= base_url("worksheet-import/get-years") ?>')
                .then(res => res.json())
                .then(data => {
                    const yearSelect = document.getElementById('wsYear');
                    yearSelect.innerHTML = `<option value="">-- Pilih Tahun --</option>`;
                    data.years.forEach(y => {
                        yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
                    });
                });
        });

        // ====================== CHANGE TYPE → RELOAD YEARS ======================
        document.getElementById('wsType').addEventListener('change', function () {
            const type = this.value;

            const yearsUrl = 
                type === 'export'
                ? '<?= base_url("worksheet-export/get-years") ?>'
                : '<?= base_url("worksheet-import/get-years") ?>';

            // Load tahun berdasarkan type
            fetch(yearsUrl)
                .then(res => res.json())
                .then(data => {
                    const yearSelect = document.getElementById('wsYear');
                    yearSelect.innerHTML = `<option value="">-- Pilih Tahun --</option>`;
                    data.years.forEach(y => {
                        yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
                    });
                });

            // Reset bulan
            document.getElementById('wsMonth').innerHTML = `<option value="">-- Semua Bulan --</option>`;
        });

        // ====================== LOAD MONTHS ======================
        document.getElementById('wsYear').addEventListener('change', function () {

            const type = document.getElementById('wsType').value;
            const year = this.value;

            if (!type || !year) return;

            const monthsUrl = 
                type === 'export'
                ? '<?= base_url("worksheet-export/get-months") ?>'
                : '<?= base_url("worksheet-import/get-months") ?>';

            fetch(`${monthsUrl}/${year}`)
                .then(res => res.json())
                .then(data => {
                    const monthSelect = document.getElementById('wsMonth');
                    monthSelect.innerHTML = `<option value="">-- Semua Bulan --</option>`;
                    data.months.forEach(m => {
                        monthSelect.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                    });
                });
        });

        // ====================== EXPORT NOW ======================
        document.getElementById('btnExportCombined').addEventListener('click', function () {

            const type = document.getElementById('wsType').value;
            const year = document.getElementById('wsYear').value;
            const month = document.getElementById('wsMonth').value;

            if (!type) {
                alert('Silakan pilih jenis worksheet dahulu!');
                return;
            }

            const exportUrl = 
                type === 'export'
                ? '<?= base_url("worksheet-export/export-excel") ?>'
                : '<?= base_url("worksheet-import/export-excel") ?>';

            // Redirect to download file
            window.location.href = `${exportUrl}?year=${year}&month=${month}`;
        });

        // ====================== OPEN MODAL ======================
        // OPEN MODAL PDF
        document.getElementById('btnExportWorksheePdf').addEventListener('click', function () {
            $('#modalExportPDF').modal('show');
            loadYearsPDF("import");
        });

        // LOAD TAHUN PDF
        function loadYearsPDF(type) {
            const url = type === 'export'
                ? '<?= base_url("worksheet-export/get-years") ?>'
                : '<?= base_url("worksheet-import/get-years") ?>';

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const yearSelect = document.getElementById('wsYearPDF');
                    yearSelect.innerHTML = `<option value="">-- Pilih Tahun --</option>`;
                    data.years.forEach(y => {
                        yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
                    });
                });

            document.getElementById('wsMonthPDF').innerHTML = `<option value="">-- Semua Bulan --</option>`;
        }

        // CHANGE TYPE PDF
        document.getElementById('wsTypePDF').addEventListener('change', function () {
            loadYearsPDF(this.value);
        });

        // LOAD BULAN PDF
        document.getElementById('wsYearPDF').addEventListener('change', function () {
            const type = document.getElementById('wsTypePDF').value;
            const year = this.value;
            if (!type || !year) return;

            const url = type === 'export'
                ? '<?= base_url("worksheet-export/get-months") ?>'
                : '<?= base_url("worksheet-import/get-months") ?>';

            fetch(`${url}/${year}`)
                .then(res => res.json())
                .then(data => {
                    const monthSelect = document.getElementById('wsMonthPDF');
                    monthSelect.innerHTML = `<option value="">-- Semua Bulan --</option>`;
                    data.months.forEach(m => {
                        monthSelect.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                    });
                });
        });

        // EXPORT PDF
        document.getElementById('btnExportPDFNow').addEventListener('click', function () {
            const type  = document.getElementById('wsTypePDF').value;
            const year  = document.getElementById('wsYearPDF').value;
            const month = document.getElementById('wsMonthPDF').value;

            if (!type) return alert("Pilih jenis worksheet!");
            if (!year) return alert("Pilih tahun!");

            const url = type === 'export'
                ? '<?= base_url("worksheet-export/export-pdf") ?>'
                : '<?= base_url("worksheet-import/export-pdf") ?>';

            window.location.href = `${url}?year=${year}&month=${month}`;
        });




    })();

    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
    <?php endif; ?>

</script>
<?= $this->endSection() ?>