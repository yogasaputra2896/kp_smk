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

<script>
    const BASE_URL = "<?= base_url() ?>/";
</script>

<?= $this->endSection() ?>

<!-- ====================== PAGE SCRIPTS ====================== -->
<?= $this->section('pageScripts') ?>
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/pages/master.css') ?>">
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pages/master/notify_party.js') ?>"></script>
<?= $this->endSection() ?>