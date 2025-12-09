<?= $this->extend('layouts/layout') ?>

<!-- ====================== TITLE ====================== -->
<?= $this->section('title') ?>Manajemen User<?= $this->endSection() ?>

<!-- ====================== HEADER ====================== -->
<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Manajemen User</h3>
    <p class="text-subtitle text-muted">
        Modul pengelolaan akun pengguna — tambah, edit, hapus, aktivasi, dan pengaturan role.
    </p>
</div>
<?= $this->endSection() ?>

<!-- ====================== CONTENT ====================== -->
<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-body">

        <!-- ====================== TOOLBAR ====================== -->
        <div class="d-flex justify-content-between mb-3">
            <button id="btnAddUser" class="btn btn-primary">
                <i class="bi bi-person-plus me-2"></i> Tambah User
            </button>

            <div class="d-flex gap-2">
                <button id="btnRefresh" class="btn btn-secondary" title="Refresh Data">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>

                <a href="<?= site_url('user-management-trash') ?>" class="btn btn-danger" title="User Terhapus">
                    <i class="bi bi-trash"></i>
                </a>
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
        <form id="formAddUser" action="<?= site_url('user-management/store') ?>" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="exim">Exim</option>
                            <option value="document">Document</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan User</button>
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="editUserId">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" id="editUsername" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" id="editPassword" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                    </div>



                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" id="editRole" class="form-select" required></select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="active" id="editStatus" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i> Batal</button>
                <button class="btn btn-warning" id="btnUpdateUser"><i class="bi bi-pencil-square me-1"></i> Update User</button>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<script>
    $(document).ready(function() {

        // ========== LOAD TABLE ==========
        window.table = $('#tblUsers').DataTable({
            ajax: {
                url: "<?= site_url('user-management/list') ?>",
                dataSrc: json => json
            },
            columns: [{
                    data: null,
                    render: (_, __, ___, meta) => meta.row + 1
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
                        let color = 'secondary';
                        if (role === 'exim') color = 'success';
                        else if (role === 'document') color = 'primary';
                        else if (role === 'admin') color = 'danger';
                        return `<span class="badge bg-${color}">${role ?? 'No Role'}</span>`;
                    }
                },
                {
                    data: 'active',
                    render: a => a == 1 ?
                        '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>'
                },
                {
                    data: 'id',
                    render: id => `
                    <button class="btn btn-warning btn-sm btnEdit" data-id="${id}">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-danger btn-sm btnDelete" data-id="${id}">
                        <i class="bi bi-trash"></i>
                    </button>`
                }
            ]
        });



        // =====================================================================
        // =============== TAMBAH USER (SUDAH ADA) =============================
        // =====================================================================

        $('#btnAddUser').click(() => $('#modalAddUser').modal('show'));

        $('#formAddUser').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {

                    if (res.status === true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'User berhasil ditambahkan.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#modalAddUser').modal('hide');
                        $('#formAddUser')[0].reset();
                        table.ajax.reload();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Periksa kembali inputan.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server.',
                    });
                }
            });
        });



        // =====================================================================
        // =============== EDIT USER (LOAD DATA) ===============================
        // =====================================================================

        $(document).on('click', '.btnEdit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: "<?= site_url('user-management/edit') ?>/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {

                    // ====== Isi data dasar ======
                    $('#editUserId').val(res.user.id);
                    $('#editUsername').val(res.user.username);
                    $('#editEmail').val(res.user.email);

                    // ====== Isi Role ======
                    $('#editRole').empty();
                    $.each(res.roles, function(i, role) {
                        let selected = (role.name === res.user.role) ? 'selected' : '';
                        $('#editRole').append(`
                            <option value="${role.name}" ${selected}>
                                ${role.name}
                            </option>
                        `);
                    });

                    $('#editStatus').empty();

                    let statusList = [{
                            value: 1,
                            label: "Aktif"
                        },
                        {
                            value: 0,
                            label: "Nonaktif"
                        }
                    ];

                    $.each(statusList, function(i, st) {
                        let selected = (res.user.active == st.value) ? 'selected' : '';
                        $('#editStatus').append(`
                            <option value="${st.value}" ${selected}>
                                ${st.label}
                            </option>
                        `);
                    });


                    // ====== Tampilkan Modal ======
                    $('#modalEditUser').modal('show');
                }
            });
        });


        // =====================================================================
        // =============== UPDATE USER (AJAX + SWEETALERT) =====================
        // =====================================================================

        $('#formEditUser').on('submit', function(e) {
            e.preventDefault();

            let id = $('#editUserId').val();

            $.ajax({
                url: "<?= site_url('user-management/update') ?>/" + id,
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',

                success: function(res) {
                    if (res.status === true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'User berhasil diperbarui.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#modalEditUser').modal('hide');
                        table.ajax.reload();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Update',
                            html: Object.values(res.errors).join('<br>'),
                        });
                    }
                },

                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Server Error',
                        text: 'Kesalahan server saat update.',
                    });
                }

            });
        });




        // =====================================================================
        // =============== DELETE USER (WITH SWEETALERT2) ======================
        // =====================================================================

        $(document).on('click', '.btnDelete', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Hapus User?',
                text: "User akan dipindahkan ke trash!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "<?= site_url('user-management/delete') ?>/" + id,
                        type: 'POST',
                        dataType: 'json',
                        success: function(res) {

                            if (res.status === true) {

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: 'User berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });

                                table.ajax.reload();
                            }
                        }
                    });

                }

            });

        });

        // ====================== REFRESH ======================
        $('#btnRefresh').on('click', function() {

            Swal.fire({
                title: 'Memuat...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            table.ajax.reload(function() {

                Swal.close(); // wajib tutup loading swal

                Swal.fire({
                    icon: 'success',
                    title: 'Data telah diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                });

            }, false);
        });



    });
</script>

<?= $this->endSection() ?>