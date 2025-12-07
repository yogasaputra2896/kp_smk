<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Booking Job Trash<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading">
  <h3>Trash Booking Job</h3>
  <p class="text-subtitle text-muted">
    Anda bisa mengembalikan atau menghapus permanen data booking job yang sudah dihapus.
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
            <th>No Job</th>
            <th>No PIB/PO</th>
            <th>Consignee</th>
            <th>Party</th>
            <th>ETA</th>
            <th>POL</th>
            <th>Pelayaran</th>
            <th>BL</th>
            <th>Master BL</th>
            <th>Status</th>
            <th>Deleted At</th>
            <th>Deleted By</th>
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
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/vendors/jquery/fixedColumns.dataTables.min.css') ?>">
<script src="<?= base_url('assets/vendors/jquery/jquery-3.6.0.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/jquery/dataTables.fixedColumns.min.js') ?>"></script>
<script src="<?= base_url('assets/vendors/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<style>
  #tblTrash th,
  #tblTrash td {
    font-size: 15px;
  }

  #tblTrash th:nth-child(3),
  #tblTrash td:nth-child(3) {
    min-width: 85px !important;
  }

  #tblTrash th:nth-child(4),
  #tblTrash td:nth-child(4) {
    min-width: 200px !important;
  }

  #tblTrash th:nth-child(5),
  #tblTrash td:nth-child(5) {
    min-width: 75px !important;
  }

  #tblTrash th:nth-child(6),
  #tblTrash td:nth-child(6) {
    min-width: 75px !important;
  }

  #tblTrash th:nth-child(7),
  #tblTrash td:nth-child(7) {
    min-width: 125px !important;
  }

  #tblTrash th:nth-child(9),
  #tblTrash td:nth-child(9) {
    min-width: 150px !important;
  }

  #tblTrash th:nth-child(10),
  #tblTrash td:nth-child(10) {
    min-width: 150px !important;
  }

  #tblTrash th:nth-child(13),
  #tblTrash td:nth-child(13) {
    min-width: 100px !important;
  }

  #tblTrash {
    width: 100% !important;
  }

  #tblTrash th,
  #tblTrash td {
    white-space: nowrap;
    /* biar teks tidak turun ke bawah */
    font-size: 15px;
  }

  .dataTables_wrapper .dataTables_scroll {
    overflow: auto;
  }
</style>
<script>
  (function() {
    const LIST_URL = "<?= base_url('booking-job-trash/list') ?>";
    const RESTORE_URL = "<?= base_url('booking-job-trash/restore') ?>";
    const DELETE_URL = "<?= base_url('booking-job-trash/delete-permanent') ?>"; // ✅ rapikan
    const BASE_URL = "<?= base_url() ?>";

    const tbl = $('#tblTrash').DataTable({
      processing: true,
      scrollX: true, // WAJIB untuk fixedColumns
      scrollCollapse: true,
      autoWidth: false,
      fixedColumns: {
        left: 2, // fix 2 kolom paling kiri
        right: 1 // fix 1 kolom paling kanan
      },
      ajax: {
        url: LIST_URL,
        dataSrc: 'data'
      },
      columns: [{
          data: null,
          render: (d, t, r, m) => m.row + 1
        },
        {
          data: 'no_job'
        },
        {
          data: 'no_pib_po'
        },
        {
          data: 'consignee'
        },
        {
          data: 'party'
        },
        {
          data: 'eta'
        },
        {
          data: 'pol'
        },
        {
          data: 'shipping_line'
        },
        {
          data: 'bl'
        },
        {
          data: 'master_bl'
        },
        {
          data: 'status',
          render: function(data) {
            if (data === 'open job') {
              return '<span class="badge bg-primary">Open Job</span>';
            } else if (data === 'worksheet') {
              return '<span class="badge bg-success">Worksheet</span>';
            } else {
              return data;
            }
          }
        },
        {
          data: 'deleted_at'
        },
        {
          data: 'deleted_by'
        },
        {
          data: 'id',
          render: (id) => `
          <button class="btn btn-sm btn-success btn-restore" data-id="${id}" title="Restore Job">
            <i class="bi bi-arrow-counterclockwise"></i>
          </button>
          <?php if (in_groups('admin')): ?>
            <button class="btn btn-sm btn-danger btn-delete-permanent" data-id="${id}" title="Delete Permanent">
              <i class="bi bi-trash"></i>
            </button>
          <?php endif; ?>
        `
        }
      ],
      order: [
        [10, 'desc']
      ]
    });


    // === Restore ===
    $('#tblTrash').on('click', '.btn-restore', function() {
      const id = $(this).data('id');
      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data akan direstore kembali.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Restore!',
        cancelButtonText: 'Batal'
      }).then((res) => {
        if (res.isConfirmed) {
          Swal.fire({
            title: 'Proses restore...',
            allowOutsideClick: false,
            didOpen: Swal.showLoading
          });
          $.post(RESTORE_URL + '/' + id, {}, function(res) {
            Swal.close();
            if (res.status === 'ok') {
              Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
              });
              tbl.ajax.reload();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: res.message
              });
            }
          }, 'json').fail((xhr) => {
            Swal.close();
            Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal restore data.', 'error');
          });
        }
      });
    });

    // === Delete Permanen ===
    $('#tblTrash').on('click', '.btn-delete-permanent', function() {
      const id = $(this).data('id');
      Swal.fire({
        title: 'Apakah Anda Yakin ?',
        text: "Data akan dihapus selamanya dan tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!'
      }).then((res) => {
        if (res.isConfirmed) {
          Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            didOpen: Swal.showLoading
          });
          $.ajax({
            url: DELETE_URL + '/' + id,
            type: 'POST',
            dataType: 'json',
            success: function(res) {
              Swal.close();
              if (res.status === 'ok') {
                Swal.fire('Berhasil!', res.message, 'success');
                tbl.ajax.reload();
              } else {
                Swal.fire('Gagal!', res.message || 'Data gagal dihapus.', 'error');
              }
            },
            error: function(xhr) {
              Swal.close();
              Swal.fire('Error!', xhr.responseJSON?.message || 'Terjadi kesalahan server.', 'error');
            }
          });
        }
      });
    });

  })();
</script>
<?= $this->endSection() ?>