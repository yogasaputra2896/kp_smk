<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0px;
            color: #000;
        }

        .header-info {
            text-align: left;
            font-weight: bold;
            margin-bottom: 10px;
            line-height: 1.5;
            font-size: 15px;
            margin-left: auto;
            width: fit-content;
            margin-top: -30px;
        }

        h1 {
            text-align: center;
            margin: 0 0 15px 0;
            padding: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            padding: 3px 0;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 11px;
            background: #e0e0e0;
            padding-left: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            font-size: 11px;
        }

        td:first-child {
            font-weight: bold;
            background: #f5f5f5;
            width: 25%;
        }

        td:nth-child(3) {
            font-weight: bold;
            background: #f5f5f5;
            width: 25%;
        }

        .info-table td {
            width: 25%;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <div class="header-info">
        AJU : <?= $ws['no_aju'] ?><br>
        PO : <?= $ws['no_po'] ?>
    </div>

    <h1>WORKSHEET IMPORT</h1>

    <!-- Informasi Umum -->
    <div class="section-title">Informasi Umum</div>
    <table class="info-table">
        <tr>
            <td>No Worksheet</td>
            <td><?= $ws['no_ws'] ?></td>
            <td>Tgl Worksheet</td>
            <td><?= $ws['created_at'] ?></td>
        </tr>
        <tr>
            <td>Shipper</td>
            <td colspan="3"><?= $ws['shipper'] ?></td>
        </tr>
        <tr>
            <td>Consignee</td>
            <td colspan="3"><?= $ws['consignee'] ?></td>
        </tr>
        <tr>
            <td>Notify Party</td>
            <td colspan="3"><?= $ws['notify_party'] ?></td>
        </tr>
        <tr>
            <td>IO Number</td>
            <td colspan="3"><?= $ws['io_number'] ?></td>
        </tr>
    </table>

    <!-- Informasi PIB -->
    <div class="section-title">Informasi PIB</div>
    <table class="info-table">
        <tr>
            <td>Pengurusan PIB</td>
            <td colspan="3"><?= $ws['pengurusan_pib'] ?></td>
        </tr>
        <tr>
            <td>No Aju</td>
            <td><?= $ws['no_aju'] ?></td>
            <td>Tanggal Aju</td>
            <td><?= $ws['tgl_aju'] ?></td>
        </tr>
        <tr>
            <td>PIB No Pen</td>
            <td><?= $ws['pib_nopen'] ?></td>
            <td>Tanggal Nopen</td>
            <td><?= $ws['tgl_nopen'] ?></td>
        </tr>
    </table>

    <!-- Informasi Penjaluran -->
    <div class="section-title">Informasi Penjaluran</div>
    <table class="info-table">
        <tr>
            <td>Penjaluran</td>
            <td colspan="3"><?= $ws['penjaluran'] ?></td>
        </tr>
        <tr>
            <?php if ($ws['penjaluran'] != 'SPPB'): ?>
                <td>Tanggal SPJM</td>
                <td><?= $ws['tgl_spjm'] ?></td>
                <td>Tanggal SPPB</td>
                <td><?= $ws['tgl_sppb'] ?></td>
            <?php else: ?>
                <td>Tanggal SPPB</td>
                <td colspan="3"><?= $ws['tgl_sppb'] ?></td>
            <?php endif; ?>
        </tr>
    </table>

    <!-- Informasi Pengangkutan -->
    <div class="section-title">Informasi Pengangkutan</div>
    <table class="info-table">
        <tr>
            <td>Shipping Line</td>
            <td><?= $ws['shipping_line'] ?></td>
            <td>POL</td>
            <td><?= $ws['pol'] ?></td>
        </tr>
        <tr>
            <td>Vessel</td>
            <td><?= $ws['vessel'] ?></td>
            <td>No Voyage</td>
            <td><?= $ws['no_voyage'] ?></td>
        </tr>
        <tr>
            <td>Lokasi Sandar</td>
            <td><?= $ws['terminal'] ?></td>
            <td>ETA</td>
            <td><?= $ws['eta'] ?></td>
        </tr>
    </table>

    <!-- Informasi Dokumen -->
    <div class="section-title">Informasi Dokumen</div>
    <table class="info-table">
        <tr>
            <td>BL</td>
            <td><?= $ws['bl'] ?></td>
            <td>Tanggal BL</td>
            <td><?= $ws['tgl_bl'] ?></td>
        </tr>
        <tr>
            <td>Master BL</td>
            <td><?= $ws['master_bl'] ?></td>
            <td>Tanggal MBL</td>
            <td><?= $ws['tgl_master'] ?></td>
        </tr>
        <tr>
            <td>No Invoice</td>
            <td><?= $ws['no_invoice'] ?></td>
            <td>Tanggal Invoice</td>
            <td><?= $ws['tgl_invoice'] ?></td>
        </tr>
    </table>

    <!-- Informasi Barang -->
    <div class="section-title">Informasi Barang</div>
    <table class="info-table">
        <tr>
            <td>Commodity</td>
            <td colspan="3"><?= $ws['commodity'] ?></td>
        </tr>
        <tr>
            <td>Party</td>
            <td><?= $ws['party'] ?></td>
            <td>Jenis Container</td>
            <td><?= $ws['jenis_con'] ?></td>
        </tr>
        <tr>
            <td>Quantity</td>
            <td><?= $ws['qty'] ?></td>
            <td>Kemasan</td>
            <td><?= $ws['kemasan'] ?></td>
        </tr>
        <tr>
            <td>Net</td>
            <td><?= $ws['net'] ?></td>
            <td>Gross</td>
            <td><?= $ws['gross'] ?></td>
        </tr>
    </table>

    <!-- Informasi Lainnya -->
    <div class="section-title">Informasi Lainnya</div>
    <table class="info-table">
        <tr>
            <?php if ($ws['dok_ori'] != 'Belum Ada'): ?>
                <td>Dokumen Original</td>
                <td><?= $ws['dok_ori'] ?></td>
                <td>Tanggal Dok Ori</td>
                <td><?= $ws['tgl_ori'] ?></td>
            <?php else: ?>
                <td>Dokumen Original</td>
                <td colspan="3"><?= $ws['dok_ori'] ?></td>
            <?php endif; ?>
        </tr>
        <tr>
            <td>Pengurusan DO</td>
            <td><?= $ws['pengurusan_do'] ?></td>
            <td>Pengurusan Lartas</td>
            <td><?= $ws['pengurusan_lartas'] ?></td>
        </tr>
        <tr>
            <td>Asuransi</td>
            <td><?= $ws['asuransi'] ?></td>
            <td>TOP</td>
            <td><?= $ws['top'] ?></td>
        </tr>
        <tr>
            <td>Jenis Trucking</td>
            <td><?= $ws['jenis_trucking'] ?></td>
            <td>Jenis Fasilitas</td>
            <td><?= $ws['jenis_fasilitas'] ?></td>
        </tr>
        <tr>
            <td>Jenis Tambahan</td>
            <td colspan="3"><?= $ws['jenis_tambahan'] ?></td>
        </tr>
        <tr>
            <td>Berita Acara</td>
            <td colspan="3"><?= $ws['berita_acara'] ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ========================= PAGE 2 ========================= -->
    <h1>WORKSHEET IMPORT – LEMBAR LANJUT</h1>

    <!-- Container -->
    <?php if ($ws['jenis_con'] != 'LCL'): ?>
        <div class="section-title">Container</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">No Container</td>
                <td style="font-weight: bold; background: #f5f5f5;">Ukuran</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tipe</td>
            </tr>
            <?php foreach ($container as $c): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $c['no_container'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $c['ukuran'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $c['tipe'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Trucking -->
    <?php if ($ws['jenis_trucking'] != 'Trucking Sendiri'): ?>
        <div class="section-title">Trucking</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">No Mobil</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tipe Mobil</td>
                <td style="font-weight: bold; background: #f5f5f5;">Supir</td>
                <td style="font-weight: bold; background: #f5f5f5;">Telp</td>
            </tr>
            <?php foreach ($trucking as $t): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $t['no_mobil'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $t['tipe_mobil'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $t['nama_supir'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $t['telp_supir'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Delivery Order -->
    <?php if ($ws['pengurusan_do'] != 'Delivery Order Sendiri'): ?>
        <div class="section-title">Delivery Order</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">Tipe DO</td>
                <td style="font-weight: bold; background: #f5f5f5;">Pengambil</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tanggal Mati DO</td>
            </tr>
            <?php foreach ($do as $d): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $d['tipe_do'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $d['pengambil_do'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $d['tgl_mati_do'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Lartas -->
    <?php if ($ws['pengurusan_lartas'] != 'Lartas Sendiri'): ?>
        <div class="section-title">Lartas</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">Nama Lartas</td>
                <td style="font-weight: bold; background: #f5f5f5;">No Lartas</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tanggal</td>
            </tr>
            <?php foreach ($lartas as $l): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $l['nama_lartas'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $l['no_lartas'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $l['tgl_lartas'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Fasilitas -->
    <?php if ($ws['jenis_fasilitas'] != 'Tidak Ada Fasilitas'): ?>
        <div class="section-title">Fasilitas</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">Tipe</td>
                <td style="font-weight: bold; background: #f5f5f5;">Nama</td>
                <td style="font-weight: bold; background: #f5f5f5;">No</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tanggal</td>
            </tr>
            <?php foreach ($fasilitas as $f): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $f['tipe_fasilitas'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $f['nama_fasilitas'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $f['no_fasilitas'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $f['tgl_fasilitas'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Informasi Tambahan -->
    <?php if ($ws['jenis_tambahan'] != 'Tidak Ada Tambahan'): ?>
        <div class="section-title">Informasi Tambahan</div>
        <table>
            <tr>
                <td style="font-weight: bold; background: #f5f5f5;">Nama Pengurusan</td>
                <td style="font-weight: bold; background: #f5f5f5;">Tanggal</td>
            </tr>
            <?php foreach ($tambahan as $t): ?>
                <tr>
                    <td style="font-weight: normal; background: #fff;"><?= $t['nama_pengurusan'] ?></td>
                    <td style="font-weight: normal; background: #fff;"><?= $t['tgl_pengurusan'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</body>


</html>