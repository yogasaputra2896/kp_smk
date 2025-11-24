<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Master Consignee<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Master Data Consignee</h3>

    <p class="text-subtitle text-muted">
        Master Data Consignee digunakan untuk menyimpan informasi importir atau penerima barang.
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
                        <th width="120px">Aksi</th>
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

                    <div class="col-md-4">
                        <label class="form-label">Kode</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Consignee</label>
                        <input type="text" name="nama_consignee" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NPWP</label>
                        <input type="text" name="npwp_consignee" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="alamat_consignee" class="form-control">
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary">Simpan</button>
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
                        <input type="text" id="editNpwp" name="npwp_consignee" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <input type="text" id="editAlamat" name="alamat_consignee" class="form-control">
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


<?= $this->endSection() ?>

<!-- ====================== PAGE SCRIPTS ====================== -->
<?= $this->section('pageScripts') ?>

<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>

<script>
$(function(){

    const BASE_URL = "<?= base_url() ?>";

        // INIT DATATABLE
        let tbl = $('#tblConsignee').DataTable({
        ajax: {
            url: BASE_URL + "master-data/consignee/list",
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null, 
                render: (d,t,r,m)=> m.row + 1 
            },
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

    // REFRESH TABLE
    $('#btnRefresh').click(()=> tbl.ajax.reload());

    // SHOW ADD MODAL
    $('#btnAdd').click(()=>{
        $('#formAdd')[0].reset();
        $('#addErrors').addClass('d-none');
        $('#modalAdd').modal('show');
    });

    // STORE DATA
    $('#formAdd').submit(function(e){
        e.preventDefault();

        $.post(BASE_URL + "master-data/consignee/store/", $(this).serialize(), function(res){
            if(res.status === 'ok'){
                Swal.fire('Berhasil!', res.message, 'success');
                $('#modalAdd').modal('hide');
                tbl.ajax.reload();
            } else {
                $('#addErrors').removeClass('d-none').html(res.message);
            }
        }, 'json');
    });

    // EDIT BUTTON
    $('#tblConsignee').on('click', '.btn-edit', function(){
        const id = $(this).data('id');

        $.get(BASE_URL + "master-data/consignee/edit/" + id, function(res){
            if(res.status === 'ok'){
                const d = res.data;

                $('#editId').val(d.id);
                $('#editKode').val(d.kode);
                $('#editNama').val(d.nama_consignee);
                $('#editNpwp').val(d.npwp_consignee);
                $('#editAlamat').val(d.alamat_consignee);

                $('#modalEdit').modal('show');

            }else{
                Swal.fire('Error', res.message, 'error');
            }
        }, 'json');
    });

    // UPDATE DATA
    $('#formEdit').submit(function(e){
        e.preventDefault();

        const id = $('#editId').val();

        $.post(BASE_URL + "master-data/consignee/update/" + id, $(this).serialize(), function(res){

            if(res.status === 'ok'){
                Swal.fire('Berhasil!', res.message, 'success');
                $('#modalEdit').modal('hide');
                tbl.ajax.reload();
            } else {
                $('#editErrors').removeClass('d-none').html(res.message);
            }

        }, 'json');
    });

    // DELETE DATA
    $('#tblConsignee').on('click', '.btn-delete', function(){
        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus'
        }).then(result=>{
            if(result.isConfirmed){
                $.post(BASE_URL + "master-data/consignee/delete/" + id, function(res){
                    if(res.status === 'ok'){
                        Swal.fire('Terhapus!', res.message, 'success');
                        tbl.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });

});
</script>

<?= $this->endSection() ?>
