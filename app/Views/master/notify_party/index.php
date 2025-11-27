<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Master Notify Party<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Master Data Notify Party</h3>

    <p class="text-subtitle text-muted">
        Master Data Notify Party digunakan untuk menyimpan informasi Notify Party atau pihak ketiga.
        Data pada modul ini akan digunakan otomatis di modul Booking Job dan Worksheet
        agar proses input lebih cepat, konsisten dan tidak terjadi kesalahan penulisan data.
    </p>
</div>
<?= $this->endSection() ?>

<!-- ====================== CONTENT ====================== -->
<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">

        <!-- TOOLBAR -->
        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex gap-2 flex-wrap">
                <button id="btnAdd" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>
                    Tambah Notify Party
                </button>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button id="btnRefresh" class="btn btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <hr class="border-2 border-primary">

        <!-- TABLE -->
        <div class="table-responsive">
            <table id="tblNotify" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Notify Party</th>
                        <th>NPWP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<!-- ====================== MODAL TAMBAH ====================== -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formAdd" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Notify Party</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="addErrors" class="alert alert-danger d-none"></div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <select name="kode" id="kodeNotify" class="form-control select2" required></select>
                        <span id="kodeWarning" class="text-danger mt-1" style="display:none;">Kode sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Notify Party</label>
                        <select name="nama_notify" id="namaNotify" class="form-control select2" required></select>
                        <span id="namaWarning" class="text-danger mt-1" style="display:none;">Nama sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <select name="npwp_notify" id="npwpNotify" class="form-control select2" required></select>
                        <span id="namaWarning" class="text-danger mt-1" style="display:none;">Npwp sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat_notify" class="form-control" rows="3" placeholder="Masukan Alamat Notify Party" required></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan</button>
            </div>

        </form>
    </div>
</div>

<!-- ====================== MODAL EDIT ====================== -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEdit" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Notify Party</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="editErrors" class="alert alert-danger d-none"></div>
                <input type="hidden" name="id" id="editId">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <select name="kode" id="editkodeNotify" class="form-control select2" required></select>
                        <span id="editKodeWarning" class="text-danger mt-1" style="display:none;">Kode sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Consignee</label>
                        <select name="nama_notify" id="editNamaNotify" class="form-control select2" required></select>
                        <span id="editNamaWarning" class="text-danger mt-1" style="display:none;">Nama sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <select name="npwp_notify" id="editNpwpNotify" class="form-control select2" required></select>
                        <span id="editNpwpWarning" class="text-danger mt-1" style="display:none;">NPWP sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea id="editAlamat" name="alamat_notify" class="form-control" rows="3" required></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>
                <button class="btn btn-warning">
                    <i class="bi bi-pencil-square me-1"></i> Update
                </button>
            </div>

        </form>
    </div>
</div>



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

    .btn {
        transition: 0.2s;
    }

    .btn:hover {
        transform: translateY(-4px);
    }

    /* ===============================
   SELECT2 - MODERN PREMIUM STYLE
   =============================== */

    .select2-container .select2-selection--single {
        height: 48px !important;
        padding: 8px 14px;
        border: 1px solid #d0d7de !important;
        border-radius: 10px !important;
        background: #ffffff;
        display: flex;
        align-items: center;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: 0.2s ease-in-out;
    }

    /* Perbaiki border input search Select2 agar tidak hitam */
    .select2-container .select2-dropdown .select2-search__field {
        border: 1px solid #435ebe !important;
        /* warna border */
        outline: none !important;
        box-shadow: none !important;
        border-radius: 8px !important;
        padding: 8px 10px !important;
        color: #2d2d2d !important;
    }

    /* Saat fokus */
    .select2-container .select2-dropdown .select2-search__field:focus {
        border: 1px solid #2f49d1 !important;
        box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.25) !important;
    }


    /* Perbaiki border input search Select2 agar tidak hitam */
    .select2-container .select2-dropdown .select2-search__field {
        border: 1px solid #435ebe !important;
        /* warna border */
        outline: none !important;
        box-shadow: none !important;
        border-radius: 8px !important;
        padding: 8px 10px !important;
        color: #2d2d2d !important;
    }

    /* Saat fokus */
    .select2-container .select2-dropdown .select2-search__field:focus {
        border: 1px solid #2f49d1 !important;
        box-shadow: 0 0 0 3px rgba(67, 94, 190, 0.25) !important;
    }


    /* Focused */
    .select2-container--open .select2-selection--single {
        border-color: #435ebe !important;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    /* Text di dalam Select */
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
        font-size: 15px;
        color: #2d2d2d;
    }

    /* Posisikan arrow Select2 di tengah */
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 50% !important;
        transform: translateY(-50%);
        right: 12px !important;
        height: auto !important;
    }


    /* Dropdown */
    .select2-dropdown {
        border-radius: 10px !important;
        border: 1px solid #435ebe !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.10);
        padding-top: 5px;
    }

    /* Item di dropdown */
    .select2-results__option {
        padding: 10px 14px;
        font-size: 14px;
        border-bottom: 1px solid #eef1f4;
    }

    /* Hover item */
    .select2-results__option--highlighted {
        background-color: #e8f0fe !important;
        color: #000 !important;
    }

    /* Input search di dropdown */
    .select2-search__field {
        border-radius: 8px !important;
        border-color: #435ebe;
        padding: 8px !important;
        border: 1px solid #b8bec5 !important;
        margin-bottom: 5px;
    }

    .select2-container .select2-selection__placeholder {
        color: #607080 !important;
        /* ubah warna sesuai kebutuhan */

    }

    /* Label form lebih rapi */
    .form-label {
        font-weight: 600;
        margin-bottom: 6px;
        color: #3a3a3a;
    }
</style>

<?= $this->endSection() ?>

<!-- ====================== PAGE SCRIPTS ====================== -->
<?= $this->section('pageScripts') ?>
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/select2/dist/css/select2.min.css') ?>">
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>

<script>
    $(function() {

        const BASE_URL = "<?= base_url() ?>/";

        // ===================================
        // INIT DATATABLE
        // ===================================
        let tbl = $('#tblNotify').DataTable({
            ajax: {
                url: BASE_URL + "master-data/notify-party/list",
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1
                },
                {
                    data: 'kode'
                },
                {
                    data: 'nama_notify'
                },
                {
                    data: 'npwp_notify'
                },
                {
                    data: 'alamat_notify',
                    render: function(data) {
                        if (!data) return "-";
                        return data.length > 25 ? data.substring(0, 25) + "..." : data;
                    }
                },
                {
                    data: 'id',
                    render: function(id) {
                        return `
                        <button class="btn btn-sm btn-warning btn-edit mb-2" data-id="${id}" title="Edit Notify Party">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete mb-2" data-id="${id}" title="Delete Notify Party">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    }
                }
            ]
        });

        // ===================================
        // RELOAD DATA
        // ===================================
        $('#btnRefresh').on('click', function() {
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            tbl.ajax.reload(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Data sudah diperbarui!',
                    timer: 1200,
                    showConfirmButton: false
                });
            }, false);
        });

        // ===================================
        // SHOW ADD MODAL
        // ===================================
        $('#btnAdd').click(() => {
            $('#formAdd')[0].reset();
            $('#addErrors').html('').addClass('d-none');
            $('#modalAdd').modal('show');
        });

        /// ================================
        //  INIT SELECT2 DALAM MODAL ADD
        // ================================
        $('#modalAdd').on('shown.bs.modal', function() {

            // ==========================
            // FUNGSI CEK DATA EXISTS
            // ==========================
            function checkExisting(element, title) {
                $(element).off("select2:select").on("select2:select", function(e) {
                    if (e.params.data.exists) {
                        Swal.fire({
                            icon: 'warning',
                            title: title + ' sudah terdaftar!',
                            text: 'Gunakan ' + title.toLowerCase() + ' lain.'
                        }).then(() => {
                            $(this).val(null).trigger('change');
                        });
                    }
                });
            }

            // ==========================
            // FUNGSI SELECT2
            // ==========================
            function initSelect2(selector, url, placeholder) {
                $(selector).select2({
                    dropdownParent: $('#modalAdd'),
                    theme: "default",
                    placeholder: placeholder,
                    allowClear: true,
                    tags: true,
                    width: "100%",
                    ajax: {
                        url: BASE_URL + url,
                        dataType: 'json',
                        delay: 0,
                        data: params => ({
                            term: params.term
                        }),
                        processResults: data => ({
                            results: data.map(item => ({
                                id: item.id,
                                text: item.text.toUpperCase(), // <=== dropdown uppercase
                                exists: item.exists
                            }))
                        })
                    }
                });

                // =============================
                // BUAT SEMUA DROPDOWN UPPERCASE
                // =============================
                $(selector).on('select2:open', function() {
                    setTimeout(() => {
                        $('.select2-results__option').each(function() {
                            $(this).text($(this).text().toUpperCase());
                        });
                    }, 10);
                });
            }

            // ============================================
            // 1. SELECT2 KODE CONSIGNEE (AUTO UPPERCASE)
            // ============================================
            initSelect2(
                '#kodeNotify',
                "master-data/notify-party/search/kode",
                "Masukan Kode Notify Party"
            );
            checkExisting('#kodeNotify', "Kode Notify Party");

            // Auto uppercase + minimal 4 huruf
            $('#kodeNotify').on('select2:select', function(e) {
                let val = $(this).val();
                if (!val) return;

                val = val.toString().toUpperCase();
                $(this).val(val).trigger('change.select2');

                if (val.length < 4) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Minimal 4 Karakter!',
                        text: 'Kode Notify Party harus terdiri dari minimal 4 karakter.'
                    }).then(() => {
                        $(this).val(null).trigger('change');
                    });
                } else if (val.length > 6) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maximal 6 Karakter!',
                        text: 'Kode Notify Party harus terdiri dari maksimal 6 karakter.'
                    }).then(() => {
                        $(this).val(null).trigger('change');
                    });
                }
            });

            // ==========================================
            // REALTIME UPPERCASE UNTUK DROPDOWN SELECT2
            // ==========================================
            $(document).on('keyup', ".select2-search__field", function() {
                // Uppercase untuk input search
                $(this).val($(this).val().toUpperCase());

                // Realtime ubah semua opsi dropdown
                $('.select2-results__option').each(function() {
                    $(this).text($(this).text().toUpperCase());
                });
            });

            // Saat dropdown muncul, apply juga langsung
            $(document).on('select2:open', function() {
                setTimeout(() => {
                    $('.select2-results__option').each(function() {
                        $(this).text($(this).text().toUpperCase());
                    });
                }, 10);
            });



            // ============================
            // 2. SELECT2 NAMA Notify Party
            // ============================
            initSelect2(
                '#namaNotify',
                "master-data/notify-party/search/nama",
                "Masukan Nama Notify Party"
            );
            checkExisting('#namaNotify', "Nama Notify Party");


            // ============================
            // 3. SELECT2 NPWP CONSIGNEE
            // ============================
            initSelect2(
                '#npwpNotify',
                "master-data/notify-party/search/npwp",
                "Masukan NPWP Notify Party"
            );
            checkExisting('#npwpNotify', "NPWP");

        });


        // ======================
        // SELECT2 EDIT MODAL
        // ======================
        $('#modalEdit').on('shown.bs.modal', function() {

            // === KODE ===
            $('#editkodeNotify').select2({
                dropdownParent: $('#modalEdit'),
                placeholder: "Masukan Kode Notify Party",
                tags: true,
                width: "100%",
                ajax: {
                    url: BASE_URL + "master-data/notify-party/search/kode",
                    dataType: 'json',
                    delay: 0,
                    data: params => ({
                        term: params.term
                    }),
                    processResults: data => ({
                        results: data
                    })
                }
            });

            // === NAMA ===
            $('#editNamaNotify').select2({
                dropdownParent: $('#modalEdit'),
                placeholder: "Masukan Nama Notify Party",
                tags: true,
                width: "100%",
                ajax: {
                    url: BASE_URL + "master-data/notify-party/search/nama",
                    dataType: 'json',
                    delay: 0,
                    data: params => ({
                        term: params.term
                    }),
                    processResults: data => ({
                        results: data
                    })
                }
            });

            // === NPWP ===
            $('#editNpwpNotify').select2({
                dropdownParent: $('#modalEdit'),
                placeholder: "Masukan NPWP Notify Party",
                tags: true,
                width: "100%",
                ajax: {
                    url: BASE_URL + "master-data/notify-party/search/npwp",
                    dataType: 'json',
                    delay: 0,
                    data: params => ({
                        term: params.term
                    }),
                    processResults: data => ({
                        results: data
                    })
                }
            });

        });



        // ===================================
        // ADD DATA
        // ===================================
        $('#formAdd').submit(function(e) {
            e.preventDefault();

            $.post(BASE_URL + "master-data/notify-party/store", $(this).serialize(), function(res) {

                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message
                    });
                    $('#modalAdd').modal('hide');
                    tbl.ajax.reload();
                } else if (res.status === 'error') {

                    let errorList = '';
                    if (res.errors) {
                        Object.keys(res.errors).forEach(key => {
                            errorList += `• ${res.errors[key]}<br>`;
                        });
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Menyimpan!',
                        html: errorList || 'Terjadi kesalahan.'
                    });
                }


            }, 'json');
        });

        // ===================================
        // EDIT SHOW DATA
        // ===================================
        $('#tblNotify').on('click', '.btn-edit', function() {
            const id = $(this).data('id');

            $.get(BASE_URL + "master-data/notify-party/edit/" + id, function(res) {

                if (res.status === 'success') {
                    const d = res.data;

                    $('#editId').val(d.id);

                    // SET VALUE SELECT2 (harus append data dulu)
                    let kodeOption = new Option(d.kode, d.kode, true, true);
                    $('#editkodeNotify').append(kodeOption).trigger('change');

                    let namaOption = new Option(d.nama_notify, d.nama_notify, true, true);
                    $('#editNamaNotify').append(namaOption).trigger('change');

                    let npwpOption = new Option(d.npwp_notify, d.npwp_notify, true, true);
                    $('#editNpwpNotify').append(npwpOption).trigger('change');

                    $('#editAlamat').val(d.alamat_notify);

                    $('#modalEdit').modal('show');
                } else {
                    Swal.fire('Error', res.message, 'error');
                }

            }, 'json');
        });


        // ===================================
        // UPDATE DATA
        // ===================================
        $('#formEdit').submit(function(e) {
            e.preventDefault();

            const id = $('#editId').val();

            $.post(BASE_URL + "master-data/notify-party/update/" + id, $(this).serialize(), function(res) {

                if (res.status === 'success') {
                    Swal.fire('Berhasil!', res.message, 'success');
                    $('#modalEdit').modal('hide');
                    tbl.ajax.reload();
                } else {
                    let err = "";
                    $.each(res.errors, function(k, v) {
                        err += `<li>${v}</li>`;
                    });

                    $('#editErrors')
                        .removeClass('d-none')
                        .html("<ul>" + err + "</ul>");
                }

            }, 'json');
        });

        // ===================================
        // DELETE DATA
        // ===================================
        $('#tblNotify').on('click', '.btn-delete', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {

                    $.post(BASE_URL + "master-data/notify-party/delete/" + id, function(res) {

                        if (res.status === 'success') {
                            Swal.fire('Terhapus!', res.message, 'success');
                            tbl.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }

                    }, 'json');

                }
            });
        });

        // =====================================================
        // RESET MODAL ADD SAAT DITUTUP
        // =====================================================
        $('#modalAdd').on('hidden.bs.modal', function() {

            $('#formAdd')[0].reset();

            // Reset error
            $('#addErrors').html('').addClass('d-none');

            // Reset Select2
            $('#kodeNotify').val(null).trigger('change');
            $('#kodeNotify').empty();

            $('#namaNotify').val(null).trigger('change');
            $('#namaNotify').empty();

            $('#npwpNotify').val(null).trigger('change');
            $('#npwpNotify').empty();
        });





    });

    // INIT TOOLTIP
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
</script>


<?= $this->endSection() ?>