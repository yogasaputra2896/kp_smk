<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Dompdf\Dompdf;
use Dompdf\Options;

class WorksheetExportService
{
    public function exportExcel($master, $containers, $fasilitas, $lartas, $do, $trucking, $tambahan)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        // ====================== WARNA HIJAU ======================
        $HEADER_COLOR = "2E7D32";

        // ====================== MASTER HEADER ======================
        $headers = [
            'No',
            'No WorkSheet',
            'No Aju',
            'Pengurusan PEB',
            'PEB Nopen',
            'Tgl Aju',
            'Tgl Nopen',
            'No PO',
            'IO Number',
            'Penjaluran',
            'Tgl NPE',
            'Tgl SPJM',
            'Shipper',
            'Consignee',
            'Notify Party',
            'Vessel',
            'Voyage',
            'POL',
            'POD',
            'Shipping Line',
            'Commodity',
            'Party',
            'Jenis Con',
            'Qty',
            'Kemasan',
            'Net',
            'Gross',
            'BL',
            'Tgl BL',
            'Master BL',
            'Tgl Master BL',
            'No Invoice',
            'Tgl Invoice',
            'ETD',
            'Closing',
            'Stuffing',
            'Depo',
            'Terminal',
            'Dok Ori',
            'Tgl Ori',
            'Pengurusan DO',
            'Asuransi',
            'Jenis Trucking',
            'Jenis Fasilitas',
            'Jenis Tambahan',
            'Pengurusan Lartas',
            'TOP',
            'Berita Acara',
            'Status',
            'Created At',
            'Updated At',
        ];

        // ====================== MASTER SHEET ======================
        $this->createStyledSheet(
            $spreadsheet,
            "Laporan Worksheet Export",
            $headers,
            $master,
            function ($r) {
                return [
                    $r['no_ws'],
                    $r['no_aju'],
                    $r['pengurusan_peb'],
                    $r['peb_nopen'],
                    $r['tgl_aju'],
                    $r['tgl_nopen'],
                    $r['no_po'],
                    $r['io_number'],
                    $r['penjaluran'],
                    $r['tgl_npe'],
                    $r['tgl_spjm'],
                    $r['shipper'],
                    $r['consignee'],
                    $r['notify_party'],
                    $r['vessel'],
                    $r['no_voyage'],
                    $r['pol'],
                    $r['pod'],
                    $r['shipping_line'],
                    $r['commodity'],
                    $r['party'],
                    $r['jenis_con'],
                    $r['qty'],
                    $r['kemasan'],
                    $r['net'],
                    $r['gross'],
                    $r['bl'],
                    $r['tgl_bl'],
                    $r['master_bl'],
                    $r['tgl_master'],
                    $r['no_invoice'],
                    $r['tgl_invoice'],
                    $r['etd'],
                    $r['closing'],
                    $r['stuffing'],
                    $r['depo'],
                    $r['terminal'],
                    $r['dok_ori'],
                    $r['tgl_ori'],
                    $r['pengurusan_do'],
                    $r['asuransi'],
                    $r['jenis_trucking'],
                    $r['jenis_fasilitas'],
                    $r['jenis_tambahan'],
                    $r['pengurusan_lartas'],
                    $r['top'],
                    $r['berita_acara'],
                    $r['status'],
                    $r['created_at'],
                    $r['updated_at'],
                ];
            },
            $HEADER_COLOR
        );

        // ====================== CHILD TABLES ======================

        $this->createStyledSheet(
            $spreadsheet,
            "Container",
            ['No','No WorkSheet','No Container','Ukuran','Tipe','Created At'],
            $containers,
            fn($r) => [$r['no_ws'], $r['no_container'], $r['ukuran'], $r['tipe'], $r['created_at']],
            $HEADER_COLOR
        );

        $this->createStyledSheet(
            $spreadsheet,
            "Fasilitas",
            ['No','No WorkSheet','Tipe Fasilitas','Nama Fasilitas','Tgl Fasilitas','No Fasilitas','Created At'],
            $fasilitas,
            fn($r) => [$r['no_ws'], $r['tipe_fasilitas'], $r['nama_fasilitas'], $r['tgl_fasilitas'], $r['no_fasilitas'], $r['created_at']],
            $HEADER_COLOR
        );

        $this->createStyledSheet(
            $spreadsheet,
            "Lartas",
            ['No','No WorkSheet','Nama Lartas','No Lartas','Tgl Lartas','Created At'],
            $lartas,
            fn($r) => [$r['no_ws'], $r['nama_lartas'], $r['no_lartas'], $r['tgl_lartas'], $r['created_at']],
            $HEADER_COLOR
        );

        $this->createStyledSheet(
            $spreadsheet,
            "Delivery Order",
            ['No','No WorkSheet','Tipe DO','Pengambil DO','Tgl Mati DO','Created At'],
            $do,
            fn($r) => [$r['no_ws'], $r['tipe_do'], $r['pengambil_do'], $r['tgl_mati_do'], $r['created_at']],
            $HEADER_COLOR
        );

        $this->createStyledSheet(
            $spreadsheet,
            "Trucking",
            ['No','No WorkSheet','No Mobil','Tipe Mobil','Nama Supir','Alamat','Telp Supir','Created At'],
            $trucking,
            fn($r) => [$r['no_ws'], $r['no_mobil'], $r['tipe_mobil'], $r['nama_supir'], $r['alamat'], $r['telp_supir'], $r['created_at']],
            $HEADER_COLOR
        );

        $this->createStyledSheet(
            $spreadsheet,
            "Info Tambahan",
            ['No','No WorkSheet','Nama Pengurusan','Tgl Pengurusan','Created At'],
            $tambahan,
            fn($r) => [$r['no_ws'], $r['nama_pengurusan'], $r['tgl_pengurusan'], $r['created_at']],
            $HEADER_COLOR
        );

        // ====================== OUTPUT ======================
        $filename = "Laporan_Worksheet_Export_" . date('Ymd_His') . ".xlsx";

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Cache-Control: max-age=0");

        $writer = new Xlsx($spreadsheet);
        $writer->save("php://output");
        exit;
    }

    private function createStyledSheet($spreadsheet, $title, $headers, $rows, $formatter, $color)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(substr($title, 0, 25));

        $totalCols = count($headers);
        $lastCol = Coordinate::stringFromColumnIndex($totalCols);

        // ====================== JUDUL ======================
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue("A1", strtoupper($title));

        $sheet->getStyle("A1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF'.$color]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // ====================== SUBTITLE ======================
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue("A2", "Laporan Worksheet Export — Dicetak: " . date('d/m/Y H:i:s'));

        $sheet->getStyle("A2")->applyFromArray([
            'font' => ['italic' => true, 'size' => 9],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // ====================== HEADER ======================
        $rowHeader = 3;
        $colIndex = 1;

        foreach ($headers as $h) {
            $cell = Coordinate::stringFromColumnIndex($colIndex) . $rowHeader;

            $sheet->setCellValue($cell, $h);

            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF'.$color]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);

            $colIndex++;
        }

        // ====================== DATA ======================
        $rowNum = 4;
        $no = 1;

        foreach ($rows as $row) {
            $mapped = $formatter($row);

            $sheet->setCellValue("A{$rowNum}", $no++);

            $col = 2;
            foreach ($mapped as $val) {
                $cell = Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, $val);
                $col++;
            }

            $rowNum++;
        }

        // ====================== AUTO WIDTH ======================
        $highestCol = $sheet->getHighestColumn();
        $highestIndex = Coordinate::columnIndexFromString($highestCol);

        for ($i = 1; $i <= $highestIndex; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    public function exportPDF($master, $containers, $fasilitas, $lartas, $do, $trucking, $tambahan)
    {
        // ===========================
        // SETUP DOMPDF
        // ===========================
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // ===========================
        // WARNA HIJAU
        // ===========================
        $HIJAU = "#2E7D32";

        // ===========================
        // STYLE GLOBAL PDF
        // ===========================
        $html = '
        <style>
            body { font-family: sans-serif; font-size: 10px; }
            .title {
                text-align: center; 
                font-size: 16px; 
                font-weight: bold; 
                margin-bottom: 5px;
                color: white;
                padding: 10px;
                background: '.$HIJAU.';
            }
            .subtitle {
                text-align: center;
                font-size: 10px;
                font-style: italic;
                margin-bottom: 10px;
                color: #444;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                page-break-inside: auto;
            }
            th {
                background: '.$HIJAU.';
                color: white;
                padding: 5px;
                border: 1px solid #000;
                font-weight: bold;
                text-align: center;
            }
            td {
                padding: 4px;
                border: 1px solid #000;
                text-align: center;
            }
            .section-title {
                font-size: 13px;
                font-weight: bold;
                margin-top: 25px;
                margin-bottom: 5px;
                color: '.$HIJAU.';
                border-bottom: 2px solid '.$HIJAU.';
                padding-bottom: 3px;
            }
        </style>';

        // ===========================
        // MASTER HEADER
        // ===========================
        $headers = [
            'No','No WorkSheet','No Aju','Pengurusan PEB','PEB Nopen','Tgl Aju','Tgl Nopen',
            'No PO','IO Number','Penjaluran','Tgl NPE','Tgl SPJM','Shipper','Consignee',
            'Notify Party','Vessel','Voyage','POL','POD','Shipping Line','Commodity',
            'Party','Jenis Con','Qty','Kemasan','Net','Gross','BL','Tgl BL','Master BL',
            'Tgl Master BL','No Invoice','Tgl Invoice','ETD','Closing','Stuffing','Depo',
            'Terminal','Dok Ori','Tgl Ori','Pengurusan DO','Asuransi','Jenis Trucking',
            'Jenis Fasilitas','Jenis Tambahan','Pengurusan Lartas','TOP','Berita Acara',
            'Status','Created At','Updated At'
        ];

        // ===========================
        // FORMAT DATA MASTER
        // ===========================
        $formattedMaster = [];
        $no = 1;
        foreach ($master as $m) {
            $formattedMaster[] = array_merge(['No' => $no++], $m);
        }

        // ===========================
        // SPLIT MASTER PER 15 KOLOM
        // ===========================
        $chunks = array_chunk($headers, 15);
        $parts = count($chunks);
        $partIndex = 0;

        foreach ($chunks as $colSet) {

            $partIndex++;
            $isFirstPage = ($partIndex === 1);

            // ==========================================
            // HALAMAN BARU JIKA BUKAN HALAMAN PERTAMA
            // ==========================================
            if (!$isFirstPage) {
                $html .= '<div style="page-break-before: always;"></div>';
            }

            // ==========================================
            // HALAMAN 1 — JUDUL + SUBTITLE
            // ==========================================
            if ($isFirstPage) {
                $html .= '
                    <div class="title">LAPORAN WORKSHEET EXPORT</div>
                    <div class="subtitle">Dicetak: '.date("d/m/Y H:i:s").'</div>
                ';
            }

            // ==========================================
            // TABLE MASTER EXPORT (Bagian X)
            // ==========================================
            $html .= '<div class="section-title">WORKSHEET EXPORT (Bagian '.$partIndex.' dari '.$parts.')</div>';
            $html .= '<table><tr>';

            // Index kolom yang digunakan
            $colIndexes = [];
            foreach ($colSet as $h) {
                $colIndexes[] = array_search($h, $headers);
                $html .= '<th>'.$h.'</th>';
            }
            $html .= '</tr>';

            // ISI DATA
            foreach ($formattedMaster as $row) {
                $html .= '<tr>';
                foreach ($colIndexes as $idx) {
                    $key = array_keys($row)[$idx];
                    $html .= '<td>'.($row[$key] ?? '').'</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</table>';
        }

        // ====================================================================
        // CHILD TABLE FUNCTION — SELALU HALAMAN BARU
        // ====================================================================
        $child = function($title, $headers, $rows) use ($HIJAU) {

            $html = '<div style="page-break-before: always;"></div>';

            $html .= '<div class="section-title">'.$title.'</div>';
            $html .= '<table><tr>';

            foreach ($headers as $h) {
                $html .= '<th>'.$h.'</th>';
            }

            $html .= '</tr>';
            $no = 1;

            foreach ($rows as $r) {
                $html .= '<tr><td>'.$no++.'</td>';
                foreach ($r as $v) {
                    $html .= '<td>'.$v.'</td>';
                }
                $html .= '</tr>';
            }

            return $html.'</table>';
        };

        // ===========================
        // CHILD TABLES
        // ===========================
        $html .= $child("Container", 
            ['No','No WorkSheet','Container','Ukuran','Tipe','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['no_container'],$d['ukuran'],$d['tipe'],$d['created_at']], $containers)
        );

        $html .= $child("Fasilitas",
            ['No','No WorkSheet','Tipe','Nama','Tanggal','Nomor','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['tipe_fasilitas'],$d['nama_fasilitas'],$d['tgl_fasilitas'],$d['no_fasilitas'],$d['created_at']], $fasilitas)
        );

        $html .= $child("Lartas",
            ['No','No WorkSheet','Nama','Nomor','Tanggal','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['nama_lartas'],$d['no_lartas'],$d['tgl_lartas'],$d['created_at']], $lartas)
        );

        $html .= $child("Delivery Order",
            ['No','No WorkSheet','Tipe DO','Pengambil','Tgl Mati DO','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['tipe_do'],$d['pengambil_do'],$d['tgl_mati_do'],$d['created_at']], $do)
        );

        $html .= $child("Trucking",
            ['No','No WorkSheet','No Mobil','Tipe','Supir','Alamat','Telp','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['no_mobil'],$d['tipe_mobil'],$d['nama_supir'],$d['alamat'],$d['telp_supir'],$d['created_at']], $trucking)
        );

        $html .= $child("Informasi Tambahan",
            ['No','No WorkSheet','Nama Pengurusan','Tgl Pengurusan','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['nama_pengurusan'],$d['tgl_pengurusan'],$d['created_at']], $tambahan)
        );

        // ===========================
        // OUTPUT PDF
        // ===========================
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Laporan_Worksheet_Export_".date('Ymd_His').".pdf", ["Attachment" => true]);
        exit;
    }

}
