// ====================== BOOKING JOB MODULE ======================
(function() {
    // ====================== CONFIG ======================
    const config = window.BookingJobConfig || {};
    const LIST_URL = config.listUrl;
    const NEXTNO_URL = config.nextNoUrl;
    const STORE_URL = config.storeUrl;
    const BASE_URL = config.baseUrl;
    const CSRF_NAME = config.csrfName;
    const CSRF_HASH = config.csrfHash;
    const AUTO_ADD = config.autoAdd || false;

    let currentType = 'import_lcl';

    // ====================== INIT DATATABLES ======================
    const isMobile = window.innerWidth < 576;

    const tbl = $('#tblBookings').DataTable({
        fixedColumns: isMobile ? false : {
            left: 2,
            right: 2
        },
        ajax: {
            url: LIST_URL,
            data: function(d) {
                d.type = currentType;
            },
            dataSrc: 'data'
        },
        columns: [
            {
                data: null,
                render: function(data, type, row, meta) {
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
                data: 'id',
                render: function(data, type, row) {
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
    $('#tblBookings').on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        const no_job = $(this).data('no_job');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Data Booking Job dengan nomor:'${no_job}' akan dihapus!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            $.ajax({
                url: `${BASE_URL}/booking-job/delete/${id}`,
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    Swal.close();
                    if (res.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        tbl.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: res.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseText || 'Terjadi kesalahan pada server.'
                    });
                }
            });
        });
    });

    // ====================== FILTER JOB TYPE ======================
    $('.filter-btn').on('click', function() {
        currentType = $(this).data('type');
        $('.filter-btn').removeClass('active btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary active');
        tbl.search('').page('first').ajax.reload(function() {}, true);
    });

    // ====================== BUKA MODAL TAMBAH ======================
    $('#btnAdd').on('click', function() {
        $('#formBooking')[0].reset();
        $('#noJob').val('');
        $('#formErrors').addClass('d-none').html('');
        $('#modalBooking').modal('show');
    });

    // ====================== GENERATE NOMOR JOB ======================
    $('#jobType').on('change', function() {
        const type = $(this).val();
        if (!type) {
            $('#noJob').val('');
            return;
        }
        $.get(NEXTNO_URL, { type: type })
            .done(function(res) {
                if (res.status === 'ok') {
                    $('#noJob').val(res.no_job);
                } else {
                    $('#noJob').val('');
                    alert(res.message || 'Gagal membuat nomor job');
                }
            })
            .fail(function() {
                alert('Gagal request nomor job. Cek koneksi atau route.');
            });
    });

    //  generate no pib
    $('#modalBooking').on('shown.bs.modal', function () {

        const jenisNomor = document.getElementById('jenisNomor');
        const noPibPo = document.getElementById('noPibPo');
    
        jenisNomor.addEventListener('change', async function () {
            if (this.value === 'PIB') {
    
                const response = await fetch('/booking-job/generate-no-pib');
                const data = await response.json();
    
                console.log("DARI SERVER:", data);
    
                noPibPo.value = data.no_pib_po;
    
            } else {
                noPibPo.value = "";
            }
        });
    });


    $('#modalEditBooking').on('shown.bs.modal', function (e) {

        const editJenisNomor = document.getElementById('editJenisNomor');
        const editNoPibPo = document.getElementById('editNoPibPo');

        // ---- Saat dropdown diubah ----
        editJenisNomor.addEventListener('change', async function () {

            if (this.value === 'PIB') {
                // Auto generate dari server
                const response = await fetch('/booking-job/generate-no-pib');
                const data = await response.json();

                console.log("Generate Edit:", data);

                editNoPibPo.value = data.no_pib_po;

            } else {
                // Jika bukan PIB → manual
                editNoPibPo.value = "";
            }
        });

    });


    // ====================== SELECT2: IMPORTIR/EXPORTIR ======================
    $('#modalBooking').on('shown.bs.modal', function() {

        // ------------------ RESET VALUE SETIAP MODAL DIBUKA ------------------
        $('#namaConsignee').val(null).trigger('change');  // kosongkan
        $('#namaPort').val(null).trigger('change');  // kosongkan
        $('#namaPelayaran').val(null).trigger('change');  // kosongkan

        // ------------------ HANCURKAN SELECT2 LAMA JIKA ADA ------------------
        if ($('#namaConsignee').hasClass('select2-hidden-accessible')) {
            $('#namaConsignee').select2('destroy');
        }

        // ------------------ INIT SELECT2 ULANG ------------------
        $('#namaConsignee').select2({
            placeholder: "Cari Importir / Exportir...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 1,
            dropdownParent: $('#modalBooking'),

            ajax: {
                url: `${BASE_URL}/booking-job/search-consignee`,
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return { term: params.term || '' };
                },
                processResults: function(data) {
                    return { results: data };
                }
            },

            templateResult: data => data.text,
            templateSelection: data => data.text || data.id
        });

        // ------------------ OVERRIDE VALUE AGAR KIRIM NAMA, BUKAN ID ------------------
        $('#namaConsignee').on('select2:select', function(e) {
            let nama = e.params.data.text;
            let newOption = new Option(nama, nama, true, true);
            $(this).empty().append(newOption).trigger('change');
            console.log("Kirim ke server (consignee):", nama);
        });
    });

    // ------------------ DESTROY SELECT2 SAAT MODAL DITUTUP ------------------
    $('#modalBooking').on('hidden.bs.modal', function() {
        if ($('#namaConsignee').hasClass('select2-hidden-accessible')) {
            $('#namaConsignee').select2('destroy');
        }
        $('#namaConsignee').val(null); // reset value utama
    });


    

    // ====================== SELECT2: PORT ======================
    $('#modalBooking').on('shown.bs.modal', function() {

        if ($('#namaPort').hasClass('select2-hidden-accessible')) {
            $('#namaPort').select2('destroy');
        }

        $('#namaPort').select2({
            placeholder: "Cari POL/POD...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 1,
            dropdownParent: $('#modalBooking'),

            ajax: {
                url: `${BASE_URL}/booking-job/search-port`,
                dataType: "json",
                delay: 250,
                data: params => ({ term: params.term || '' }),
                processResults: data => ({ results: data })
            },

            templateResult: data => data.text,               
            templateSelection: data => data.text || data.id  
        });

        // ========== OVERRIDE VALUE AGAR SIMPAN NAMA, BUKAN ID ==========
        $('#namaPort').on('select2:select', function(e) {

            let nama = e.params.data.text;
            let newOption = new Option(nama, nama, true, true);
            $(this).empty().append(newOption).trigger('change');
            console.log("Kirim ke server (PORT):", nama);
        });

    });

    $('#modalBooking').on('hidden.bs.modal', function() {
        if ($('#namaPort').hasClass('select2-hidden-accessible')) {
            $('#namaPort').select2('destroy');
        }
    });


    // ====================== SELECT2: PELAYARAN ======================
    $('#modalBooking').on('shown.bs.modal', function() {

        if ($('#namaPelayaran').hasClass('select2-hidden-accessible')) {
            $('#namaPelayaran').select2('destroy');
        }

        $('#namaPelayaran').select2({
            placeholder: "Cari Shipping Line...",
            allowClear: true,
            width: '100%',
            minimumInputLength: 1,
            dropdownParent: $('#modalBooking'),

            ajax: {
                url: `${BASE_URL}/booking-job/search-pelayaran`,
                dataType: "json",
                delay: 250,
                data: params => ({ term: params.term || '' }),
                processResults: data => ({ results: data })
            },

            // tampil nama, bukan ID
            templateResult: data => data.text,
            templateSelection: data => data.text || data.id
        });

        // ========== OVERRIDE VALUE AGAR SIMPAN NAMA, BUKAN ID ==========
        $('#namaPelayaran').on('select2:select', function(e) {

            let nama = e.params.data.text;
            let newOption = new Option(nama, nama, true, true);
            $(this).empty().append(newOption).trigger('change');
            console.log("Kirim ke server (PEL):", nama);
        });

    });

    $('#modalBooking').on('hidden.bs.modal', function() {
        if ($('#namaPelayaran').hasClass('select2-hidden-accessible')) {
            $('#namaPelayaran').select2('destroy');
        }
    });

    

    // ====================== SUBMIT FORM TAMBAH ======================
    $('#formBooking').on('submit', function(e) {
        e.preventDefault();
        const data = $(this).serialize();

        $.ajax({
            url: STORE_URL,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'ok') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                
                    // ========== KOSONGKAN SELECT2 ==========
                    $('#namaConsignee').val(null).trigger('change');
                    $('#namaPort').val(null).trigger('change');
                    $('#namaPelayaran').val(null).trigger('change');
                
                    // reset input lainnya
                    $('#formBooking')[0].reset();
                
                    $('#modalBooking').modal('hide');
                    tbl.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                let msg = 'Terjadi kesalahan saat menyimpan booking.';
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch (e) {
                    msg = xhr.responseText || msg;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: msg
                });
            }
        });
    });

    // ====================== EDIT BOOKING ======================
    $('#tblBookings').on('click', '.btn-edit', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Memuat data...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: BASE_URL + '/booking-job/edit/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                Swal.close();
                if (res.status === 'ok') {
                    const d = res.data;
                    $('#editId').val(d.id);
                    $('#editJobType').val(d.type);
                    $('#editNoJob').val(d.no_job);
                    $('#editNoPibPo').val(d.no_pib_po);
                    $('#editConsignee').val(d.consignee);
                    $('#editParty').val(d.party);
                    $('#editEta').val(d.eta);
                    $('#editPol').val(d.pol);
                    $('#editShippingLine').val(d.shipping_line);
                    $('#editBl').val(d.bl);
                    $('#editMasterBl').val(d.master_bl);
                    $('#editFormErrors').addClass('d-none').html('');
                    $('#modalEditBooking').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseText || 'Terjadi kesalahan saat mengambil data.'
                });
            }
        });
    });

    // ====================== UPDATE BOOKING ======================
    $('#formEditBooking').on('submit', function(e) {
        e.preventDefault();
        const id = $('#editId').val();
        const data = $(this).serialize();

        Swal.fire({
            title: 'Menyimpan...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: BASE_URL + '/booking-job/update/' + id,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(res) {
                Swal.close();
                if (res.status === 'ok') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                
                    // ========== KOSONGKAN SELECT2 ==========
                    $('#namaConsignee').val(null).trigger('change');
                    $('#namaPort').val(null).trigger('change');
                    $('#namaPelayaran').val(null).trigger('change');
                
                    // reset input lainnya
                    $('#formBooking')[0].reset();
                
                    $('#modalBooking').modal('hide');
                    tbl.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Gagal!',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                let msg = 'Terjadi kesalahan saat update booking.';
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json.message) msg = json.message;
                } catch (e) {
                    msg = xhr.responseText || msg;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: msg
                });
            }
        });
    });

    // ====================== EXPORT EXCEL ======================
    $('#btnExport').on('click', function(e) {
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

    $('#exportYear').on('change', function() {
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

    $('#modalExportExcel').on('hidden.bs.modal', function() {
        document.getElementById('jenisExport').value = '';
        document.getElementById('exportYear').value = '';
        document.getElementById('exportMonth').innerHTML = '<option value="">-- Semua Bulan --</option>';
    });

    $('#btnConfirmExportExcel').on('click', function() {
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

    // ====================== EXPORT PDF ======================
    $('#btnExportPdf').on('click', function(e) {
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

    $('#exportYearPdf').on('change', function() {
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

    $('#modalExportPdf').on('hidden.bs.modal', function() {
        document.getElementById('jenisExportPdf').value = '';
        document.getElementById('exportYearPdf').value = '';
        document.getElementById('exportMonthPdf').innerHTML = '<option value="">-- Semua Bulan --</option>';
    });

    $('#btnConfirmExportPdf').on('click', function() {
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
    $(document).on('click', '.btn-note', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const timestamp = Date.now();
        const encodedId = btoa(id + '-' + timestamp).replace(/=/g, '');
        window.open(`${BASE_URL}/booking-job/print-note/${encodedId}`, '_blank');
    });

    // ====================== KIRIM KE WORKSHEET ======================
    $('#tblBookings').on('click', '.btn-send', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Kirim ke Worksheet?',
            text: "Data booking job ini akan dikirim ke Worksheet.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Mengirim...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                $.ajax({
                    url: BASE_URL + '/booking-job/send-to-worksheet/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(res) {
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
                            Swal.fire({
                                icon: 'warning',
                                title: 'Gagal!',
                                text: res.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseText || 'Terjadi kesalahan saat mengirim data ke worksheet.'
                        });
                    }
                });
            }
        });
    });

    // ====================== REFRESH DATA ======================
    $('#btnRefresh').on('click', function() {
        Swal.fire({
            title: 'Memuat data...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        tbl.ajax.reload(function() {
            Swal.fire({
                icon: 'success',
                title: 'Data sudah terbaru!',
                timer: 1500,
                showConfirmButton: false
            });
        }, false);
    });

    // ====================== SAMPAH DATA ======================
    $('#btnTrash').on('click', function() {
        Swal.fire({
            title: 'Apakah Ingin Pindah Ke Halaman Sampah Booking Job ?',
            text: "Untuk melihat dan merestore data yang sudah kamu delete, silahkan pergi ke sampah booking job",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Pindah',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Redirect...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                window.location.href = '/booking-job-trash';
            }
        });
    });

    // ====================== AUTO ADD ======================
    if (AUTO_ADD) {
        setTimeout(() => {
            $('#btnAdd').trigger('click');
        }, 400);
    }

})();