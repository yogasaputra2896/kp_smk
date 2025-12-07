(function () {

    // ====================== CONFIG ======================
    const config = window.BookingJobConfig || {};
    const LIST_URL = config.listUrl;
    const NEXTNO_URL = config.nextNoUrl;
    const BASE_URL = config.baseUrl;

    let currentType = 'import_lcl';

    // ====================== SWEETALERT ======================
    function showLoading(title = 'Memuat...', text = 'Mohon tunggu sebentar') {
        Swal.fire({
            title,
            text,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });
    }

    function showSuccess(message, timer = 2000) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: message,
            timer,
            showConfirmButton: false
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: message || 'Terjadi kesalahan.'
        });
    }

    function showWarning(message) {
        Swal.fire({
            icon: 'warning',
            title: 'Gagal!',
            text: message || 'Proses gagal dilakukan.'
        });
    }

    // ====================== DATATABLES ======================
    const isMobile = window.innerWidth < 576;

    const tbl = $('#tblBookings').DataTable({
        fixedColumns: isMobile ? false : {
            left: 2,
            right: 2
        },
        ajax: {
            url: LIST_URL,
            data: function (d) {
                d.type = currentType;
            },
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null, 
                render: (data, type, row, meta) =>
                    meta.row + meta.settings._iDisplayStart + 1 
            },
            { data: 'no_job' },
            { data: 'no_pib_po' },
            { data: 'consignee' },
            { data: 'party' },
            { data: 'eta' },
            { data: 'pol' },
            { data: 'shipping_line' },
            { data: 'bl' },
            { data: 'master_bl' },
            {
                data: 'status',
                render: function (data) {
                    if (data === 'open job') return '<span class="badge bg-primary">Open Job</span>';
                    if (data === 'worksheet') return '<span class="badge bg-success">Worksheet</span>';
                    return data;
                }
            },
            {
                data: 'id',
                render: function (id, type, row) {

                    if (row.status === 'worksheet') {
                        return `
                            <div class="aksi-grid">
                                <button class="btn btn-sm btn-primary btn-note" data-id="${id}">
                                    <i class="bi bi-sticky"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" data-id="${id}" data-no_job="${row.no_job}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <span class="badge bg-secondary">Terkirim</span>
                            </div>
                        `;
                    }

                    return `
                        <div class="aksi-grid">
                            <button class="btn btn-sm btn-warning btn-edit" data-id="${id}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${id}" data-no_job="${row.no_job}">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-primary btn-note" data-id="${id}">
                                <i class="bi bi-sticky"></i>
                            </button>
                            <button class="btn btn-sm btn-success btn-send" data-id="${id}">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']]
    });

    // ====================== ADD BOOKING (GO TO PAGE) ======================
    $('#btnAdd').on('click', function () {
        window.location.href = BASE_URL + "/booking-job/add";
    });

    // ====================== EDIT BOOKING (GO TO PAGE) ======================
    $('#tblBookings').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        window.location.href = BASE_URL + "/booking-job/edit/" + id;
    });

    // ====================== DELETE ======================
    $('#tblBookings').on('click', '.btn-delete', function () {

        const id = $(this).data('id');
        const no_job = $(this).data('no_job');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Data Booking Job '${no_job}' akan dihapus!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Hapus!'
        }).then(result => {

            if (!result.isConfirmed) return;

            showLoading('Menghapus...');

            $.ajax({
                url: BASE_URL + '/booking-job/delete/' + id,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    Swal.close();

                    if (res.status === 'ok') {
                        showSuccess(res.message);
                        tbl.ajax.reload(null, false);
                    } else {
                        showWarning(res.message);
                    }
                },
                error: function (xhr) {
                    showError(xhr.responseText);
                }
            });
        });
    });

    // ====================== FILTER TYPE ======================
    $('.filter-btn').on('click', function () {
        currentType = $(this).data('type');

        $('.filter-btn').removeClass('active btn-primary')
            .addClass('btn-outline-primary');

        $(this).addClass('btn-primary active').removeClass('btn-outline-primary');

        tbl.ajax.reload();
    });

    // ====================== PRINT NOTE ======================
    $(document).on('click', '.btn-note', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const encoded = btoa(id + '-' + Date.now()).replace(/=/g, '');
        window.open(BASE_URL + '/booking-job/print-note/' + encoded);
    });

    // ====================== SEND TO WORKSHEET ======================
    $('#tblBookings').on('click', '.btn-send', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Kirim ke Worksheet?',
            text: 'Data booking akan dikirim.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Ya, Kirim'
        }).then(res => {

            if (!res.isConfirmed) return;

            showLoading('Mengirim...');

            $.ajax({
                url: BASE_URL + '/booking-job/send-to-worksheet/' + id,
                type: 'POST',
                success: function (res) {
                    Swal.close();

                    if (res.status === 'ok') {
                        showSuccess(res.message, 1500);
                        tbl.ajax.reload();
                    } else {
                        showWarning(res.message);
                    }
                },
                error: function () {
                    showError('Gagal mengirim ke worksheet');
                }
            });
        });
    });

    // ====================== REFRESH ======================
    $('#btnRefresh').on('click', function () {

        showLoading('Memuat...');

        tbl.ajax.reload(function () {
            Swal.fire({
                icon: 'success',
                title: 'Data telah diperbarui',
                timer: 1500,
                showConfirmButton: false
            });
        });
    });

    // OPEN MODAL EXPORT EXCEL
    $('#btnExport').on('click', function () {
        $('#modalExportExcel').modal('show');
    });

    // OPEN MODAL EXPORT PDF
    $('#btnExportPdf').on('click', function () {
        $('#modalExportPdf').modal('show');
    });

    // ====================== EXPORT EXCEL ======================
    $('#btnConfirmExportExcel').on('click', function () {

        const type  = $('#jenisExport').val();
        const start = $('#startExport').val();
        const end   = $('#endExport').val();

        if (!type || !start || !end) {
            return Swal.fire('Oops!', 'Semua kolom harus diisi!', 'warning');
        }

        if (end < start) {
            return Swal.fire('Tanggal Salah!', 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.', 'error');
        }

        const url = BASE_URL + "booking-job/export-excel-range?type=" + type + 
                    "&start_date=" + start + "&end_date=" + end;

        window.open(url, "_blank");
    });


    // ====================== EXPORT PDF ======================
    $('#btnConfirmExportPdf').on('click', function () {

        const type  = $('#jenisExportPdf').val();
        const start = $('#startExportPdf').val();
        const end   = $('#endExportPdf').val();

        if (!type || !start || !end) {
            return Swal.fire('Oops!', 'Semua kolom harus diisi!', 'warning');
        }

        if (end < start) {
            return Swal.fire('Tanggal Salah!', 'Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.', 'error');
        }

        const url = BASE_URL + "booking-job/export-pdf-range?type=" + type + 
                    "&start_date=" + start + "&end_date=" + end;

        window.open(url, "_blank");
    });

    // RESET MODAL EXPORT EXCEL SAAT DITUTUP
    $('#modalExportExcel').on('hidden.bs.modal', function () {
        $('#jenisExport').val('').trigger('change');
        $('#startExport').val('');
        $('#endExport').val('');
    });

    // RESET MODAL EXPORT PDF SAAT DITUTUP
    $('#modalExportPdf').on('hidden.bs.modal', function () {
        $('#jenisExportPdf').val('').trigger('change');
        $('#startExportPdf').val('');
        $('#endExportPdf').val('');
    });



    // ====================== GO TO TRASH ======================
    $('#btnTrash').on('click', function () {

        Swal.fire({
            title: 'Pergi ke Sampah Booking Job?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then(res => {
            if (res.isConfirmed) {
                window.location.href = '/booking-job-trash';
            }
        });
    });

    // ====================== FLASHDATA SWEETALERT ======================
    if (window.BookingJobFlash && window.BookingJobFlash.success) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: window.BookingJobFlash.success,
            timer: 2000,
            showConfirmButton: false
        });
    }


})();
