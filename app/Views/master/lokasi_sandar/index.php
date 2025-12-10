<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Master Lokasi Sandar<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3 class="heading-title">Master Data Lokasi Sandar</h3>

    <p class="text-subtitle text-muted">
        Master Data Lokasi Sandar digunakan untuk menyimpan informasi Lokasi Sandar.
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
                    Tambah Lokasi Sandar
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
            <table id="tblSandar" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Lokasi Sandar</th>
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
                <h5 class="modal-title">Tambah Lokasi Sandar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="addErrors" class="alert alert-danger d-none"></div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <select name="kode" id="kodeSandar" class="form-control select2" required></select>
                        <span id="kodeWarning" class="text-danger mt-1" style="display:none;">Kode sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Lokasi Sandar</label>
                        <select name="nama_sandar" id="namaSandar" class="form-control select2" required></select>
                        <span id="namaWarning" class="text-danger mt-1" style="display:none;">Nama sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat_sandar" id="alamatSandar" class="form-control" rows="3" placeholder="Masukan Alamat" required></textarea>
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
                <h5 class="modal-title">Edit Lokasi Sandar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div id="editErrors" class="alert alert-danger d-none"></div>
                <input type="hidden" name="id" id="editId">

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Kode</label>
                        <select name="kode" id="editKodeSandar" class="form-control select2" required></select>
                        <span id="editKodeWarning" class="text-danger mt-1" style="display:none;">Kode sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Lokasi Sandar</label>
                        <select name="nama_sandar" id="editNamaSandar" class="form-control select2" required></select>
                        <span id="editNamaWarning" class="text-danger mt-1" style="display:none;">Nama sudah terdaftar!</span>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat_sandar" id="editAlamatSandar" class="form-control" rows="3"></textarea>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-warning"><i class="bi bi-pencil-square me-1"></i> Update</button>
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
<script src="<?= base_url('assets/js/pages/master/lokasi_sandar.js') ?>"></script>
<?= $this->endSection() ?>