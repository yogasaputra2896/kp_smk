$(function() {
    // ===================================
    // INIT DATATABLE
    // ===================================
    let tbl = $('#tblPort').DataTable({
        ajax: {
            url: BASE_URL + "master-data/port/list",
            dataSrc: 'data'
        },
        columns: [{
                data: null,
                render: (d, t, r, m) => m.row + 1
            },
            {
                data: 'kode'
            },
            {
                data: 'nama_port'
            },
            {
                data: 'negara_port'
            },
            {
                data: 'id',
                render: function(id) {
                    return `
                <button class="btn btn-sm btn-warning btn-edit mb-2" data-id="${id}" title="Edit Pelabuhan">
                    <i class="bi bi-pencil-square"></i>
                </button>
                <button class="btn btn-sm btn-danger btn-delete mb-2" data-id="${id}" title="Delete Pelabuhan">
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
    $('#btnRefresh').on('click', function() {
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

    /// ================================
    //  INIT SELECT2 DALAM MODAL ADD
    // ================================
    $('#modalAdd').on('shown.bs.modal', function() {

        // ==========================
        // FUNGSI CEK DATA EXISTS
        // ==========================
        function checkExisting(element, title) {
            $(element).off("select2:select").on("select2:select", function(e) {
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

        // ==========================
        // FUNGSI SELECT2
        // ==========================
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
                    data: params => ({
                        term: params.term
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.id,
                            text: item.text.toUpperCase(), // <=== dropdown uppercase
                            exists: item.exists
                        }))
                    })
                }
            });

            // =============================
            // BUAT SEMUA DROPDOWN UPPERCASE
            // =============================
            $(selector).on('select2:open', function() {
                setTimeout(() => {
                    $('.select2-results__option').each(function() {
                        $(this).text($(this).text().toUpperCase());
                    });
                }, 10);
            });
        }

        // ============================================
        // 1. SELECT2 KODE Pelabuhan (AUTO UPPERCASE)
        // ============================================
        initSelect2(
            '#kodePort',
            "master-data/port/search/kode",
            "Masukan Kode Pelabuhan"
        );
        checkExisting('#kodePort', "Kode Pelabuhan");

        // Auto uppercase + minimal 4 huruf
        $('#kodePort').on('select2:select', function(e) {
            let val = $(this).val();
            if (!val) return;

            val = val.toString().toUpperCase();
            $(this).val(val).trigger('change.select2');

            if (val.length < 4) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Minimal 4 Karakter!',
                    text: 'Kode Pelabuhan harus terdiri dari minimal 4 karakter.'
                }).then(() => {
                    $(this).val(null).trigger('change');
                });
            } else if (val.length > 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maximal 6 Karakter!',
                    text: 'Kode Pelabuhan harus terdiri dari maksimal 6 karakter.'
                }).then(() => {
                    $(this).val(null).trigger('change');
                });
            }
        });



        // ==========================================
        // REALTIME UPPERCASE UNTUK DROPDOWN SELECT2
        // ==========================================
        $(document).on('keyup', ".select2-search__field", function() {
            // Uppercase untuk input search
            $(this).val($(this).val().toUpperCase());

            // Realtime ubah semua opsi dropdown
            $('.select2-results__option').each(function() {
                $(this).text($(this).text().toUpperCase());
            });
        });

        // Saat dropdown muncul, apply juga langsung
        $(document).on('select2:open', function() {
            setTimeout(() => {
                $('.select2-results__option').each(function() {
                    $(this).text($(this).text().toUpperCase());
                });
            }, 10);
        });



        // ============================
        // 2. SELECT2 NAMA Pelabuhan
        // ============================
        initSelect2(
            '#namaPort',
            "master-data/port/search/nama",
            "Masukan Nama Pelabuhan"
        );
        checkExisting('#namaPort', "Nama Pelabuhan");


        // ============================
        // 3. SELECT2 NEGARA
        // ============================
        initSelect2(
            '#negaraPort',
            "master-data/port/search/negara",
            "Masukan Negara"
        );

    });


    // ======================
    // SELECT2 EDIT MODAL
    // ======================
    $('#modalEdit').on('shown.bs.modal', function() {

        // === KODE ===
        $('#editKodePort').select2({
            dropdownParent: $('#modalEdit'),
            placeholder: "Masukan Kode Pelabuhan",
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/port/search/kode",
                dataType: 'json',
                delay: 0,
                data: params => ({
                    term: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });

        // === NAMA ===
        $('#editNamaPort').select2({
            dropdownParent: $('#modalEdit'),
            placeholder: "Masukan Nama Pelabuhan",
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/port/search/nama",
                dataType: 'json',
                delay: 0,
                data: params => ({
                    term: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });

        // === NEGARA ===
        $('#editNegaraPort').select2({
            dropdownParent: $('#modalEdit'),
            placeholder: "Masukan Negara Pelabuhan",
            tags: true,
            width: "100%",
            ajax: {
                url: BASE_URL + "master-data/port/search/negara",
                dataType: 'json',
                delay: 0,
                data: params => ({
                    term: params.term
                }),
                processResults: data => ({
                    results: data
                })
            }
        });

    });



    // ===================================
    // ADD DATA
    // ===================================
    $('#formAdd').submit(function(e) {
        e.preventDefault();

        $.post(BASE_URL + "master-data/port/store", $(this).serialize(), function(res) {

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
    $('#tblPort').on('click', '.btn-edit', function() {
        const id = $(this).data('id');

        $.get(BASE_URL + "master-data/port/edit/" + id, function(res) {

            if (res.status === 'success') {
                const d = res.data;

                $('#editId').val(d.id);

                // SET VALUE SELECT2 (harus append data dulu)
                let kodeOption = new Option(d.kode, d.kode, true, true);
                $('#editKodePort').append(kodeOption).trigger('change');

                let namaOption = new Option(d.nama_port, d.nama_port, true, true);
                $('#editNamaPort').append(namaOption).trigger('change');

                let negaraOption = new Option(d.negara_port, d.negara_port, true, true);
                $('#editNegaraPort').append(negaraOption).trigger('change');

                $('#modalEdit').modal('show');
            } else {
                Swal.fire('Error', res.message, 'error');
            }

        }, 'json');
    });


    // ===================================
    // UPDATE DATA
    // ===================================
    $('#formEdit').submit(function(e) {
        e.preventDefault();

        const id = $('#editId').val();

        $.post(BASE_URL + "master-data/port/update/" + id, $(this).serialize(), function(res) {

            if (res.status === 'success') {
                Swal.fire('Berhasil!', res.message, 'success');
                $('#modalEdit').modal('hide');
                tbl.ajax.reload();
            } else {
                let err = "";
                $.each(res.errors, function(k, v) {
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
    $('#tblPort').on('click', '.btn-delete', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {

                $.post(BASE_URL + "master-data/port/delete/" + id, function(res) {

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

    // =====================================================
    // RESET MODAL ADD SAAT DITUTUP
    // =====================================================
    $('#modalAdd').on('hidden.bs.modal', function() {

        $('#formAdd')[0].reset();

        // Reset error
        $('#addErrors').html('').addClass('d-none');

        // Reset Select2
        $('#kodePort').val(null).trigger('change');
        $('#kodePort').empty();

        $('#namaPort').val(null).trigger('change');
        $('#namaPort').empty();

        $('#negara_port').val(null).trigger('change');
        $('#negara_port').empty();
    });

});

// INIT TOOLTIP
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
});