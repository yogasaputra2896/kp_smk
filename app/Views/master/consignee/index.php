<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Master Consignee<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Master Data Consignee</h3>

    <p class="text-subtitle text-muted">
        Master Data Consignee digunakan untuk menyimpan informasi importir/Exportir atau penerima barang.
        Data pada modul ini akan digunakan otomatis di modul Bosuccessing Job dan Worksheet 
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
                    Tambah Consignee
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
            <table id="tblConsignee" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Consignee</th>
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
                <h5 class="modal-title">Tambah Consignee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="addErrors" class="alert alert-danger d-none"></div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <select name="kode" id="kodeConsignee" class="form-control select2" required></select>
                        <span id="kodeWarning" class="text-danger mt-1" style="display:none;">Kode sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Consignee</label>
                        <select name="nama_consignee" id="namaConsignee" class="form-control select2" required></select>
                        <span id="namaWarning" class="text-danger mt-1" style="display:none;">Nama sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <select name="npwp_consignee" id="npwpConsignee" class="form-control select2" required></select>
                        <span id="namaWarning" class="text-danger mt-1" style="display:none;">Npwp sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat_consignee" class="form-control" rows="3" required></textarea>
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
                <h5 class="modal-title">Edit Consignee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="editErrors" class="alert alert-danger d-none"></div>
                <input type="hidden" name="id" id="editId">

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Kode</label>
                        <input type="text" id="editKode" name="kode" class="form-control" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Consignee</label>
                        <input type="text" id="editNama" name="nama_consignee" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <input type="text" id="editNpwp" name="npwp_consignee" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea id="editAlamat" name="alamat_consignee" class="form-control" rows="3" required></textarea>
                    </div>


                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-warning">Update</button>
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

    /* ===========================
   CUSTOM SELECT2 MODERN STYLE
   =========================== */
    .select2-container .select2-selection--single {
        height: 45px !important;
        padding: 6px 12px;
        border: 1px solid #ced4da !important;
        border-radius: 8px !important;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 32px !important;
        font-size: 16px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100%;
        top: 10px !important;
        right: 10px !important;
    }

    .select2-dropdown {
        border-radius: 8px !important;
        border: 1px solid #aaa !important;
    }

    .select2-results__option {
        padding: 10px;
        font-size: 14px;
        border-bottom: 1px solid #f3f3f3;
    }

    .select2-search__field {
        border-radius: 6px !important;
        padding: 6px !important;
        border: 1px solid #bbb !important;
    }

    /* Jarak antar form lebih rapi */
    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
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
$(function(){

    const BASE_URL = "<?= base_url() ?>/";

    // ===================================
    // INIT DATATABLE
    // ===================================
    let tbl = $('#tblConsignee').DataTable({
        ajax: {
            url: BASE_URL + "master-data/consignee/list",
            dataSrc: 'data'
        },
        columns: [
            { data: null, render: (d,t,r,m)=> m.row + 1 },
            { data: 'kode' },
            { data: 'nama_consignee' },
            { data: 'npwp_consignee' },
            { data: 'alamat_consignee' },
            { 
                data: 'id',
                render: function(id){
                    return `
                        <button class="btn btn-sm btn-warning btn-edit" data-id="${id}">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${id}">
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
    $('#btnAdd').click(()=>{
        $('#formAdd')[0].reset();
        $('#addErrors').html('').addClass('d-none');
        $('#modalAdd').modal('show');
    });

    // ========
    // SELECT2
    // ========

    $('#modalAdd').on('shown.bs.modal', function () {

        // ==============
        // SELECT2: KODE
        // ==============
        $('#kodeConsignee').select2({
            dropdownParent: $('#modalAdd'),
            theme: "default",
            placeholder: "Masukan Kode Consignee",
            allowClear: true,
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/consignee/search/kode",
                dataType: 'json',
                delay: 300,
                data: params => ({ term: params.term }),
                processResults: data => ({ results: data })
            }
        });

        $('#kodeConsignee').off("select2:select").on("select2:select", function(e){
            if (e.params.data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Kode sudah ada!',
                    text: 'Gunakan kode lain.',
                }).then(() => {
                    $(this).val(null).trigger('change');
                });
            }
        });


        // =========================
        // SELECT2: NAMA CONSIGNEE
        // =========================
        $('#namaConsignee').select2({
            dropdownParent: $('#modalAdd'),
            theme: "default",
            placeholder: "Masukan Nama Consignee",
            allowClear: true,
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/consignee/search/nama",
                dataType: 'json',
                delay: 300,
                data: params => ({ term: params.term }),
                processResults: data => ({ results: data })
            }
        });

        $('#namaConsignee').off("select2:select").on("select2:select", function(e){
            if (e.params.data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama Consignee sudah ada!',
                    text: 'Gunakan nama lain.',
                }).then(() => {
                    $(this).val(null).trigger('change');
                });
            }
        });

        // ========================
        // SELECT2 NPWP CONSIGNEE
        // ========================
        $('#npwpConsignee').select2({
            dropdownParent: $('#modalAdd'),
            placeholder: "Masukan Npwp Consignee",
            allowClear: true,
            tags: true,
            ajax: {
                url: BASE_URL + "master-data/consignee/search/npwp",
                dataType: 'json',
                delay: 250,
                data: params => ({ term: params.term }),
                processResults: data => ({ results: data })
            }
        });

        $('#npwpConsignee').on('select2:select', function(e){
            if (e.params.data.exists) {
                Swal.fire({
                    icon: 'warning',
                    title: 'NPWP sudah terdaftar!',
                    text: 'Gunakan NPWP lain.'
                }).then(() => {
                    $(this).val(null).trigger('change');
                });
            }
        });

    });

    // ===================================
    // ADD DATA
    // ===================================
    $('#formAdd').submit(function(e){
        e.preventDefault();

        $.post(BASE_URL + "master-data/consignee/store", $(this).serialize(), function(res){

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
    $('#tblConsignee').on('click', '.btn-edit', function(){
        const id = $(this).data('id');

        $.get(BASE_URL + "master-data/consignee/edit/" + id, function(res){

            if(res.status === 'success'){
                const d = res.data;

                $('#editId').val(d.id);
                $('#editKode').val(d.kode);
                $('#editNama').val(d.nama_consignee);
                $('#editNpwp').val(d.npwp_consignee);
                $('#editAlamat').val(d.alamat_consignee);

                $('#editErrors').addClass('d-none').html("");

                $('#modalEdit').modal('show');
            } 
            else {
                Swal.fire('Error', res.message, 'error');
            }

        }, 'json');
    });

    // ===================================
    // UPDATE DATA
    // ===================================
    $('#formEdit').submit(function(e){
        e.preventDefault();

        const id = $('#editId').val();

        $.post(BASE_URL + "master-data/consignee/update/" + id, $(this).serialize(), function(res){

            if(res.status === 'success'){
                Swal.fire('Berhasil!', res.message, 'success');
                $('#modalEdit').modal('hide');
                tbl.ajax.reload();
            } 
            else {
                let err = "";
                $.each(res.errors, function(k, v){
                    err += `<li>${v}</li>`;
                });

                $('#editErrors')
                    .removeClass('d-none')
                    .html("<ul>"+err+"</ul>");
            }

        }, 'json');
    });

    // ===================================
    // DELETE DATA
    // ===================================
    $('#tblConsignee').on('click', '.btn-delete', function(){
        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(result=>{
            if(result.isConfirmed){

                $.post(BASE_URL + "master-data/consignee/delete/" + id, function(res){

                    if(res.status === 'success'){
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
    $('#modalAdd').on('hidden.bs.modal', function () {

        $('#formAdd')[0].reset();

        // Reset error
        $('#addErrors').html('').addClass('d-none');

        // Reset Select2
        $('#kodeConsignee').val(null).trigger('change');
        $('#kodeConsignee').empty();

        $('#namaConsignee').val(null).trigger('change');
        $('#namaConsignee').empty();

        $('#npwpConsignee').val(null).trigger('change');
        $('#npwpConsignee').empty();
    });





});

// INIT TOOLTIP
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
</script>


<?= $this->endSection() ?>
