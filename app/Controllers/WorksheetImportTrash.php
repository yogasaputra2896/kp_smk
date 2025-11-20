<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// MAIN MODELS
use App\Models\WorksheetImport\WorkSheetImportModel;
use App\Models\WorksheetImport\WorksheetContainerModel;
use App\Models\WorksheetImport\WorksheetTruckingModel;
use App\Models\WorksheetImport\WorksheetDoModel;
use App\Models\WorksheetImport\WorksheetLartasModel;
use App\Models\WorksheetImport\WorksheetInformasiTambahanModel;
use App\Models\WorksheetImport\WorksheetFasilitasModel;

// TRASH MODELS
use App\Models\WorksheetImportTrash\WorkSheetImportTrashModel;
use App\Models\WorksheetImportTrash\WorksheetContainerTrashModel;
use App\Models\WorksheetImportTrash\WorksheetTruckingTrashModel;
use App\Models\WorksheetImportTrash\WorksheetDoTrashModel;
use App\Models\WorksheetImportTrash\WorksheetLartasTrashModel;
use App\Models\WorksheetImportTrash\WorksheetInformasiTambahanTrashModel;
use App\Models\WorksheetImportTrash\WorksheetFasilitasTrashModel;

class WorksheetImportTrash extends BaseController
{
    protected $main, $cont, $do, $trucking, $lartas, $info, $fasilitas;
    protected $tMain, $tCont, $tDo, $tTrucking, $tLartas, $tInfo, $tFasilitas;

    // Child tables (real table names)
    protected $childTables = [
        'worksheet_import' => [
            'worksheet_container_import',
            'worksheet_do_import',
            'worksheet_trucking_import',
            'worksheet_lartas_import',
            'worksheet_informasi_tambahan_import',
            'worksheet_fasilitas_import'
        ]
    ];

    public function __construct()
    {
        helper(['form', 'url', 'auth']);

        // MAIN
        $this->main      = new WorkSheetImportModel();
        $this->cont      = new WorksheetContainerModel();
        $this->do        = new WorksheetDoModel();
        $this->trucking  = new WorksheetTruckingModel();
        $this->lartas    = new WorksheetLartasModel();
        $this->info      = new WorksheetInformasiTambahanModel();
        $this->fasilitas = new WorksheetFasilitasModel();

        // TRASH
        $this->tMain      = new WorkSheetImportTrashModel();
        $this->tCont      = new WorksheetContainerTrashModel();
        $this->tDo        = new WorksheetDoTrashModel();
        $this->tTrucking  = new WorksheetTruckingTrashModel();
        $this->tLartas    = new WorksheetLartasTrashModel();
        $this->tInfo      = new WorksheetInformasiTambahanTrashModel();
        $this->tFasilitas = new WorksheetFasilitasTrashModel();
    }

    public function index()
    {
        return view('worksheet/trash_import/index');
    }

    public function list()
    {
        try {

            $table = $this->request->getGet('table') ?? 'worksheet_import';
            $trashModel = $this->getTrashModel($table);

            if (!$trashModel) return $this->response->setJSON(['data' => []]);

            $rows = $trashModel->orderBy('deleted_at', 'DESC')->findAll();
            $data = [];

            foreach ($rows as $r) {
                $data[] = [
                    'id'            => $r['id'],
                    'no_ws'         => $r['no_ws'] ?? '-',
                    'no_aju'        => $r['no_aju'] ?? '-',
                    'consignee'     => $r['consignee'] ?? '-',
                    'party'         => $r['party'] ?? '-',
                    'eta'           => $r['eta'] ?? '-',
                    'pol'           => $r['pol'] ?? '-',
                    'bl'            => $r['bl'] ?? '-',
                    'master_bl'     => $r['master_bl'] ?? '-',
                    'shipping_line' => $r['shipping_line'] ?? '-',
                    'status'        => $r['status'] ?? 'not completed',
                    'deleted_at'    => $r['deleted_at'],
                    'deleted_by'    => $r['deleted_by'],
                ];
            }

            return $this->response->setJSON(['data' => $data]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'data'  => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * ========
     * RESTORE 
     * ========
     */
    public function restore($table, $id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $trashModel = $this->getTrashModel($table);
            $mainModel  = $this->getMainModel($table);

            if (!$trashModel || !$mainModel) return $this->failModel();

            $trash = $trashModel->find($id);
            if (!$trash) return $this->failNotFound();

            // Ambil data parent dari trash
            $origTrashId = $trash['id'];      // id di tabel trash
            $origNoWs    = $trash['no_ws'] ?? null;

            // CEK DUPLICATE no_ws di tabel utama
            if (!empty($origNoWs)) {
                $exists = $this->main->where('no_ws', $origNoWs)->limit(1)->countAllResults();
                if ($exists > 0) {
                    // tambahkan suffix agar tidak error (atau bisa Anda ubah jadi return 409)
                    $suffix = '-restored-' . date('YmdHis');
                    $trash['no_ws'] = $origNoWs . $suffix;
                }
            }

            // siapkan data untuk insert ke tabel utama (filter allowedFields)
            unset($trash['id'], $trash['deleted_at'], $trash['deleted_by']);
            $allowed = $mainModel->allowedFields;
            $restoreParent = array_intersect_key($trash, array_flip($allowed));

            $restoreParent['created_at'] = date('Y-m-d H:i:s');
            $restoreParent['updated_at'] = date('Y-m-d H:i:s');

            // insert parent ke main
            $mainModel->protect(false);
            $inserted = $mainModel->insert($restoreParent);
            $mainModel->protect(true);

            if ($inserted === false) {
                $db->transComplete();
                log_message('error', 'Restore gagal insert parent: ' . json_encode($mainModel->errors()));
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal merestore worksheet (insert parent).'
                ]);
            }

            $newParentId = $mainModel->getInsertID();

            // Restore child: cari child di trash berdasarkan id_ws == trash parent id
            if (isset($this->childTables[$table])) {
                foreach ($this->childTables[$table] as $child) {
                    $childTrash = $this->getTrashModel($child);
                    $childMain  = $this->getMainModel($child);
                    if (!$childTrash || !$childMain) continue;

                    $childRows = $childTrash->where('id_ws', $origTrashId)->findAll();
                    foreach ($childRows as $c) {
                        $trashChildId = $c['id'] ?? null;
                        unset($c['id'], $c['deleted_at'], $c['deleted_by']);

                        // ganti id_ws ke id parent baru di main
                        $c['id_ws'] = $newParentId;

                        $allowedChild = $childMain->allowedFields;
                        $childData = array_intersect_key($c, array_flip($allowedChild));

                        $childMain->protect(false);
                        $childInsert = $childMain->insert($childData);
                        $childMain->protect(true);

                        if ($childInsert === false) {
                            log_message('error', 'Restore child failed: ' . json_encode($childMain->errors()));
                            // lanjutkan rollback dan stop
                            $db->transComplete();
                            return $this->response->setStatusCode(500)->setJSON([
                                'status' => 'error',
                                'message' => 'Gagal merestore salah satu relasi child.'
                            ]);
                        }

                        // hapus child di trash
                        if ($trashChildId) {
                            $childTrash->delete($trashChildId, true);
                        }
                    }
                }
            }

            // hapus parent di trash
            $trashModel->delete($origTrashId, true);

            $db->transComplete();
            if ($db->transStatus() === false) {
                log_message('error', 'Transaksi restore gagal untuk trash id=' . $origTrashId);
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal merestore worksheet (transaksi gagal).'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Worksheet & relasi berhasil direstore.'
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Exception restore: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat restore.'
            ]);
        }
    }


    /**
     * =================
     * DELETE PERMANENT 
     * =================
     */
    public function deletePermanent($table, $id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $trashModel = $this->getTrashModel($table);
            if (!$trashModel) return $this->failModel();

            $row = $trashModel->find($id);
            if (!$row) return $this->failNotFound();

            $trashParentId = $row['id']; // id di trash (child id_ws points to this)

            // hapus semua child di trash yang id_ws == trash parent id
            if (isset($this->childTables[$table])) {
                foreach ($this->childTables[$table] as $child) {
                    $childTrash = $this->getTrashModel($child);
                    if (!$childTrash) continue;

                    $childRows = $childTrash->where('id_ws', $trashParentId)->findAll();
                    foreach ($childRows as $c) {
                        $childTrash->delete($c['id'], true);
                    }
                }
            }

            // hapus parent di trash
            $trashModel->delete($trashParentId, true);

            $db->transComplete();
            if ($db->transStatus() === false) {
                log_message('error', 'Transaksi deletePermanent gagal untuk trash id=' . $trashParentId);
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal menghapus permanen (transaksi gagal).'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'ok',
                'message' => 'Data berhasil dihapus permanen.'
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Exception deletePermanent: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus permanen.'
            ]);
        }
    }


    /* ======================================================
     * MODEL PICKER
     * ====================================================== */
    private function getTrashModel($table)
    {
        return match ($table) {
            'worksheet_import'                    => $this->tMain,
            'worksheet_container_import'          => $this->tCont,
            'worksheet_do_import'                 => $this->tDo,
            'worksheet_trucking_import'           => $this->tTrucking,
            'worksheet_lartas_import'             => $this->tLartas,
            'worksheet_informasi_tambahan_import' => $this->tInfo,
            'worksheet_fasilitas_import'          => $this->tFasilitas,
            default                                => null,
        };
    }

    private function getMainModel($table)
    {
        return match ($table) {
            'worksheet_import'                    => $this->main,
            'worksheet_container_import'          => $this->cont,
            'worksheet_do_import'                 => $this->do,
            'worksheet_trucking_import'           => $this->trucking,
            'worksheet_lartas_import'             => $this->lartas,
            'worksheet_informasi_tambahan_import' => $this->info,
            'worksheet_fasilitas_import'          => $this->fasilitas,
            default                                => null,
        };
    }

    /* ======================================================
     * RESPONSE HELPER
     * ====================================================== */
    private function success($msg)
    {
        return $this->response->setJSON([
            'status'  => 'ok',
            'message' => $msg
        ]);
    }

    private function failNotFound()
    {
        return $this->response->setStatusCode(404)->setJSON([
            'status'  => 'error',
            'message' => 'Data tidak ditemukan.'
        ]);
    }

    private function failModel()
    {
        return $this->response->setStatusCode(400)->setJSON([
            'status'  => 'error',
            'message' => 'Model tidak ditemukan.'
        ]);
    }

    private function error($e)
    {
        log_message('error', $e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'status'  => 'error',
            'message' => 'Kesalahan server.'
        ]);
    }
}
