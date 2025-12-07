<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Tambah Booking Job<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading mb-4">
    <h3>Tambah Booking Job</h3>
</div>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="card shadow-sm p-4">
    <form id="formAddBooking" action="<?= base_url('booking-job/store') ?>" method="post">
        <?= csrf_field() ?>

        <!-- ================= INFORMASI UTAMA ================= -->
        <h5 class="mt-2 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Utama</h5>

        <div class="row">
            <!-- Jenis Job -->
            <div class="col-md-6 mb-3">
                <label>Jenis Job</label>
                <select name="type" id="jobType" class="form-select" required>
                    <option value="">-- Pilih Jenis Job --</option>
                    <option value="import_lcl">Import LCL</option>
                    <option value="import_fcl_jaminan">Import FCL Jaminan</option>
                    <option value="import_fcl_nonjaminan">Import FCL Non-Jaminan</option>
                    <option value="lain">Import Lain-lain</option>
                    <option value="export">Export</option>
                </select>
            </div>

            <!-- Nomor Job -->
            <div class="col-md-6 mb-3">
                <label>Nomor Job</label>
                <input type="text" name="no_job" id="noJob" class="form-control" required readonly>
            </div>

            <!-- Jenis Nomor -->
            <div class="col-md-6 mb-3">
                <label>Jenis Nomor</label>
                <select name="jenis_nomor" id="jenisNomor" class="form-select" required>
                    <option value="">-- Pilih Jenis Nomor --</option>
                    <option value="PIB">No PIB/PEB</option>
                    <option value="PIB-SENDIRI">No PIB/PEB Sendiri</option>
                    <option value="PO-SENDIRI">No PO Sendiri</option>
                </select>

                <input type="text" name="no_pib_po" id="noPibPo"
                    class="form-control mt-1" placeholder="Masukkan Nomor" required>
            </div>

            <!-- Consignee -->
            <div class="col-md-6 mb-3">
                <label>Importir / Exportir</label>
                <select name="consignee" id="namaConsignee" class="form-control"></select>
            </div>

        </div>

        <!-- ================= INFORMASI PENGANGKUTAN ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Pengangkutan</h5>

        <div class="row">

            <!-- Shipping Line -->
            <div class="col-md-6 mb-3">
                <label>Pelayaran / Shipping Line</label>
                <select name="shipping_line" id="namaPelayaran" class="form-control"></select>
            </div>

            <!-- POL -->
            <div class="col-md-6 mb-3">
                <label>POL / POD</label>
                <select name="pol" id="namaPort" class="form-control"></select>
            </div>

            <!-- ETA -->
            <div class="col-md-6 mb-3">
                <label>ETA / ETD</label>
                <input type="date" name="eta" class="form-control" required>
            </div>

            <!-- Party -->
            <div class="col-md-6 mb-3">
                <label>Party</label>
                <input type="text" name="party" class="form-control"
                    placeholder="1 X 20 / LCL 1 PK" required>
            </div>
        </div>

        <!-- ================= INFORMASI DOKUMEN ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Dokumen</h5>

        <div class="row">
            <!-- BL -->
            <div class="col-md-6 mb-3">
                <label>Nomor BL</label>
                <input type="text" name="bl" class="form-control" required>
            </div>

            <!-- Master BL -->
            <div class="col-md-6 mb-3">
                <label>Master BL</label>
                <input type="text" name="master_bl" class="form-control" required>
            </div>
        </div>

        <hr class="border-2 border-primary mt-4">

        <div class="mt-3 d-flex justify-content-between">
            <a href="<?= base_url('booking-job') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-floppy me-1"></i> Simpan
            </button>
        </div>

    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<script>
    const BASE_URL = "<?= base_url() ?>";

    // ========================== INIT SELECT2 UNIVERSAL ==========================
    function initSelect2(selector, url, placeholder) {
        $(selector).select2({
            theme: "bootstrap-5",
            placeholder: placeholder,
            allowClear: true,
            width: "100%",
            minimumInputLength: 0,
            ajax: {
                url: url,
                dataType: "json",
                delay: 150,
                data: params => ({
                    term: params.term || ""
                }),
                processResults: data => ({
                    results: data
                })
            }
        }).on("select2:select", function(e) {
            $(this).html(`<option selected>${e.params.data.text}</option>`);
        });
    }

    // Init Select2
    initSelect2("#namaConsignee", BASE_URL + "/booking-job/search-consignee", "Cari Importir / Exportir...");
    initSelect2("#namaPort", BASE_URL + "/booking-job/search-port", "Cari POL/POD...");
    initSelect2("#namaPelayaran", BASE_URL + "/booking-job/search-pelayaran", "Cari Shipping Line...");

    // ========================== GENERATE NOMOR JOB ==========================
    $("#jobType").on("change", function() {
        const type = this.value;
        if (!type) return $("#noJob").val("");

        $.get(BASE_URL + "/booking-job/nextno", {
                type: type
            })
            .done(res => {
                if (res.status === "ok") $("#noJob").val(res.no_job);
                else $("#noJob").val("");
            })
            .fail(() => alert("Gagal membuat nomor job"));
    });

    // ========================== GENERATE NO PIB ==========================
    $("#jenisNomor").on("change", async function() {
        if (this.value === "PIB") {
            try {
                const res = await fetch(BASE_URL + "/booking-job/generate-no-pib");
                const d = await res.json();
                $("#noPibPo").val(d.no_pib_po);
            } catch (e) {
                $("#noPibPo").val("");
            }
        } else {
            $("#noPibPo").val("");
        }
    });
</script>
<?= $this->endSection() ?>