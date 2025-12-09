<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>User Trash<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Trash User</h3>
    <p class="text-subtitle text-muted">
        Daftar user yang telah dihapus. Anda dapat merestore atau menghapus permanen pengguna.
    </p>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive mt-2">
            <table id="tblTrash" class="table table-striped table-hover table-bordered" style="width:100%">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Deleted At</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<style>
    #tblTrash th,
    #tblTrash td {
        font-size: 15px;
        white-space: nowrap;
    }

    #tblTrash {
        width: 100% !important;
    }

    .dataTables_wrapper .dataTables_scroll {
        overflow: auto;
    }
</style>

<script>
    (function() {

        const LIST_URL = "<?= base_url('user-management-trash/trash-list') ?>";
        const RESTORE_URL = "<?= base_url('user-management-trash/restore') ?>";
        const DELETE_URL = "<?= base_url('user-management-trash/delete-permanent') ?>";

        // ===========================
        //  INIT DATATABLE
        // ===========================
        const tbl = $('#tblTrash').DataTable({
            processing: true,
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,
            fixedColumns: {
                left: 1,
                right: 1
            },
            ajax: {
                url: LIST_URL,
                dataSrc: json => json
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'active',
                    render: a => a == 1 ?
                        '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Nonaktif</span>'
                },
                {
                    data: 'deleted_at',
                    render: d => {
                        if (!d) return '-';
                        return (typeof d === 'object' && d.date) ?
                            d.date.split('.')[0] :
                            d;
                    }
                },

                {
                    data: 'id',
                    render: id => `
                    <button class="btn btn-sm btn-success btn-restore" data-id="${id}" title="Restore User">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>

                    <?php if (in_groups('admin')): ?>
                    <button class="btn btn-sm btn-danger btn-delete-permanent" data-id="${id}" title="Delete Permanen">
                        <i class="bi bi-trash"></i>
                    </button>
                    <?php endif; ?>
                `
                }
            ],
            order: [
                [4, 'desc']
            ]
        });

        // ===========================
        //  RESTORE USER
        // ===========================
        $('#tblTrash').on('click', '.btn-restore', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Restore User?',
                text: "User akan dikembalikan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Restore',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: Swal.showLoading
                    });

                    $.post(`${RESTORE_URL}/${id}`, {}, res => {
                        Swal.close();

                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'User berhasil direstore.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            tbl.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message || 'Restore gagal.', 'error');
                        }

                    }, 'json');
                }

            });
        });

        // ===========================
        //  DELETE PERMANENT
        // ===========================
        $('#tblTrash').on('click', '.btn-delete-permanent', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: 'Hapus Permanen?',
                text: "User tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then(result => {

                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        allowOutsideClick: false,
                        didOpen: Swal.showLoading
                    });

                    $.post(`${DELETE_URL}/${id}`, {}, res => {
                        Swal.close();

                        if (res.status) {
                            Swal.fire('Berhasil!', 'User dihapus permanen.', 'success');
                            tbl.ajax.reload();
                        } else {
                            Swal.fire('Gagal!', res.message || 'Tidak dapat menghapus user.', 'error');
                        }

                    }, 'json');
                }

            });

        });


    })();
</script>

<?= $this->endSection() ?>