<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Worksheet Export Trash<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading">
    <h3>Trash Worksheet Export</h3>
    <p class="text-subtitle text-muted">
        Anda dapat memulihkan atau menghapus permanen worksheet export yang sudah dihapus (beserta seluruh child).
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
            <th>No Worksheet</th>
            <th>No Aju PEB</th>
            <th>Shipper/Exportir</th>
            <th>Party</th>
            <th>ETD</th>
            <th>POD</th>
            <th>No BL</th>
            <th>No Master BL</th>          
            <th>Pelayaran</th>
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
  #tblTrash th, #tblTrash td {
    white-space: nowrap;
    font-size: 14px;
  }
  #tblTrash { width: 100% !important; }
</style>

<script>
(function(){

  const TABLE_NAME  = "worksheet_export";

  const LIST_URL    = "<?= base_url('worksheet-export-trash/list') ?>?table=" + TABLE_NAME;
  const RESTORE_URL = "<?= base_url('worksheet-export-trash/restore') ?>/" + TABLE_NAME;
  const DELETE_URL  = "<?= base_url('worksheet-export-trash/delete-permanent') ?>/" + TABLE_NAME;

  const tbl = $('#tblTrash').DataTable({
    processing: true,
    scrollX: true,
    scrollCollapse: true,
    autoWidth: false,
    fixedColumns: { left: 2, right: 1 },

    ajax: { url: LIST_URL, dataSrc: 'data' },

    columns: [
      { data: null, render:(d,t,r,m)=> m.row + 1 },
      { data: 'no_ws' },
      { data: 'no_aju' },
      { data: 'shipper' },
      { data: 'party' },
      { data: 'etd' },
      { data: 'pod' },
      { data: 'bl' },
      { data: 'master_bl' },
      { data: 'shipping_line' },

      {
        data: 'status',
        render: (s) => {
          if (s === 'not completed') return '<span class="badge bg-warning">Not Completed</span>';
          if (s === 'completed') return '<span class="badge bg-success">Completed</span>';
        }
      },

      { data: 'deleted_at' },
      { data: 'deleted_by' },

      { 
        data: 'id',
        render: (id) => `
          <button class="btn btn-sm btn-success btn-restore" data-id="${id}">
            <i class="bi bi-arrow-counterclockwise"></i>
          </button>

          <button class="btn btn-sm btn-danger btn-delete-permanent" data-id="${id}">
            <i class="bi bi-trash"></i>
          </button>
        `
      }
    ],

    order: [[ 10, 'desc' ]]
  });


  // RESTORE
  $('#tblTrash').on('click', '.btn-restore', function(){
    const id = $(this).data('id');

    Swal.fire({
      title: 'Restore Worksheet?',
      text: 'Data worksheet export dan seluruh child akan dipulihkan.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Restore',
      cancelButtonText: 'Batal'
    }).then((res)=>{
      if(res.isConfirmed){
        Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:Swal.showLoading});

        $.post(RESTORE_URL + "/" + id, {}, function(res){
          Swal.close();

          if(res.status === 'ok'){
            Swal.fire('Berhasil!', res.message, 'success');
            tbl.ajax.reload();
          } else {
            Swal.fire('Gagal!', res.message, 'error');
          }
        }, 'json');
      }
    });
  });


  // DELETE PERMANENT
  $('#tblTrash').on('click', '.btn-delete-permanent', function(){
    const id = $(this).data('id');

    Swal.fire({
      title: 'Hapus Permanen?',
      text: 'Data worksheet export dan seluruh child akan dihapus secara permanen!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Hapus'
    }).then((res)=>{
      if(res.isConfirmed){
        Swal.fire({title:'Menghapus...',allowOutsideClick:false,didOpen:Swal.showLoading});

        $.post(DELETE_URL + "/" + id, {}, function(res){
          Swal.close();

          if(res.status === 'ok'){
            Swal.fire('Berhasil!', res.message, 'success');
            tbl.ajax.reload();
          } else {
            Swal.fire('Gagal!', res.message, 'error');
          }
        }, 'json');
      }
    });
  });

})();
</script>

<?= $this->endSection() ?>
