<?= $this->extend('layouts/layout') ?>
<?= $this->section('title') ?>Edit Worksheet Import<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>
<div class="page-heading mb-4">
    <h3>Edit Worksheet Import</h3>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm p-4">
    <form action="<?= base_url('worksheet/import/update/' . $worksheet['id']) ?>" method="post">
        <input type="hidden" name="type" value="import">

        <!-- ================= INFORMASI UTAMA ================= -->
        <h5 class="mb-3 text-primary fw-bold border-bottom pb-2">Informasi Utama</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>No Worksheet</label>
                <input type="text" class="form-control-plaintext" readonly value="<?= esc($worksheet['no_ws']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label>Tanggal Worksheet</label>
                <input type="text" class="form-control-plaintext" readonly value="<?= esc($worksheet['created_at']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Nama Consignee / Importir</label>
                <input type="text" name="consignee" class="form-control" value="<?= esc($worksheet['consignee']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Notify Party (Opsional)</label>
                <input type="text" name="notify_party" class="form-control" value="<?= esc($worksheet['notify_party']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Nama Shipper</label>
                <input type="text" name="shipper" class="form-control" value="<?= esc($worksheet['shipper']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>PO Number (Opsional)</label>
                <input type="text" name="no_po" class="form-control" value="<?= esc($worksheet['no_po']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>IO Number (Opsional)</label>
                <input type="text" name="io_number" class="form-control" value="<?= esc($worksheet['io_number']) ?>">
            </div>

        </div>

        <!-- ================= INFORMASI PIB ================= -->
        <br>
        <h5 class="mb-3 text-primary fw-bold border-bottom pb-2">Informasi PIB</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Pengurusan PIB</label>
                <select name="pengurusan_pib" class="form-select">
                    <option value="">-- Pilih Pengurusan PIB --</option>
                    <option value="Pembuatan Draft PIB" <?= $worksheet['pengurusan_pib'] === 'Pembuatan Draft PIB' ? 'selected' : '' ?>>Pembuatan Draft PIB</option>
                    <option value="Draft PIB Sendiri" <?= $worksheet['pengurusan_pib'] === 'Draft PIB Sendiri' ? 'selected' : '' ?>>Draft PIB Sendiri</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Nomor Pengajuan PIB</label>
                <input type="text" name="no_aju" class="form-control" value="<?= esc($worksheet['no_aju']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Pengajuan PIB</label>
                <input type="date" name="tgl_aju" class="form-control" value="<?= esc($worksheet['tgl_aju']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Nomor Pendaftaran PIB</label>
                <input type="text" name="pib_nopen" class="form-control" value="<?= esc($worksheet['pib_nopen']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Pendaftaran PIB</label>
                <input type="date" name="tgl_nopen" class="form-control" value="<?= esc($worksheet['tgl_nopen']) ?>">
            </div>
        
        </div>

        <!-- ================= INFORMASI PENJALURAN ================= -->
        <br>
        <h5 class="mb-3 text-primary fw-bold border-bottom pb-2">Informasi Penjaluran</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                    <label>Penjaluran PIB</label>
                    <select name="penjaluran" id="penjaluran" class="form-select">
                        <option value="">-- Pilih Penjaluran PIB --</option>
                        <option value="SPPB" <?= $worksheet['penjaluran'] === 'SPPB' ? 'selected' : '' ?>>SPPB</option>
                        <option value="SPJM" <?= $worksheet['penjaluran'] === 'SPJM' ? 'selected' : '' ?>>SPJM</option>
                    </select>
                </div>
            

                <div class="col-md-6 mb-3" id="tgl-spjm-section">
                    <div class="col-md-6 mb-3">
                        <label>Tanggal SPJM</label>
                        <input type="date" name="tgl_spjm" class="form-control" value="<?= esc($worksheet['tgl_spjm']) ?>">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label>Tanggal SPPB</label>
                    <input type="date" name="tgl_sppb" class="form-control" value="<?= esc($worksheet['tgl_sppb']) ?>">
                </div>
        </div>

        <!-- ================= INFORMASI PENGANGKUTAN ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Pengangkutan</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Shipping Line</label>
                <input type="text" name="shipping_line" class="form-control" value="<?= esc($worksheet['shipping_line']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Port Of Loading (POL)</label>
                <input type="text" name="pol" class="form-control" value="<?= esc($worksheet['pol']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Nama Vessel</label>
                <input type="text" name="vessel" class="form-control" value="<?= esc($worksheet['vessel']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>No Voyage</label>
                <input type="text" name="no_voyage" class="form-control" value="<?= esc($worksheet['no_voyage']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>ETA</label>
                <input type="date" name="eta" class="form-control" value="<?= esc($worksheet['eta']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Lokasi Sandar</label>
                <input type="text" name="terminal" class="form-control" value="<?= esc($worksheet['terminal']) ?>">
            </div>
        </div>

        <!-- ================= INFORMASI DOKUMEN ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Dokumen</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>BL</label>
                <input type="text" name="bl" class="form-control" value="<?= esc($worksheet['bl']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal BL</label>
                <input type="date" name="tgl_bl" class="form-control" value="<?= esc($worksheet['tgl_bl']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Master BL</label>
                <input type="text" name="master_bl" class="form-control" value="<?= esc($worksheet['master_bl']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Master</label>
                <input type="date" name="tgl_master" class="form-control" value="<?= esc($worksheet['tgl_master']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>No Invoice</label>
                <input type="text" name="no_invoice" class="form-control" value="<?= esc($worksheet['no_invoice']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Tanggal Invoice</label>
                <input type="date" name="tgl_invoice" class="form-control" value="<?= esc($worksheet['tgl_invoice']) ?>">
            </div>

        </div>


        <!-- ================= INFORMASI BARANG ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Barang</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Commodity Barang</label>
                <input type="text" name="commodity" class="form-control" value="<?= esc($worksheet['commodity']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Party</label>
                <input type="text" name="party" class="form-control" value="<?= esc($worksheet['party']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Quantity</label>
                <input type="text" name="qty" class="form-control" value="<?= esc($worksheet['qty']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Kemasan</label>
                <input type="text" name="kemasan" class="form-control" value="<?= esc($worksheet['kemasan']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Gross Weight</label>
                <input type="text" name="gross" class="form-control" value="<?= esc($worksheet['gross']) ?>">
            </div>

            <div class="col-md-6 mb-3">
                <label>Net Weight</label>
                <input type="text" name="net" class="form-control" value="<?= esc($worksheet['net']) ?>">
            </div>
        </div>

        <!-- ================= JENIS CONTAINER ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Container</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Jenis Container</label>
                <select name="jenis_con" id="jenis_con" class="form-select">
                    <option value="">-- Pilih Jenis Container --</option>
                    <option value="FCL" <?= $worksheet['jenis_con'] == 'FCL' ? 'selected' : '' ?>>FCL</option>
                    <option value="LCL" <?= $worksheet['jenis_con'] == 'LCL' ? 'selected' : '' ?>>LCL</option>
                </select>
            </div>
        </div>

        <!-- ================= INFORMASI CONTAINER ================= -->
        <br>
        <div id="container-info-section">
            <button type="button" class="btn btn-sm btn-success mb-2" id="addRow">+ Tambah Container</button>

            <div class="table-responsive">
                <table class="table table-bordered" id="containerTable">
                    <thead>
                        <tr class="table-secondary">
                            <th>No.</th>
                            <th>No Container</th>
                            <th>Ukuran</th>
                            <th>Tipe</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        if (!empty($containers)) : 
                            foreach ($containers as $c) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><input type="text" name="no_container[]" class="form-control" value="<?= esc($c['no_container']) ?>"></td>
                                    <td>
                                        <select name="ukuran[]" class="form-control">
                                            <option value="">-- Pilih Ukuran --</option>
                                            <option value="20" <?= $c['ukuran'] == '20' ? 'selected' : '' ?>>20</option>
                                            <option value="40" <?= $c['ukuran'] == '40' ? 'selected' : '' ?>>40</option>
                                            <option value="45" <?= $c['ukuran'] == '45' ? 'selected' : '' ?>>45</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="tipe[]" class="form-control">
                                            <option value="">-- Pilih Tipe --</option>
                                            <option value="DRY" <?= $c['tipe'] == 'DRY' ? 'selected' : '' ?>>DRY</option>
                                            <option value="REEFER" <?= $c['tipe'] == 'REEFER' ? 'selected' : '' ?>>REEFER</option>
                                            <option value="ISO TANK" <?= $c['tipe'] == 'ISO TANK' ? 'selected' : '' ?>>ISO TANK</option>
                                        </select>
                                    </td>
                                    <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td>1</td>
                                <td><input type="text" name="no_container[]" class="form-control"></td>
                                <td>
                                    <select name="ukuran[]" class="form-control">
                                        <option value="">-- Pilih Ukuran --</option>
                                        <option value="20">20</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="tipe[]" class="form-control">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="DRY">DRY</option>
                                        <option value="REEFER">REEFER</option>
                                        <option value="ISO TANK">ISO TANK</option>
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= Informasi DO ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Delivery Order</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Pengurusan Delivery Order</label>
                <select name="pengurusan_do" id="pengurusan_do" class="form-select">
                    <option value="">-- Pilih Jenis Pengurusan Delivery Order --</option>
                    <option value="Pengambilan Delivery Order" <?= $worksheet['pengurusan_do'] === 'Pengambilan Delivery Order' ? 'selected' : '' ?>>Pengambilan Delivery Order</option>
                    <option value="Delivery Order Sendiri" <?= $worksheet['pengurusan_do'] === 'Delivery Order Sendiri' ? 'selected' : '' ?>>Delivery Order Sendiri</option>
                </select>
            </div>
        </div>

        <!-- ================= Tabel DO ================= -->
        <div id="do-info-section" class="mt-3">
            <button type="button" id="addDoRow" class="btn btn-sm btn-success mb-2">+ Tambah Delivery Order</button>
            <div class="table-responsive">
                <table class="table table-bordered" id="doTable">
                    <thead>
                        <tr class="table-secondary text-center">
                            <th>No</th>
                            <th>Tipe Delivery Order</th>
                            <th>Nama Pengambil Delivery Order</th>
                            <th>Tanggal Mati</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dos)) : ?>
                            <?php foreach ($dos as $index => $d) : ?>
                                <tr>
                                    <td class="text-center nomor"><?= $index + 1 ?></td>
                                    <td>
                                        <select name="tipe_do[]" class="form-select">
                                            <option value="">-- Pilih Tipe Delivery Order --</option>
                                            <option value="Delivery Order" <?= $d['tipe_do'] === 'Delivery Order' ? 'selected' : '' ?>>Delivery Order</option>
                                            <option value="Pengantar Delivery Order" <?= $d['tipe_do'] === 'Pengantar Delivery Order' ? 'selected' : '' ?>>Pengantar Delivery Order</option>
                                            <option value="Perpanjangan Delivery Order" <?= $d['tipe_do'] === 'Perpanjangan Delivery Order' ? 'selected' : '' ?>>Perpanjangan Delivery Order</option>
                                        </select>
                                    </td>
                                    <td><input type="text" name="pengambil_do[]" value="<?= esc($d['pengambil_do']) ?>" class="form-control" placeholder="Nama Pengambil DO"></td>
                                    <td><input type="date" name="tgl_mati_do[]" value="<?= esc($d['tgl_mati_do']) ?>" class="form-control"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm removeDoRow">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ================= Informasi Trucking ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Trucking</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Jenis Trucking</label>
                <select name="jenis_trucking" id="jenis_trucking" class="form-select">
                    <option value="">-- Pilih Jenis Trucking --</option>
                    <option value="Pengurusan Trucking" <?= $worksheet['jenis_trucking'] === 'Pengurusan Trucking' ? 'selected' : '' ?>>Pengurusan Trucking</option>
                    <option value="Trucking Sendiri" <?= $worksheet['jenis_trucking'] === 'Trucking Sendiri' ? 'selected' : '' ?>>Trucking Sendiri</option>
                </select>
            </div>
        </div>

        <!-- ================= Tabel Trucking ================= -->
        <div id="trucking-info-section" class="mt-3">
            <button type="button" id="addTruckingRow" class="btn btn-sm btn-success mb-2">+ Tambah Trucking</button>

            <table class="table table-bordered align-middle" id="truckingTable">
                <thead>
                    <tr class="table-secondary">
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>No. Mobil</th>
                        <th>Tipe Mobil</th>
                        <th>Nama Supir</th>
                        <th style="width: 250px;">Alamat Pengiriman</th>
                        <th>Telp Supir</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($truckings)) : ?>
                        <?php $no = 1; foreach ($truckings as $t) : ?>
                            <tr>
                                <td class="text-center nomor"><?= $no++; ?></td>
                                <td><input type="text" name="no_mobil[]" value="<?= esc($t['no_mobil']) ?>" class="form-control"></td>
                                <td><input type="text" name="tipe_mobil[]" value="<?= esc($t['tipe_mobil']) ?>" class="form-control"></td>
                                <td><input type="text" name="nama_supir[]" value="<?= esc($t['nama_supir']) ?>" class="form-control"></td>
                                <td>
                                    <textarea name="alamat[]" rows="2" class="form-control" placeholder="Masukkan alamat lengkap..."><?= esc($t['alamat']) ?></textarea>
                                </td>
                                <td><input type="text" name="telp_supir[]" value="<?= esc($t['telp_supir']) ?>" class="form-control"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeTruckingRow">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <!-- ================= Informasi Lartas ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Lartas</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Pengurusan Lartas</label>
                <select name="pengurusan_lartas" id="pengurusan_lartas" class="form-select">
                    <option value="">-- Pilih Pengurusan Lartas --</option>
                    <option value="Pembuatan Lartas" <?= $worksheet['pengurusan_lartas'] === 'Pembuatan Lartas' ? 'selected' : '' ?>>Pembuatan Lartas</option>
                    <option value="Lartas Sendiri" <?= $worksheet['pengurusan_lartas'] === 'Lartas Sendiri' ? 'selected' : '' ?>>Lartas Sendiri</option>
                </select>
            </div>
        </div>

        <!-- ================= Tabel Lartas ================= -->
        <div id="lartas-info-section" class="mt-3">
            <button type="button" id="addLartasRow" class="btn btn-sm btn-success mb-2">+ Tambah Lartas</button>

            <table class="table table-bordered" id="lartasTable">
                <thead>
                    <tr class="table-secondary">
                        <th style="width: 50px;">No</th>
                        <th>Nama Lartas</th>
                        <th>No. Lartas</th>
                        <th>Tanggal Lartas</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($lartass)) : ?>
                        <?php $no = 1; foreach ($lartass as $l) : ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td><input type="text" name="nama_lartas[]" value="<?= esc($l['nama_lartas']) ?>" class="form-control"></td>
                                <td><input type="text" name="no_lartas[]" value="<?= esc($l['no_lartas']) ?>" class="form-control"></td>
                                <td><input type="date" name="tgl_lartas[]" value="<?= esc($l['tgl_lartas']) ?>" class="form-control"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeLartasRow">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <!-- ================= Informasi Asuransi ================= -->
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Asuransi</h5>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Pengurusan Asuransi</label>
                <select name="asuransi" class="form-select">
                    <option value="">-- Pilih Pengurusan Asuransi --</option>
                    <option value="Pembuatan Asuransi" <?= $worksheet['asuransi'] === 'Pembuatan Asuransi' ? 'selected' : '' ?>>Pembuatan Asuransi</option>
                    <option value="Asuransi Sendiri" <?= $worksheet['asuransi'] === 'Asuransi Sendiri' ? 'selected' : '' ?>>Asuransi Sendiri</option>
                    <option value="CIF" <?= $worksheet['asuransi'] === 'CIF' ? 'selected' : '' ?>>CIF</option>
                    <option value="CIP" <?= $worksheet['asuransi'] === 'CIP' ? 'selected' : '' ?>>CIP</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>TOP</label>
                <select name="top" class="form-select">
                    <option value="">-- Pilih TOP --</option>
                    <option value="PREPAID" <?= $worksheet['top'] === 'PREPAID' ? 'selected' : '' ?>>PREPAID</option>
                    <option value="COLLECT" <?= $worksheet['top'] === 'COLLECT' ? 'selected' : '' ?>>COLLECT</option>
                </select>
            </div>
        </div>

        <!-- ================= INFORMASI TAMBAHAN ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Tambahan</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Jenis Tambahan</label>
                <select name="jenis_tambahan" id="jenis_tambahan" class="form-select">
                    <option value="">-- Pilih Jenis Tambahan --</option>
                    <option value="Pengurusan Tambahan" <?= $worksheet['jenis_tambahan'] === 'Pengurusan Tambahan' ? 'selected' : '' ?>>Pengurusan Tambahan</option>
                    <option value="Tidak Ada Tambahan" <?= $worksheet['jenis_tambahan'] === 'Tidak Ada Tambahan' ? 'selected' : '' ?>>Tidak Ada Tambahan</option>
                </select>
            </div>
        </div>

        <!-- ================= Tabel Informasi Tambahan ================= -->
        <div id="tambahan-info-section" class="mt-3">
            <button type="button" id="addTambahanRow" class="btn btn-sm btn-success mb-2">+ Tambah Pengurusan</button>

            <table class="table table-bordered" id="tambahanTable">
                <thead>
                    <tr class="table-secondary">
                        <th style="width: 50px;">No</th>
                        <th>Nama Pengurusan</th>
                        <th>Tanggal Pengurusan</th>
                        <th style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($informasitambahans)) : ?>
                        <?php $no = 1; foreach ($informasitambahans as $info) : ?>
                            <tr>
                                <td class="text-center nomor"><?= $no++; ?></td>
                                <td><input type="text" name="nama_pengurusan[]" value="<?= esc($info['nama_pengurusan']) ?>" class="form-control"></td>
                                <td><input type="date" name="tgl_pengurusan[]" value="<?= esc($info['tgl_pengurusan']) ?>" class="form-control"></td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeTambahanRow">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


        <!-- ================= CATATAN ================= -->
        <br>
        <h5 class="mt-4 mb-3 text-primary fw-bold border-bottom pb-2">Informasi Catatan</h5>
        <div class="row">                    
            <div class="col-md-12 mb-3">
                <label>Berita Acara</label>
                <textarea name="berita_acara" class="form-control" rows="3"><?= esc($worksheet['berita_acara']) ?></textarea>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-danger" id="checkDataBtn" data-id="<?= $worksheet['id'] ?>">Cek Data</button>
            <a href="<?= base_url('worksheet?type=import') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<!-- ================= SCRIPT ================= -->
<script>
    // =============== Penjaluran =======================
    const penjaluranSelect = document.getElementById('penjaluran');
    const tglSpjmSection = document.getElementById('tgl-spjm-section');

    function toggleTglSpjm() {
        if (penjaluranSelect.value === 'SPJM') {
            tglSpjmSection.style.display = 'flex'; // tampilkan
        } else {
            tglSpjmSection.style.display = 'none'; // sembunyikan
            document.querySelector('input[name="tgl_spjm"]').value = ''; // kosongkan nilai
        }
    }

    penjaluranSelect.addEventListener('change', toggleTglSpjm);
    window.addEventListener('DOMContentLoaded', toggleTglSpjm);

    // =============== Pengurusan Container =======================
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.getElementById('containerTable').getElementsByTagName('tbody')[0];
        const jenisSelect = document.getElementById('jenis_con');
        const containerSection = document.getElementById('container-info-section');

        function updateRowNumbers() {
            const rows = table.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        // Fungsi logika 
        function toggleContainerSection() {
            const jenis = jenisSelect.value;
            if (jenis === 'FCL') {
                containerSection.style.display = 'block';
            } else if (jenis === 'LCL') {
                containerSection.style.display = 'none';
            } else {
                containerSection.style.display = 'none';
            }
        }

        // Event listener untuk dropdown jenis
        jenisSelect.addEventListener('change', toggleContainerSection);

        // Tambah baris container
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.querySelector('#containerTable tbody');
            let newRow;

            // Jika masih ada baris, clone baris pertama
            if (tbody.rows.length > 0) {
                newRow = tbody.rows[0].cloneNode(true);
                newRow.querySelectorAll('input, select').forEach(el => el.value = '');
            } 
            // Jika semua baris sudah dihapus, buat baris baru dari template manual
            else {
                newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td></td>
                    <td><input type="text" name="no_container[]" class="form-control"></td>
                    <td>
                        <select name="ukuran[]" class="form-control">
                            <option value="">-- Pilih Ukuran --</option>
                            <option value="20">20</option>
                            <option value="40">40</option>
                            <option value="45">45</option>
                        </select>
                    </td>
                    <td>
                        <select name="tipe[]" class="form-control">
                            <option value="">-- Pilih Tipe --</option>
                            <option value="DRY">DRY</option>
                            <option value="REEFER">REEFER</option>
                            <option value="ISO TANK">ISO TANK</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bi bi-trash"></i></button></td>
                `;
            }

            tbody.appendChild(newRow);
            updateRowNumbers();
        });

        // Hapus baris
        table.addEventListener('click', function(e) {
            if (e.target.closest('.removeRow')) {
                e.target.closest('tr').remove();
                updateRowNumbers();
            }
        });

        // Jalankan saat halaman pertama kali dimuat
        updateRowNumbers();
        toggleContainerSection();
    });



    // ==================== Pengurusan DO ==========================
    // Fungsi Update Nomor
    function updateDoRowNumbers() {
        document.querySelectorAll('#doTable tbody tr').forEach((tr, index) => {
            tr.querySelector('.nomor').textContent = index + 1;
        });
    }

    // Tambah Baris DO 
    document.getElementById('addDoRow').addEventListener('click', function () {
        const tbody = document.querySelector('#doTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center nomor"></td>
            <td>
                <select name="tipe_do[]" class="form-select">
                    <option value="">-- Pilih Tipe Delivery Order --</option>
                    <option value="Delivery Order">Delivery Order</option>
                    <option value="Pengantar Delivery Order">Pengantar Delivery Order</option>
                    <option value="Perpanjangan Delivery Order">Perpanjangan Delivery Order</option>
                </select>
            </td>
            <td><input type="text" name="pengambil_do[]" class="form-control" placeholder="Nama Pengambil Delivery Order"></td>
            <td><input type="date" name="tgl_mati_do[]" class="form-control"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeDoRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        updateDoRowNumbers();
        enableTooltips();
    });

    // Hapus Baris DO
    document.addEventListener('click', function (e) {
        if (e.target.closest('.removeDoRow')) {
            e.target.closest('tr').remove();
            updateDoRowNumbers();
        }
    });

    // Tampilkan / Sembunyikan Section
    const jenisDoSelect = document.getElementById('pengurusan_do');
    const doSection = document.getElementById('do-info-section');

    function toggleDoSection() {
        doSection.style.display = (jenisDoSelect.value === 'Pengambilan Delivery Order') ? 'block' : 'none';
    }

    jenisDoSelect.addEventListener('change', toggleDoSection);
    window.addEventListener('DOMContentLoaded', () => {
        toggleDoSection();
        updateDoRowNumbers();
    });

   // ==================== Pengurusan Trucking ==========================
    // Fungsi: Update nomor urut otomatis
    function updateTruckingNumbers() {
        document.querySelectorAll('#truckingTable tbody tr').forEach((tr, index) => {
            tr.querySelector('.nomor').textContent = index + 1;
        });
    }

    // Tambah baris Trucking
    document.getElementById('addTruckingRow').addEventListener('click', function() {
        const tbody = document.querySelector('#truckingTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center nomor"></td>
            <td><input type="text" name="no_mobil[]" class="form-control"></td>
            <td><input type="text" name="tipe_mobil[]" class="form-control"></td>
            <td><input type="text" name="nama_supir[]" class="form-control"></td>
            <td><textarea name="alamat[]" rows="2" class="form-control" placeholder="Masukkan alamat lengkap..."></textarea></td>
            <td><input type="text" name="telp_supir[]" class="form-control"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeTruckingRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        updateTruckingNumbers();
    });

    // Hapus baris Trucking
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeTruckingRow')) {
            e.target.closest('tr').remove();
            updateTruckingNumbers();
        }
    });

    // Tampilkan / sembunyikan section Trucking
    const jenisTruckingSelect = document.getElementById('jenis_trucking');
    const truckingSection = document.getElementById('trucking-info-section');

    function toggleTruckingSection() {
        truckingSection.style.display = (jenisTruckingSelect.value === 'Pengurusan Trucking') ? 'block' : 'none';
    }

    jenisTruckingSelect.addEventListener('change', toggleTruckingSection);
    window.addEventListener('DOMContentLoaded', function() {
        toggleTruckingSection();
        updateTruckingNumbers();
    });


    // ==================== Pengurusan Lartas ==========================
    // Fungsi: Update nomor urut otomatis
    function updateLartasNumbers() {
        document.querySelectorAll('#lartasTable tbody tr').forEach((tr, index) => {
            tr.querySelector('.nomor').textContent = index + 1;
        });
    }

    // Tambah baris Lartas
    document.getElementById('addLartasRow').addEventListener('click', function() {
        const tbody = document.querySelector('#lartasTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center nomor"></td>
            <td><input type="text" name="nama_lartas[]" class="form-control" placeholder="Masukkan nama lartas"></td>
            <td><input type="text" name="no_lartas[]" class="form-control" placeholder="Masukkan nomor lartas"></td>
            <td><input type="date" name="tgl_lartas[]" class="form-control"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeLartasRow" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus baris ini">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        updateLartasNumbers();
    });

    // Hapus baris Lartas
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeLartasRow')) {
            e.target.closest('tr').remove();
            updateLartasNumbers();
        }
    });

    // Tampilkan / sembunyikan section Lartas
    const pengurusanLartasSelect = document.getElementById('pengurusan_lartas');
    const lartasSection = document.getElementById('lartas-info-section');

    function toggleLartasSection() {
        if (pengurusanLartasSelect.value === 'Pembuatan Lartas') {
            lartasSection.style.display = 'block';
        } else {
            lartasSection.style.display = 'none';
            document.querySelector('#lartasTable tbody').innerHTML = '';
        }
    }

    pengurusanLartasSelect.addEventListener('change', toggleLartasSection);
    window.addEventListener('DOMContentLoaded', function() {
        toggleLartasSection();
        updateLartasNumbers();
    });

    // ==================== Pengurusan Tambahan ==========================

    // Fungsi: Update nomor urut otomatis
    function updateTambahanNumbers() {
        document.querySelectorAll('#tambahanTable tbody tr').forEach((tr, index) => {
            tr.querySelector('.nomor').textContent = index + 1;
        });
    }

    // Tambah baris baru
    document.getElementById('addTambahanRow').addEventListener('click', function() {
        const tbody = document.querySelector('#tambahanTable tbody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center nomor"></td>
            <td><input type="text" name="nama_pengurusan[]" class="form-control"></td>
            <td><input type="date" name="tgl_pengurusan[]" class="form-control"></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm removeTambahanRow">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        updateTambahanNumbers();
    });

    // Hapus baris tambahan
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeTambahanRow')) {
            e.target.closest('tr').remove();
            updateTambahanNumbers();
        }
    });

    // Tampilkan / sembunyikan section Informasi Tambahan
    const jenisTambahanSelect = document.getElementById('jenis_tambahan');
    const tambahanSection = document.getElementById('tambahan-info-section');

    function toggleTambahanSection() {
        if (jenisTambahanSelect.value === 'Pengurusan Tambahan') {
            tambahanSection.style.display = 'block';
        } else {
            tambahanSection.style.display = 'none';
        }
    }

    jenisTambahanSelect.addEventListener('change', toggleTambahanSection);
    window.addEventListener('DOMContentLoaded', function() {
        toggleTambahanSection();
        updateTambahanNumbers();
    })

    
    
   // =============== Tombol Cek Data (kode asli tetap + tambahan loading) ===================
    document.getElementById('checkDataBtn').addEventListener('click', function(e) {
        e.preventDefault();
        const btn = this;
        btn.blur();
        const id = <?= $worksheet['id'] ?>;
        const form = document.querySelector('form');
        const formData = new FormData(form);

        // === Tambahan: tampilkan loading "Mohon tunggu..." ===
        Swal.fire({
            title: 'Mohon tunggu...',
            text: 'Sedang memproses dan memeriksa kelengkapan data',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`<?= base_url('worksheet/import/update/') ?>${id}`, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal update data');
                return response.text();
            })
            .then(() => fetch(`<?= base_url('worksheet/checkImport/') ?>${id}`))
            .then(response => response.json())
            .then(data => {
                // Tutup loading saat data sudah diterima
                Swal.close();

                document.querySelectorAll('.is-empty').forEach(el => el.classList.remove('is-empty'));

                if (data.status === 'complete') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Sudah Lengkap!',
                        text: data.message,
                        confirmButtonColor: '#435ebe'
                    }).then(() => btn.blur());
                } else if (data.status === 'incomplete') {
                    const list = data.missing_fields.map(f => `• ${f.label}`).join('<br>');

                    Swal.fire({
                        icon: 'warning',
                        title: 'Data Belum Lengkap',
                        html: `Ada kolom yang masih kosong:<br><br>${list}`,
                        confirmButtonColor: '#435ebe',
                        allowOutsideClick: false
                    }).then(() => {
                        btn.blur();
                        setTimeout(() => {
                            data.missing_fields.forEach((f, i) => {
                                const el = document.querySelector(`input[name="${f.name}"], select[name="${f.name}"], textarea[name="${f.name}"]`);
                                if (el) {
                                    el.classList.add('is-empty');
                                    if (i === 0) {
                                        el.focus({ preventScroll: false });
                                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    }
                                }
                            });
                            setTimeout(() => {
                                document.querySelectorAll('.is-empty').forEach(el => el.classList.remove('is-empty'));
                            }, 3000);
                        }, 300);
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan.',
                        confirmButtonColor: '#435ebe'
                    }).then(() => btn.blur());
                }
            })
            .catch(err => {
                console.error(err);
                // Tutup loading jika terjadi error
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat update atau cek data.',
                    confirmButtonColor: '#435ebe'
                }).then(() => btn.blur());
            });
    });

</script>


<style>
    .is-empty {
        border: 2px solid red !important;
        background-color: #ffe6e6 !important;
        animation: shake 0.2s ease-in-out 0s 2;
    }

    @keyframes shake {

        0%,
        100% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        75% {
            transform: translateX(5px);
        }
    }
</style>


<?= $this->endSection() ?>