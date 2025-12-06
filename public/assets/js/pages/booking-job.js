// ====================== BOOKING JOB MODULE ======================
(function () {
    // ====================== CONFIG ======================
    const config    = window.BookingJobConfig || {};
    const LIST_URL  = config.listUrl;
    const NEXTNO_URL= config.nextNoUrl;
    const STORE_URL = config.storeUrl;
    const BASE_URL  = config.baseUrl;
    const AUTO_ADD  = config.autoAdd || false;

    let currentType = 'import_lcl';

    // ====================== HELPER: SWEETALERT ======================
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

    // ====================== HELPER: UNIVERSAL SELECT2 ======================
    /**
     * Init Select2 universal untuk TAMBAH & EDIT
     * selector   : '#namaConsignee', '#editConsignee', dll
     * url        : endpoint AJAX
     * placeholder: teks placeholder
     * modalId    : '#modalBooking' / '#modalEditBooking'
     * oldValue   : nilai lama (hanya untuk edit), boleh null
     */
    function initSelect2Universal(selector, url, placeholder, modalId, oldValue = null) {
        const $el = $(selector);

        // Destroy jika sudah ada
        if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
        }

        // Set value lama (untuk edit)
        if (oldValue && oldValue !== '') {
            $el.html(`<option selected>${oldValue}</option>`);
        } else {
            $el.html('');
        }

        // Init Select2
        $el.select2({
            theme: 'bootstrap-5',
            placeholder: placeholder,
            allowClear: true,
            width: '100%',
            minimumInputLength: 0,
            dropdownParent: $(modalId),
            ajax: {
                url: url,
                dataType: 'json',
                delay: 150,
                data: params => ({ term: params.term || '' }),
                processResults: data => ({ results: data })
            },
            templateResult: data => data.text,
            templateSelection: data => data.text || data.id
        });

        // Override: yang disimpan SELALU nama, bukan ID
        $el.off('select2:select').on('select2:select', function (e) {
            const nama = e.params.data.text;
            $(this).html(`<option selected>${nama}</option>`).trigger('change');
        });
    }

    function destroySelect2List(selectors) {
        selectors.forEach(sel => {
            const $el = $(sel);
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }
            $el.val(null);
        });
    }

    // ====================== INIT DATATABLES ======================
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
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
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
                    if (data === 'open job') {
                        return '<span class="badge bg-primary">Open Job</span>';
                    } else if (data === 'worksheet') {
                        return '<span class="badge bg-success">Worksheet</span>';
                    }
                    return data;
                }
            },
            {
                data: 'id',
                render: function (data, type, row) {
                    if (row.status === 'worksheet') {
                        return `
                            <div class="aksi-grid">
                                <button class="btn btn-sm btn-primary btn-note" 
                                    data-id="${data}" 
                                    title="Print Note">
                                    <i class="bi bi-sticky"></i>
                                </button>
                                <button class="btn btn-sm btn-danger btn-delete" 
                                    data-id="${data}" 
                                    data-no_job="${row.no_job}" 
                                    title="Delete Job">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <span class="badge bg-secondary">Terkirim</span>
                            </div>
                        `;
                    }

                    return `
                        <div class="aksi-grid">
                            <button class="btn btn-sm btn-warning btn-edit" 
                                data-id="${data}" 
                                title="Edit Job">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" 
                                data-id="${data}" 
                                data-no_job="${row.no_job}" 
                                title="Delete Job">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="btn btn-sm btn-primary btn-note" 
                                data-id="${data}" 
                                title="Print Note">
                                <i class="bi bi-sticky"></i>
                            </button>
                            <button class="btn btn-sm btn-success btn-send" 
                                data-id="${data}" 
                                title="Kirim ke Worksheet">
                                <i class="bi bi-send"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'desc']]
    });

    // ====================== DELETE DATA ======================
    $('#tblBookings').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const no_job = $(this).data('no_job');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Data Booking Job dengan nomor: '${no_job}' akan dihapus!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoading('Menghapus...', 'Mohon tunggu sebentar');

            $.ajax({
                url: `${BASE_URL}/booking-job/delete/${id}`,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    Swal.close();
                    if (res.status === 'ok') {
                        showSuccess(res.message);
                        tbl.ajax.reload(null, false);
                    } else {
                        showError(res.message);
                    }
                },
                error: function (xhr) {
                    showError(xhr.responseText || 'Terjadi kesalahan pada server.');
                }
            });
        });
    });

    // ====================== FILTER JOB TYPE ======================
    $('.filter-btn').on('click', function () {
        currentType = $(this).data('type');
        $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
        tbl.search('').page('first').ajax.reload(function () { }, true);
    });

    // ====================== BUKA MODAL TAMBAH ======================
    $('#btnAdd').on('click', function () {
        $('#formBooking')[0].reset();
        $('#noJob').val('');
        $('#formErrors').addClass('d-none').html('');
        $('#modalBooking').modal('show');
    });

    // ====================== GENERATE NOMOR JOB ======================
    $('#jobType').on('change', function () {
        const type = $(this).val();
        if (!type) {
            $('#noJob').val('');
            return;
        }
        $.get(NEXTNO_URL, { type: type })
            .done(function (res) {
                if (res.status === 'ok') {
                    $('#noJob').val(res.no_job);
                } else {
                    $('#noJob').val('');
                    alert(res.message || 'Gagal membuat nomor job');
                }
            })
            .fail(function () {
                alert('Gagal request nomor job. Cek koneksi atau route.');
            });
    });

    // ====================== GENERATE NO PIB (TAMBAH) ======================
    const $jenisNomor = $('#jenisNomor');
    const $noPibPo    = $('#noPibPo');

    if ($jenisNomor.length && $noPibPo.length) {
        $jenisNomor.off('change').on('change', async function () {
            if (this.value === 'PIB') {
                try {
                    const response = await fetch(`${BASE_URL}/booking-job/generate-no-pib`);
                    const data = await response.json();
                    console.log('DARI SERVER (Tambah):', data);
                    $noPibPo.val(data.no_pib_po);
                } catch (err) {
                    console.error('Gagal generate no PIB (Tambah)', err);
                    $noPibPo.val('');
                }
            } else {
                $noPibPo.val('');
            }
        });
    }

    // ====================== SELECT2: TAMBAH (UNIVERSAL) ======================
    $('#modalBooking').on('shown.bs.modal', function () {
        initSelect2Universal(
            '#namaConsignee',
            `${BASE_URL}/booking-job/search-consignee`,
            'Cari Importir / Exportir...',
            '#modalBooking'
        );
        initSelect2Universal(
            '#namaPort',
            `${BASE_URL}/booking-job/search-port`,
            'Cari POL/POD...',
            '#modalBooking'
        );
        initSelect2Universal(
            '#namaPelayaran',
            `${BASE_URL}/booking-job/search-pelayaran`,
            'Cari Shipping Line...',
            '#modalBooking'
        );
    });

    $('#modalBooking').on('hidden.bs.modal', function () {
        destroySelect2List(['#namaConsignee', '#namaPort', '#namaPelayaran']);
    });

    // ====================== SUBMIT FORM TAMBAH ======================
    $('#formBooking').on('submit', function (e) {
        e.preventDefault();
        const data = $(this).serialize();

        $.ajax({
            url: STORE_URL,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res.status === 'ok') {
                    showSuccess(res.message);

                    $('#namaConsignee').val(null).trigger('change');
                    $('#namaPort').val(null).trigger('change');
                    $('#namaPelayaran').val(null).trigger('change');

                    $('#formBooking')[0].reset();
                    $('#modalBooking').modal('hide');
                    tbl.ajax.reload();
                } else {
                    showWarning(res.message);
                }
            },
            error: function (xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan booking.';
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch (e) {
                    msg = xhr.responseText || msg;
                }
                showError(msg);
            }
        });
    });

    // =====================================================================
    // ======================== BAGIAN EDIT BOOKING =========================
    // =====================================================================

    // ====================== BUKA MODAL EDIT ======================
    $('#tblBookings').on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        $('#editFormErrors').addClass('d-none').html('');

        showLoading('Memuat data...');

        $.ajax({
            url: BASE_URL + '/booking-job/edit/' + id,
            type: 'GET',
            dataType: 'json',

            success: function (res) {
                Swal.close();

                if (res.status !== 'ok') {
                    showWarning(res.message);
                    return;
                }

                const d = res.data;

                // Reset form
                $('#formEditBooking')[0].reset();

                // Isi input biasa
                $('#editId').val(d.id);
                $('#editJobType').val(d.type);
                $('#editNoJob').val(d.no_job);
                $('#editNoPibPo').val(d.no_pib_po);
                $('#editParty').val(d.party);
                $('#editEta').val(d.eta);
                $('#editBl').val(d.bl);
                $('#editMasterBl').val(d.master_bl);

                // Set value lama ke select (nanti dibaca universal Select2)
                $('#editConsignee').html(`<option selected>${d.consignee}</option>`);
                $('#editPol').html(`<option selected>${d.pol}</option>`);
                $('#editShippingLine').html(`<option selected>${d.shipping_line}</option>`);

                // Tampilkan modal
                $('#modalEditBooking').modal('show');
            },

            error: function (xhr) {
                Swal.close();
                showError(xhr.responseText);
            }
        });
    });

    // ====================== SELECT2 EDIT (UNIVERSAL) ======================
    $('#modalEditBooking').on('shown.bs.modal', function () {
        const oldConsignee = $('#editConsignee option:selected').text();
        const oldPol       = $('#editPol option:selected').text();
        const oldShip      = $('#editShippingLine option:selected').text();

        initSelect2Universal(
            '#editConsignee',
            `${BASE_URL}/booking-job/search-consignee`,
            'Cari Importir / Exportir...',
            '#modalEditBooking',
            oldConsignee
        );
        initSelect2Universal(
            '#editPol',
            `${BASE_URL}/booking-job/search-port`,
            'Cari POL/POD...',
            '#modalEditBooking',
            oldPol
        );
        initSelect2Universal(
            '#editShippingLine',
            `${BASE_URL}/booking-job/search-pelayaran`,
            'Cari Shipping Line...',
            '#modalEditBooking',
            oldShip
        );
    });

    $('#modalEditBooking').on('hidden.bs.modal', function () {
        destroySelect2List(['#editConsignee', '#editPol', '#editShippingLine']);
    });

    // ====================== UPDATE BOOKING ======================
    $('#formEditBooking').on('submit', function (e) {
        e.preventDefault();

        const id   = $('#editId').val();
        const data = $(this).serialize();

        showLoading('Menyimpan...');

        $.ajax({
            url: `${BASE_URL}/booking-job/update/${id}`,
            type: 'POST',
            data: data,
            dataType: 'json',

            success: function (res) {
                Swal.close();

                if (res.status === 'ok') {
                    showSuccess(res.message);
                    $('#modalEditBooking').modal('hide');
                    tbl.ajax.reload();
                } else {
                    showWarning(res.message);
                }
            },

            error: function (xhr) {
                Swal.close();
                let msg = 'Terjadi kesalahan saat update booking.';
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch {
                    msg = xhr.responseText || msg;
                }
                showError(msg);
            }
        });

    });

    // =====================================================================
    // ========================== EXPORT EXCEL ==============================
    // =====================================================================
    $('#btnExport').on('click', function (e) {
        e.preventDefault();
        $('#modalExportExcel').modal('show');

        fetch(`${BASE_URL}/booking-job/get-years`)
            .then(res => res.json())
            .then(data => {
                const selectYear = document.getElementById('exportYear');
                selectYear.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                data.years.forEach(y => {
                    selectYear.innerHTML += `<option value="${y}">${y}</option>`;
                });
            })
            .catch(() => console.error('Gagal memuat daftar tahun'));
    });

    $('#exportYear').on('change', function () {
        const year = this.value;
        const selectMonth = document.getElementById('exportMonth');
        selectMonth.innerHTML = '<option value="">-- Semua Bulan --</option>';

        if (!year) return;

        fetch(`${BASE_URL}/booking-job/get-months/${year}`)
            .then(res => res.json())
            .then(data => {
                data.months.forEach(m => {
                    selectMonth.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                });
            })
            .catch(() => console.error('Gagal memuat daftar bulan'));
    });

    $('#modalExportExcel').on('hidden.bs.modal', function () {
        document.getElementById('jenisExport').value = '';
        document.getElementById('exportYear').value = '';
        document.getElementById('exportMonth').innerHTML = '<option value="">-- Semua Bulan --</option>';
    });

    $('#btnConfirmExportExcel').on('click', function () {
        const jenis = document.getElementById('jenisExport').value;
        const tahun = document.getElementById('exportYear').value;
        const bulan = document.getElementById('exportMonth').value;

        if (!jenis || !tahun) {
            Swal.fire({
                icon: 'warning',
                title: 'Data belum lengkap!',
                text: 'Silakan pilih jenis booking job dan tahun terlebih dahulu.'
            });
            return;
        }

        $('#modalExportExcel').modal('hide');

        let url = `${BASE_URL}/booking-job/export-excel/${jenis}?year=${tahun}`;
        if (bulan) {
            url += `&month=${bulan}`;
        }

        window.location.href = url;

        Swal.fire({
            icon: 'success',
            title: 'Export Dimulai',
            text: 'File Excel akan segera diunduh.',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // =====================================================================
    // ========================== EXPORT PDF ================================
    // =====================================================================
    $('#btnExportPdf').on('click', function (e) {
        e.preventDefault();
        $('#modalExportPdf').modal('show');

        fetch(`${BASE_URL}/booking-job/get-years`)
            .then(res => res.json())
            .then(data => {
                const selectYear = document.getElementById('exportYearPdf');
                selectYear.innerHTML = '<option value="">-- Pilih Tahun --</option>';
                data.years.forEach(y => {
                    selectYear.innerHTML += `<option value="${y}">${y}</option>`;
                });
            })
            .catch(() => console.error('Gagal memuat daftar tahun'));
    });

    $('#exportYearPdf').on('change', function () {
        const year = this.value;
        const selectMonth = document.getElementById('exportMonthPdf');
        selectMonth.innerHTML = '<option value="">-- Semua Bulan --</option>';

        if (!year) return;

        fetch(`${BASE_URL}/booking-job/get-months/${year}`)
            .then(res => res.json())
            .then(data => {
                data.months.forEach(m => {
                    selectMonth.innerHTML += `<option value="${m.month}">${m.name}</option>`;
                });
            })
            .catch(() => console.error('Gagal memuat daftar bulan'));
    });

    $('#modalExportPdf').on('hidden.bs.modal', function () {
        document.getElementById('jenisExportPdf').value = '';
        document.getElementById('exportYearPdf').value = '';
        document.getElementById('exportMonthPdf').innerHTML = '<option value="">-- Semua Bulan --</option>';
    });

    $('#btnConfirmExportPdf').on('click', function () {
        const jenis = document.getElementById('jenisExportPdf').value;
        const tahun = document.getElementById('exportYearPdf').value;
        const bulan = document.getElementById('exportMonthPdf').value;

        if (!jenis || !tahun) {
            Swal.fire({
                icon: 'warning',
                title: 'Data belum lengkap!',
                text: 'Silakan pilih jenis booking job dan tahun terlebih dahulu.'
            });
            return;
        }

        $('#modalExportPdf').modal('hide');

        let url = `${BASE_URL}/booking-job/export-pdf/${jenis}?year=${tahun}`;
        if (bulan) {
            url += `&month=${bulan}`;
        }

        window.location.href = url;

        Swal.fire({
            icon: 'success',
            title: 'Export Dimulai',
            text: 'File PDF akan segera diunduh.',
            timer: 2000,
            showConfirmButton: false
        });
    });

    // ====================== PRINT NOTE ======================
    $(document).on('click', '.btn-note', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const timestamp = Date.now();
        const encodedId = btoa(id + '-' + timestamp).replace(/=/g, '');
        window.open(`${BASE_URL}/booking-job/print-note/${encodedId}`, '_blank');
    });

    // ====================== KIRIM KE WORKSHEET ======================
    $('#tblBookings').on('click', '.btn-send', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Kirim ke Worksheet?',
            text: 'Data booking job ini akan dikirim ke Worksheet.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoading('Mengirim...');

            $.ajax({
                url: BASE_URL + '/booking-job/send-to-worksheet/' + id,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    Swal.close();
                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            tbl.ajax.reload();
                            Swal.fire({
                                title: 'Ingin pergi ke modul Worksheet?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#28a745',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((choice) => {
                                if (choice.isConfirmed) {
                                    window.location.href = BASE_URL + '/worksheet';
                                }
                            });
                        });
                    } else {
                        showWarning(res.message);
                    }
                },
                error: function (xhr) {
                    Swal.close();
                    showError(xhr.responseText || 'Terjadi kesalahan saat mengirim data ke worksheet.');
                }
            });
        });
    });

    // ====================== REFRESH DATA ======================
    $('#btnRefresh').on('click', function () {
        showLoading('Memuat data...', 'Mohon tunggu sebentar.');

        tbl.ajax.reload(function () {
            Swal.fire({
                icon: 'success',
                title: 'Data sudah terbaru!',
                timer: 1500,
                showConfirmButton: false
            });
        }, false);
    });

    // ====================== SAMPAH DATA ======================
    $('#btnTrash').on('click', function () {
        Swal.fire({
            title: 'Apakah Ingin Pindah Ke Halaman Sampah Booking Job ?',
            text: 'Untuk melihat dan merestore data yang sudah kamu delete, silahkan pergi ke sampah booking job',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pindah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            showLoading('Redirect...');
            window.location.href = '/booking-job-trash';
        });
    });

    // ====================== AUTO ADD ======================
    if (AUTO_ADD) {
        setTimeout(() => {
            $('#btnAdd').trigger('click');
        }, 400);
    }

})();
