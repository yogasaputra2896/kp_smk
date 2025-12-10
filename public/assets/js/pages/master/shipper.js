$(function () {

    // ===================================
    // INIT DATATABLE
    // ===================================
    let tbl = $('#tblShipper').DataTable({
        ajax: {
            url: BASE_URL + "master-data/shipper/list",
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                data: 'kode'
            },
            {
                data: 'nama_shipper'
            },
            {
                data: 'alamat_shipper',
                render: function (data) {
                    if (!data) return "-";
                    return data.length > 25 ? data.substring(0, 25) + "..." : data;
                }
            },
            {
                data: 'id',
                render: function (id) {
                    return `
                    <button class="btn btn-sm btn-warning btn-edit mb-2" data-id="${id}" title="Edit Shipper">
                        <i class="bi bi-pencil-square"></i>
                    </button>
                    <button class="btn btn-sm btn-danger btn-delete mb-2" data-id="${id}" title="Delete Shipper">
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
    $('#btnRefresh').on('click', function () {
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
    $('#btnAdd').click(() => {
        $('#formAdd')[0].reset();
        $('#addErrors').html('').addClass('d-none');
        $('#modalAdd').modal('show');
    });


    // ================================
    // INIT SELECT2 DALAM MODAL ADD
    // ================================
    $('#modalAdd').on('shown.bs.modal', function () {

        function checkExisting(element, title) {
            $(element).off("select2:select").on("select2:select", function (e) {
                if (e.params.data.exists) {
                    Swal.fire({
                        icon: 'warning',
                        title: title + ' sudah terdaftar!',
                        text: 'Gunakan ' + title.toLowerCase() + ' lain.'
                    }).then(() => {
                        $(this).val(null).trigger('change');
                    });
                }
            });
        }

        function initSelect2(selector, url, placeholder) {
            $(selector).select2({
                dropdownParent: $('#modalAdd'),
                theme: "bootstrap-5",
                minimumInputLength: 1,
                placeholder: placeholder,
                allowClear: true,
                tags: true,
                width: "100%",
                ajax: {
                    url: BASE_URL + url,
                    dataType: 'json',
                    delay: 0,
                    data: params => ({ term: params.term }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.text.toUpperCase(),
                            exists: item.exists
                        }))
                    })
                }
            });

            $(selector).on('select2:open', function () {
                setTimeout(() => {
                    $('.select2-results__option').each(function () {
                        $(this).text($(this).text().toUpperCase());
                    });
                }, 10);
            });
        }

        // === SELECT2 KODE SHIPPER ===
        initSelect2(
            '#kodeShipper',
            "master-data/shipper/search/kode",
            "Masukan Kode Shipper"
        );
        checkExisting('#kodeShipper', "Kode Shipper");

        $('#kodeShipper').on('select2:select', function (e) {
            let val = $(this).val();
            if (!val) return;

            val = val.toString().toUpperCase();
            $(this).val(val).trigger('change.select2');
        });

        // === SELECT2 NAMA SHIPPER ===
        initSelect2(
            '#namaShipper',
            "master-data/shipper/search/nama",
            "Masukan Nama Shipper"
        );
        checkExisting('#namaShipper', "Nama Shipper");

    });


    // ======================
    // SELECT2 EDIT MODAL
    // ======================
    $('#modalEdit').on('shown.bs.modal', function () {

        $('#editKodeShipper').select2({
            dropdownParent: $('#modalEdit'),
            theme: "bootstrap-5",
            minimumInputLength: 1,
            placeholder: "Masukan Kode Shipper",
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/shipper/search/kode",
                dataType: 'json',
                delay: 0,
                data: params => ({ term: params.term }),
                processResults: data => ({ results: data })
            }
        });

        $('#editNamaShipper').select2({
            dropdownParent: $('#modalEdit'),
            theme: "bootstrap-5",
            minimumInputLength: 1,
            placeholder: "Masukan Nama Shipper",
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/shipper/search/nama",
                dataType: 'json',
                delay: 0,
                data: params => ({ term: params.term }),
                processResults: data => ({ results: data })
            }
        });

    });



    // ===================================
    // ADD DATA
    // ===================================
    $('#formAdd').submit(function (e) {
        e.preventDefault();

        $.post(BASE_URL + "master-data/shipper/store", $(this).serialize(), function (res) {

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
    $('#tblShipper').on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        $.get(BASE_URL + "master-data/shipper/edit/" + id, function (res) {

            if (res.status === 'success') {
                const d = res.data;

                $('#editId').val(d.id);

                $('#editKodeShipper').append(new Option(d.kode, d.kode, true, true)).trigger('change');
                $('#editNamaShipper').append(new Option(d.nama_shipper, d.nama_shipper, true, true)).trigger('change');

                $('#editAlamatShipper').val(d.alamat_shipper);

                $('#modalEdit').modal('show');
            } else {
                Swal.fire('Error', res.message, 'error');
            }

        }, 'json');
    });



    // ===================================
    // UPDATE DATA
    // ===================================
    $('#formEdit').submit(function (e) {
        e.preventDefault();

        const id = $('#editId').val();

        $.post(BASE_URL + "master-data/shipper/update/" + id, $(this).serialize(), function (res) {

            if (res.status === 'success') {
                Swal.fire('Berhasil!', res.message, 'success');
                $('#modalEdit').modal('hide');
                tbl.ajax.reload();
            } else {
                let err = "";
                $.each(res.errors, function (k, v) {
                    err += `<li>${v}</li>`;
                });

                $('#editErrors')
                    .removeClass('d-none')
                    .html("<ul>" + err + "</ul>");
            }

        }, 'json');
    });



    // ===================================
    // DELETE DATA
    // ===================================
    $('#tblShipper').on('click', '.btn-delete', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {

                $.post(BASE_URL + "master-data/shipper/delete/" + id, function (res) {

                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success');
                        tbl.ajax.reload();
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }

                }, 'json');

            }
        });
    });



    // ===================================
    // RESET MODAL ADD
    // ===================================
    $('#modalAdd').on('hidden.bs.modal', function () {

        $('#formAdd')[0].reset();
        $('#addErrors').html('').addClass('d-none');

        $('#kodeShipper').val(null).trigger('change').empty();
        $('#namaShipper').val(null).trigger('change').empty();

    });

});


// INIT TOOLTIP
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});
