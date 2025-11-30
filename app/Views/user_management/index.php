<?= $this->extend('layouts/layout') ?>

<!-- ====================== TITLE ====================== -->
<?= $this->section('title') ?>Manajemen User<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Manajemen User</h3>
    <p class="text-subtitle text-muted">
        Modul ini digunakan untuk mengelola akun pengguna sistem — mencakup tambah, edit, hapus, aktivasi, dan pengaturan role (Admin, Exim, Document).
        Dilengkapi juga dengan pencarian & ekspor data user untuk mendukung dokumentasi sistem.
    </p>
</div>
<?= $this->endSection() ?>

<!-- ====================== CONTENT ====================== -->
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-body">

        <!-- ====================== TOOLBAR ====================== -->
        <div class="d-flex justify-content-between mb-3">
            <div class="d-flex gap-2">
                <button id="btnAddUser" class="btn btn-primary">
                    <i class="bi bi-person-plus me-2"></i> Tambah User
                </button>
            </div>

            <div class="d-flex gap-2">
                <button id="btnRefresh" class="btn btn-secondary" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
                <button id="btnTrash" class="btn btn-danger" title="Sampah User">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>

        <!-- ====================== TABLE ====================== -->
        <div class="table-responsive">
            <table id="tblUsers" class="table table-striped table-hover table-bordered w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

    </div>
</div>

<!-- ====================== MODAL: TAMBAH USER ====================== -->
<div class="modal fade" id="modalAddUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formAddUser" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="exim">Exim</option>
                            <option value="document">Document</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- ====================== MODAL: EDIT USER ====================== -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formEditUser" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?= csrf_field() ?>
                <input type="hidden" id="editUserId" name="id">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="editRole" class="form-label">Role</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="exim">Exim</option>
                            <option value="document">Document</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="editStatus" class="form-label">Status</label>
                        <select name="active" id="editStatus" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning">Update User</button>
            </div>
        </form>
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

<style>
    #tblUsers th,
    #tblUsers td {
        white-space: nowrap;
        font-size: 15px;
    }

    #tblUsers tbody tr:hover {
        background-color: #f3f6ff !important;
        transition: background 0.2s ease-in-out;
    }

    .btn {
        transition: 0.2s;
    }

    .btn:hover {
        transform: translateY(-3px);
    }
</style>

<script>
    $(document).ready(function() {
        $('#tblUsers').DataTable({
            processing: true,
            ajax: {
                url: "<?= site_url('user-management/list') ?>",
                dataSrc: '' // karena kita kirim array langsung
            },
            columns: [{
                    data: null,
                    render: (data, type, row, meta) => meta.row + 1,
                    className: "text-center"
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role',
                    render: role => {
                        if (!role) return '<span class="badge bg-secondary">Tanpa Role</span>';
                        const color = role === 'admin' ? 'bg-primary' :
                            role === 'exim' ? 'bg-success' :
                            role === 'document' ? 'bg-warning text-dark' : 'bg-secondary';
                        return `<span class="badge ${color}">${role}</span>`;
                    },
                    className: "text-center"
                },
                {
                    data: 'active',
                    render: active => active == 1 ?
                        '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>',
                    className: "text-center"
                },
                {
                    data: 'id',
                    render: id => `
                <button class="btn btn-sm btn-warning btnEdit" data-id="${id}" title="Edit">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-danger btnDelete" data-id="${id}" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            `,
                    className: "text-center"
                }
            ]
        });

        // Tombol refresh
        $('#btnRefresh').on('click', function() {
            table.ajax.reload(null, false);
        });

        // Tombol tambah user
        $('#btnAddUser').on('click', function() {
            $('#modalAddUser').modal('show');
        });
    });
</script>

<?= $this->endSection() ?>