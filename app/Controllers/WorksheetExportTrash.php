<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// MAIN MODELS
use App\Models\WorksheetExport\WorkSheetExportModel;
use App\Models\WorksheetExport\WorksheetContainerExportModel;
use App\Models\WorksheetExport\WorksheetTruckingExportModel;
use App\Models\WorksheetExport\WorksheetDoExportModel;
use App\Models\WorksheetExport\WorksheetLartasExportModel;
use App\Models\WorksheetExport\WorksheetInformasiTambahanExportModel;
use App\Models\WorksheetExport\WorksheetFasilitasExportModel;

// TRASH MODELS
use App\Models\WorksheetExportTrash\WorkSheetExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetContainerExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetTruckingExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetDoExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetLartasExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetInformasiTambahanExportTrashModel;
use App\Models\WorksheetExportTrash\WorksheetFasilitasExportTrashModel;

class WorksheetExportTrash extends BaseController
{
    protected $main, $cont, $do, $trucking, $lartas, $info, $fasilitas;
    protected $tMain, $tCont, $tDo, $tTrucking, $tLartas, $tInfo, $tFasilitas;

    // Child relations for EXPORT
    protected $childTables = [
        'worksheet_export' => [
            'worksheet_container_export',
            'worksheet_do_export',
            'worksheet_trucking_export',
            'worksheet_lartas_export',
            'worksheet_informasi_tambahan_export',
            'worksheet_fasilitas_export'
        ]
    ];

    public function __construct()
    {
        helper(['form', 'url', 'auth']);

        // MAIN
        $this->main      = new WorkSheetExportModel();
        $this->cont      = new WorksheetContainerExportModel();
        $this->do        = new WorksheetDoExportModel();
        $this->trucking  = new WorksheetTruckingExportModel();
        $this->lartas    = new WorksheetLartasExportModel();
        $this->info      = new WorksheetInformasiTambahanExportModel();
        $this->fasilitas = new WorksheetFasilitasExportModel();

        // TRASH
        $this->tMain      = new WorkSheetExportTrashModel();
        $this->tCont      = new WorksheetContainerExportTrashModel();
        $this->tDo        = new WorksheetDoExportTrashModel();
        $this->tTrucking  = new WorksheetTruckingExportTrashModel();
        $this->tLartas    = new WorksheetLartasExportTrashModel();
        $this->tInfo      = new WorksheetInformasiTambahanExportTrashModel();
        $this->tFasilitas = new WorksheetFasilitasExportTrashModel();
    }

    public function index()
    {
        return view('worksheet/trash_export/index');
    }

    public function list()
    {
        try {
            $table = $this->request->getGet('table') ?? 'worksheet_export';
            $trashModel = $this->getTrashModel($table);

            if (!$trashModel) return $this->response->setJSON(['data' => []]);

            $rows = $trashModel->orderBy('deleted_at', 'DESC')->findAll();
            $data = [];

            foreach ($rows as $r) {
                $data[] = [
                    'id'            => $r['id'],
                    'no_ws'         => $r['no_ws'] ?? '-',
                    'no_aju'         => $r['no_aju'] ?? '-',
                    'shipper'       => $r['shipper'] ?? '-',
                    'party'         => $r['party'] ?? '-',
                    'etd'           => $r['etd'] ?? '-',
                    'pod'           => $r['pod'] ?? '-',
                    'bl'            => $r['bl'] ?? '-',
                    'master_bl'     => $r['master_bl'] ?? '-',
                    'shipping_line' => $r['shipping_line'] ?? '-',
                    'status'        => $r['status'] ?? 'not completed',
                    'deleted_at'    => $r['deleted_at'],
                    'deleted_by'    => $r['deleted_by'],
                ];
            }

            return $this->response->setJSON(['data' => $data]);

        } catch (\Throwable $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'data'  => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * ====================
     * RESTORE EXPORT DATA
     * ====================
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

            // ID trash parent
            $origTrashId = $trash['id'];

            // CEK DUPLICATE NO_WS
            $origNoWs = $trash['no_ws'] ?? null;
            if (!empty($origNoWs)) {
                $exists = $this->main->where('no_ws', $origNoWs)->countAllResults();
                if ($exists > 0) {
                    $trash['no_ws'] = $origNoWs . '-restored-' . date('YmdHis');
                }
            }

            // CLEAR fields
            unset($trash['id'], $trash['deleted_at'], $trash['deleted_by']);

            $allowed = $mainModel->allowedFields;
            $parentData = array_intersect_key($trash, array_flip($allowed));

            $parentData['created_at'] = date('Y-m-d H:i:s');
            $parentData['updated_at'] = date('Y-m-d H:i:s');

            // Insert main parent
            $mainModel->protect(false);
            $insert = $mainModel->insert($parentData);
            $mainModel->protect(true);

            if ($insert === false) {
                $db->transComplete();
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal restore parent.'
                ]);
            }

            $newParentId = $mainModel->getInsertID();

            // Restore all children
            if (isset($this->childTables[$table])) {

                foreach ($this->childTables[$table] as $child) {

                    $childTrash = $this->getTrashModel($child);
                    $childMain  = $this->getMainModel($child);

                    if (!$childTrash || !$childMain) continue;

                    $rows = $childTrash->where('id_ws', $origTrashId)->findAll();

                    foreach ($rows as $data) {
                        $trashChildId = $data['id'];
                        unset($data['id'], $data['deleted_at'], $data['deleted_by']);

                        $data['id_ws'] = $newParentId;

                        $allowedChild = $childMain->allowedFields;
                        $childData = array_intersect_key($data, array_flip($allowedChild));

                        $childMain->protect(false);
                        $ok = $childMain->insert($childData);
                        $childMain->protect(true);

                        if ($ok === false) {
                            $db->transComplete();
                            return $this->response->setStatusCode(500)->setJSON([
                                'status' => 'error',
                                'message' => 'Gagal restore child.'
                            ]);
                        }

                        // delete from trash
                        $childTrash->delete($trashChildId, true);
                    }
                }
            }

            // delete parent
            $trashModel->delete($origTrashId, true);

            $db->transComplete();
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Worksheet Export berhasil direstore.'
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->error($e);
        }
    }

    /**
     * =======================
     * DELETE PERMANENT EXPORT
     * =======================
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

            $trashParentId = $row['id'];

            // Delete child
            if (isset($this->childTables[$table])) {
                foreach ($this->childTables[$table] as $child) {
                    $childTrash = $this->getTrashModel($child);
                    if (!$childTrash) continue;

                    $children = $childTrash->where('id_ws', $trashParentId)->findAll();
                    foreach ($children as $c) {
                        $childTrash->delete($c['id'], true);
                    }
                }
            }

            // Delete parent
            $trashModel->delete($trashParentId, true);

            $db->transComplete();
            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data permanently deleted.'
            ]);

        } catch (\Throwable $e) {
            $db->transRollback();
            return $this->error($e);
        }
    }

    /* MODEL PICKER ----------------------------------------------------- */
    private function getTrashModel($table)
    {
        return match ($table) {
            'worksheet_export'                    => $this->tMain,
            'worksheet_container_export'          => $this->tCont,
            'worksheet_do_export'                 => $this->tDo,
            'worksheet_trucking_export'           => $this->tTrucking,
            'worksheet_lartas_export'             => $this->tLartas,
            'worksheet_informasi_tambahan_export' => $this->tInfo,
            'worksheet_fasilitas_export'          => $this->tFasilitas,
            default                                => null,
        };
    }

    private function getMainModel($table)
    {
        return match ($table) {
            'worksheet_export'                    => $this->main,
            'worksheet_container_export'          => $this->cont,
            'worksheet_do_export'                 => $this->do,
            'worksheet_trucking_export'           => $this->trucking,
            'worksheet_lartas_export'             => $this->lartas,
            'worksheet_informasi_tambahan_export' => $this->info,
            'worksheet_fasilitas_export'          => $this->fasilitas,
            default                                => null,
        };
    }

    /* RESPONSE HELPERS ------------------------------------------------- */
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
