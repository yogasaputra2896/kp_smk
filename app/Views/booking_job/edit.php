<?= $this->extend('layouts/layout') ?>

<?= $this->section('title') ?>Edit Booking Job<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading mb-4">
    <h3>Edit Booking Job</h3>
</div>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<div class="card shadow-sm p-4">
    <form id="formEditBooking"
        action="<?= base_url('booking-job/update/' . $booking['id']) ?>"
        method="post">

        <?= csrf_field() ?>

        <!-- ================= INFORMASI UTAMA ================= -->
        <h5 class="mt-2 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Utama</h5>

        <div class="row">

            <!-- Jenis Job -->
            <div class="col-md-6 mb-3">
                <label>Jenis Job</label>
                <select name="type" id="editJobType" class="form-select" required>
                    <option value="import_lcl" <?= $booking['type'] == 'import_lcl' ? 'selected' : '' ?>>Import LCL</option>
                    <option value="import_fcl_jaminan" <?= $booking['type'] == 'import_fcl_jaminan' ? 'selected' : '' ?>>Import FCL Jaminan</option>
                    <option value="import_fcl_nonjaminan" <?= $booking['type'] == 'import_fcl_nonjaminan' ? 'selected' : '' ?>>Import FCL Non-Jaminan</option>
                    <option value="lain" <?= $booking['type'] == 'lain' ? 'selected' : '' ?>>Import Lain-lain</option>
                    <option value="export" <?= $booking['type'] == 'export' ? 'selected' : '' ?>>Export</option>
                </select>
            </div>

            <!-- Nomor Job -->
            <div class="col-md-6 mb-3">
                <label>Nomor Job</label>
                <input type="text" name="no_job" id="editNoJob" class="form-control"
                    value="<?= $booking['no_job'] ?>" required>
            </div>

            <!-- Jenis Nomor -->
            <div class="col-md-6 mb-3">
                <label>Nomor PIB/PEB/PO</label>
                <input type="text" name="no_pib_po" id="editNoPibPo" class="form-control mt-1"
                    value="<?= $booking['no_pib_po'] ?>" required>
            </div>

            <!-- Consignee -->
            <div class="col-md-6 mb-3">
                <label>Importir / Exportir</label>
                <select name="consignee" id="editConsignee" class="form-control">
                    <option selected><?= $booking['consignee'] ?></option>
                </select>
            </div>

            <!-- Party -->
            <div class="col-md-6 mb-3">
                <label>Party</label>
                <input type="text" name="party" class="form-control"
                    value="<?= $booking['party'] ?>" required>
            </div>
        </div>

        <!-- ================= INFORMASI PENGANGKUTAN ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Pengangkutan</h5>

        <div class="row">
            <!-- ETA -->
            <div class="col-md-6 mb-3">
                <label>ETA / ETD</label>
                <input type="date" name="eta" class="form-control"
                    value="<?= $booking['eta'] ?>" required>
            </div>

            <!-- POL -->
            <div class="col-md-6 mb-3">
                <label>POL / POD</label>
                <select name="pol" id="editPol" class="form-control">
                    <option selected><?= $booking['pol'] ?></option>
                </select>
            </div>

            <!-- Shipping Line -->
            <div class="col-md-6 mb-3">
                <label>Pelayaran / Shipping Line</label>
                <select name="shipping_line" id="editShippingLine" class="form-control">
                    <option selected><?= $booking['shipping_line'] ?></option>
                </select>
            </div>
        </div>

        <!-- ================= INFORMASI DOKUMEN ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom border-primary pb-2">Informasi Dokumen</h5>

        <div class="row">
            <!-- BL -->
            <div class="col-md-6 mb-3">
                <label>Nomor BL</label>
                <input type="text" name="bl" class="form-control"
                    value="<?= $booking['bl'] ?>" required>
            </div>

            <!-- Master BL -->
            <div class="col-md-6 mb-3">
                <label>Master BL</label>
                <input type="text" name="master_bl" class="form-control"
                    value="<?= $booking['master_bl'] ?>" required>
            </div>
        </div>

        <hr class="border-2 border-primary mt-4">

        <div class="mt-3 d-flex justify-content-between">
            <a href="<?= base_url('booking-job') ?>" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <button type="submit" class="btn btn-warning text-dark">
                <i class="bi bi-floppy2-fill"></i> Update Booking
            </button>
        </div>

    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>

<script>
    const BASE_URL = "<?= base_url() ?>";

    // UNIVERSAL SELECT2
    function initSelect2Edit(selector, url, placeholder, oldValue) {

        // Destroy jika sudah ada
        if ($(selector).hasClass("select2-hidden-accessible")) {
            $(selector).select2("destroy");
        }

        // Set nilai awal (nama)
        if (oldValue && oldValue !== "") {
            $(selector).html(`<option selected>${oldValue}</option>`);
        } else {
            $(selector).html("");
        }

        // Init Select2
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
        });

        // OVERRIDE: SIMPAN SELALU NAMA BUKAN ID
        $(selector).off("select2:select").on("select2:select", function(e) {
            const nama = e.params.data.text;
            $(this).html(`<option selected>${nama}</option>`).trigger("change");
        });
    }


    // INIT SELECT2
    initSelect2Edit("#editConsignee",
        BASE_URL + "/booking-job/search-consignee",
        "Cari Importir / Exportir...",
        "<?= $booking['consignee'] ?>"
    );

    initSelect2Edit("#editPol",
        BASE_URL + "/booking-job/search-port",
        "Cari POL / POD...",
        "<?= $booking['pol'] ?>"
    );

    initSelect2Edit("#editShippingLine",
        BASE_URL + "/booking-job/search-pelayaran",
        "Cari Shipping Line...",
        "<?= $booking['shipping_line'] ?>"
    );

    // GENERATE NO PIB OTOMATIS
    $("#editJenisNomor").on("change", async function() {
        if (this.value === "PIB") {
            try {
                const res = await fetch(BASE_URL + "/booking-job/generate-no-pib");
                const d = await res.json();
                $("#editNoPibPo").val(d.no_pib_po);
            } catch (err) {
                $("#editNoPibPo").val("");
            }
        } else {
            $("#editNoPibPo").val("");
        }
    });
</script>

<?= $this->endSection() ?>