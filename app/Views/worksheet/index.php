<?= $this->extend('layouts/layout') ?>

<!-- Title -->
<?= $this->section('title') ?>Worksheet<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Worksheet</h3>
    <p class="text-subtitle text-muted">
        Modul Worksheet merupakan lanjutan dari booking job yang menyediakan fitur lengkap untuk mengelola data worksheet,
        mulai dari edit, hapus, cetak note, hingga ekspor laporan ke Excel dan PDF secara periodik.
        Selain itu, tersedia fitur filter berdasarkan jenis worksheet dan pencarian data untuk memudahkan pengguna
        menemukan informasi dengan cepat dan akurat. Modul ini juga dilengkapi dengan softdelete untuk menyimpan
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
                <?php if (in_groups('admin') || in_groups('staff')): ?>
                    <button id="btnAdd" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-2"></i> Tambah Worksheet
                    </button>
                <?php endif; ?>
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
                <button id="btnTrash" class="btn btn-danger" title="Sampah Worksheet">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">Jenis Worksheet</h6>
        </div>

        <!-- ====================== FILTER JENIS WORKSHEET ====================== -->
        <div class="mb-3 btn-group" role="group" aria-label="Filter worksheet">
            <button type="button" class="btn btn-primary filter-btn active" data-type="import">Worksheet Import</button>
            <button type="button" class="btn btn-outline-primary filter-btn" data-type="export">Worksheet Export</button>
        </div>

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
</style>

<!-- ====================== JAVASCRIPT ====================== -->
<script>
    (function() {
        // ====================== CONFIG ======================
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
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${row.id}" title="Edit Worksheet">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" title="Delete Worksheet">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-primary btn-note" data-id="${row.id}" title="Print Worksheet">
                                <i class="bi bi-sticky"></i>
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
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id}" title="Delete Worksheet">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-primary btn-note" data-id="${row.id}" title="Print Worksheet">
                                <i class="bi bi-sticky"></i>
                            </button>
                        `;
                    }
                }
            ];

            // ====================== INIT DATATABLES ======================
            tbl = $('#tblWorksheet').DataTable({
                fixedColumns: {
                    left: 2, // fix 2 kolom paling kiri
                    right: 2 // fix 1 kolom paling kanan
                },
                ajax: {
                    url: LIST_URL,
                    data: {
                        type: type
                    },
                    dataSrc: 'data'
                },
                columns: type === 'import' ? columnsImport : columnsExport,
                order: [
                    [0, 'asc']
                ]
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