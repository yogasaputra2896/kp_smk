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


class WorksheetImportService
{
    public function exportExcel($master, $containers, $fasilitas, $lartas, $do, $trucking, $tambahan)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        // WARNA ORANGE
        $HEADER_COLOR = 'E26B0A';

        // ===================== MASTER =====================
        $headersMaster = [
            'No','No WorkSheet','Pengurusan PIB','No Aju','Tgl Aju','No PO','IO Number',
            'PIB Nopen','Tgl Nopen','Penjaluran','Tgl SPJM','Tgl SPPB','Shipper','Consignee',
            'Notify Party','Vessel','Voyage','POL','Terminal','Shipping Line','Commodity',
            'Party','Jenis Con','Qty','Kemasan','Net','Gross','BL','Tgl BL','Master BL',
            'Tgl Master BL','No Invoice','Tgl Invoice','ETA','Dok Ori','Tgl Ori','Pengurusan DO',
            'Asuransi','TOP','Jenis Trucking','Pengurusan Lartas','Jenis Tambahan','Jenis Fasilitas',
            'Berita Acara','Status','Created At','Updated At'
        ];

        $this->createStyledSheet(
            $spreadsheet,
            "Laporan Worksheet Import",
            $headersMaster,
            $master,
            function ($r) {
                return [
                    $r['no_ws'],
                    $r['pengurusan_pib'],
                    $r['no_aju'],
                    $r['tgl_aju'],
                    $r['no_po'],
                    $r['io_number'],
                    $r['pib_nopen'],
                    $r['tgl_nopen'],
                    $r['penjaluran'],
                    $r['tgl_spjm'],
                    $r['tgl_sppb'],
                    $r['shipper'],
                    $r['consignee'],
                    $r['notify_party'],
                    $r['vessel'],
                    $r['no_voyage'],
                    $r['pol'],
                    $r['terminal'],
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
                    $r['eta'],
                    $r['dok_ori'],
                    $r['tgl_ori'],
                    $r['pengurusan_do'],
                    $r['asuransi'],
                    $r['top'],
                    $r['jenis_trucking'],
                    $r['pengurusan_lartas'],
                    $r['jenis_tambahan'],
                    $r['jenis_fasilitas'],
                    $r['berita_acara'],
                    $r['status'],
                    $r['created_at'],
                    $r['updated_at']
                ];
            },
            $HEADER_COLOR
        );

        // ===================== CHILD TABLES =====================

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

        // EXPORT
        $filename = "Laporan_Worksheet_Import_" . date('Ymd_His') . ".xlsx";
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

        // =============== JUDUL ===============
        $sheet->mergeCells("A1:{$lastCol}1");
        $sheet->setCellValue("A1", strtoupper($title));

        $sheet->getStyle("A1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FFFFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF' . $color]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        $sheet->getRowDimension(1)->setRowHeight(25);

        // =============== SUBTITLE ===============
        $sheet->mergeCells("A2:{$lastCol}2");
        $sheet->setCellValue("A2", "Laporan Worksheet Import — Dicetak: " . date('d/m/Y H:i:s'));

        $sheet->getStyle("A2")->applyFromArray([
            'font' => ['italic' => true, 'size' => 9, 'color' => ['argb' => 'FF555555']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $sheet->getRowDimension(2)->setRowHeight(18);

        // =============== HEADER ===============
        $rowHeader = 3;
        $col = 1;

        foreach ($headers as $h) {
            $cell = Coordinate::stringFromColumnIndex($col) . $rowHeader;

            $sheet->setCellValue($cell, $h);

            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF' . $color]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
            ]);

            $col++;
        }

        // =============== DATA ===============
        $rowNum = 4;
        $no = 1;

        foreach ($rows as $row) {
            $mapped = $formatter($row);

            // No
            $sheet->setCellValue("A{$rowNum}", $no++);

            $col = 2;
            foreach ($mapped as $val) {
                $cell = Coordinate::stringFromColumnIndex($col) . $rowNum;
                $sheet->setCellValue($cell, $val);
                $col++;
            }

            // style row
            $sheet->getStyle("A{$rowNum}:{$lastCol}{$rowNum}")
                ->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);

            $rowNum++;
        }

        if (empty($rows)) {
            $sheet->mergeCells("A4:{$lastCol}4");
            $sheet->setCellValue("A4", "Tidak ada data tersedia");
        }

        // =============== AUTO WIDTH (NO ERROR VERSION) ===============
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);

        for ($i = 1; $i <= $highestColumnIndex; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    public function exportPDF($master, $containers, $fasilitas, $lartas, $do, $trucking, $tambahan)
    {
        // Setup Dompdf
        $opt = new Options();
        $opt->set('isHtml5ParserEnabled', true);
        $opt->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($opt);

        // Warna utama
        $ORANGE = "#E26B0A";

        // =================== GLOBAL STYLE ===================
        $html = '
        <style>
            body { font-family: sans-serif; font-size: 10px; }
            .title {
                text-align:center;font-size:16px;font-weight:bold;
                color:white;background:'.$ORANGE.';
                padding:10px;margin-bottom:5px;
            }
            .subtitle{
                text-align:center;font-size:10px;
                margin-bottom:10px;font-style:italic;color:#444;
            }
            table{
                width:100%;border-collapse:collapse;margin-bottom:20px;
            }
            th{
                border:1px solid #000;padding:5px;font-weight:bold;
                background:'.$ORANGE.';color:white;font-size:10px;
            }
            td{
                border:1px solid #000;padding:4px;text-align:center;
            }
            .section-title{
                font-size:13px;font-weight:bold;color:'.$ORANGE.';
                margin-top:20px;margin-bottom:5px;
                border-bottom:2px solid '.$ORANGE.';
            }
        </style>';

       // =================== HEADER PDF + MASTER HALAMAN 1 ===================
        $headersMaster = [
            'No','No WorkSheet','Pengurusan PIB','No Aju','Tgl Aju','No PO','IO Number',
            'PIB Nopen','Tgl Nopen','Penjaluran','Tgl SPJM','Tgl SPPB','Shipper','Consignee',
            'Notify Party','Vessel','Voyage','POL','Terminal','Shipping Line','Commodity',
            'Party','Jenis Con','Qty','Kemasan','Net','Gross','BL','Tgl BL','Master BL',
            'Tgl Master BL','No Invoice','Tgl Invoice','ETA','Dok Ori','Tgl Ori','Pengurusan DO',
            'Asuransi','TOP','Jenis Trucking','Pengurusan Lartas','Jenis Tambahan','Jenis Fasilitas',
            'Berita Acara','Status','Create_at','Update_at'
        ];

        // Tambahkan No ke data master
        $formattedMaster = [];
        $no = 1;
        foreach ($master as $m) {
            $formattedMaster[] = array_merge(['no' => $no++], $m);
        }

        // ================== SPLIT KOLOM MASTER ==================
        $chunks = array_chunk($headersMaster, 15);
        $parts = count($chunks);
        $partIndex = 0;

        foreach ($chunks as $colSet) {

            $partIndex++;
            $isFirstPage = ($partIndex === 1);

            // buat header kolom index
            $colIndexes = [];
            foreach ($colSet as $h) {
                $colIndexes[] = array_search($h, $headersMaster);
            }

            // halaman baru kecuali untuk halaman pertama
            if (!$isFirstPage) {
                $html .= '<div style="page-break-before: always;"></div>';
            }

            // ========== HALAMAN 1: JUDUL + SUBTITLE ==========
            if ($isFirstPage) {
                $html .= '
                <div class="title">LAPORAN WORKSHEET IMPORT</div>
                <div class="subtitle">Dicetak pada '.date("d/m/Y H:i:s").'</div>';
            }

            // ========== HEADER TABEL MASTER (setiap bagian) ==========
            $html .= '<div class="section-title">WORKSHEET IMPORT (Bagian '.$partIndex.' dari '.$parts.')</div>';
            $html .= '<table><tr>';

            foreach ($colSet as $h) {
                $html .= '<th>'.$h.'</th>';
            }
            $html .= '</tr>';

            // ========== ISI DATA MASTER ==============
            foreach ($formattedMaster as $row) {
                $html .= '<tr>';
                foreach ($colIndexes as $idx) {
                    $keys = array_keys($row);
                    $key = $keys[$idx] ?? "";
                    $html .= '<td>'.($row[$key] ?? '').'</td>';
                }
                $html .= '</tr>';
            }

            $html .= '</table>';
        }

        // =====================================================
        // TEMPLATE CHILD TABLE
        // =====================================================
        $renderChild = function ($title, $headers, $rows) use ($ORANGE) {

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
                foreach ($r as $val) { $html .= '<td>'.$val.'</td>'; }
                $html .= '</tr>';
            }
            return $html.'</table>';
        };

        // =====================================================
        // CHILD — MASING-MASING HALAMAN BARU
        // =====================================================
        $html .= $renderChild(
            "CONTAINER",
            ['No','No WorkSheet','Container','Ukuran','Tipe','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['no_container'],$d['ukuran'],$d['tipe'],$d['created_at']], $containers)
        );

        $html .= $renderChild(
            "FASILITAS",
            ['No','No WorkSheet','Tipe','Nama','Tanggal','Nomor','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['tipe_fasilitas'],$d['nama_fasilitas'],$d['tgl_fasilitas'],$d['no_fasilitas'],$d['created_at']], $fasilitas)
        );

        $html .= $renderChild(
            "LARTAS",
            ['No','No WorkSheet','Nama','Nomor','Tanggal','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['nama_lartas'],$d['no_lartas'],$d['tgl_lartas'],$d['created_at']], $lartas)
        );

        $html .= $renderChild(
            "DELIVERY ORDER",
            ['No','No WorkSheet','Tipe DO','Pengambil','Tgl Mati DO','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['tipe_do'],$d['pengambil_do'],$d['tgl_mati_do'],$d['created_at']], $do)
        );

        $html .= $renderChild(
            "TRUCKING",
            ['No','No WorkSheet','No Mobil','Tipe','Supir','Alamat','Telp','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['no_mobil'],$d['tipe_mobil'],$d['nama_supir'],$d['alamat'],$d['telp_supir'],$d['created_at']], $trucking)
        );

        $html .= $renderChild(
            "INFORMASI TAMBAHAN",
            ['No','No WorkSheet','Nama Pengurusan','Tanggal','Created At'],
            array_map(fn($d)=>[$d['no_ws'],$d['nama_pengurusan'],$d['tgl_pengurusan'],$d['created_at']], $tambahan)
        );

        // =====================================================
        // RENDER PDF
        // =====================================================
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Laporan_Worksheet_Import_".date('Ymd_His').".pdf", ["Attachment" => true]);
        exit;
    }


    

}
