<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Bookingjob\BookingJobTrashModel;
use App\Models\Bookingjob\BookingJobModel;

class BookingJobTrash extends BaseController
{
    protected $trashModel;
    protected $bookingModel;

    public function __construct()
    {
        helper(['form', 'url', 'auth']);
        $this->trashModel   = new BookingJobTrashModel();
        $this->bookingModel = new BookingJobModel();
    }

    /**
     * Halaman index (view DataTables trash)
     */
    public function index()
    {
        return view('booking_job/trash/index');
    }

    /**
     * AJAX: List data untuk DataTables
     */
    public function list()
    {
        try {
            $type = $this->request->getGet('type') ?? '';

            $builder = $this->trashModel;
            if ($type !== '') {
                $builder = $builder->where('type', $type);
            }

            $rows = $builder->orderBy('deleted_at', 'DESC')->findAll();

            $data = [];
            foreach ($rows as $r) {
                $data[] = [
                    'id'            => $r['id'],
                    'no_job'        => $r['no_job'],
                    'type'          => $r['type'] ?? '',
                    'consignee'     => $r['consignee'],
                    'party'         => $r['party'],
                    'eta'           => $r['eta'],
                    'pol'           => $r['pol'],
                    'no_pib_po'     => $r['no_pib_po'],
                    'shipping_line' => $r['shipping_line'],
                    'bl'            => $r['bl'],
                    'master_bl'     => $r['master_bl'],
                    'status'        => $r['status'],
                    'deleted_at'    => $r['deleted_at'],
                    'deleted_by'    => $r['deleted_by'],
                ];
            }

            return $this->response->setJSON(['data' => $data]);
        } catch (\Exception $e) {
            log_message('error', 'Exception list trash: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'data' => [],
                'error' => 'Gagal mengambil data trash.'
            ]);
        }
    }

    /**
     * Restore data dari trash ke booking_job
     */
    public function restore($id)
    {
        try {
            $db = \Config\Database::connect();

            // Cek data trash
            $trash = $this->trashModel->find($id);
            if (empty($trash)) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'Data tidak ditemukan di trash atau kosong.'
                ]);
            }

            // Simpan No Job untuk log
            $restoredNoJob = $trash['no_job'];

            // Cek duplikasi No Job atau BL
            $exists = $db->table('booking_job')
                ->groupStart()
                ->where('no_job', $trash['no_job'])
                ->orWhere('bl', $trash['bl'])
                ->groupEnd()
                ->limit(1)
                ->countAllResults();

            if ($exists > 0) {
                return $this->response->setStatusCode(409)->setJSON([
                    'status'  => 'error',
                    'message' => "Tidak bisa restore. No Job ({$trash['no_job']}) atau BL ({$trash['bl']}) sudah dipakai."
                ]);
            }

            // Bersihkan kolom trash-only
            unset($trash['id'], $trash['deleted_at'], $trash['deleted_by']);

            // Set timestamp baru
            $now = date('Y-m-d H:i:s');
            $trash['created_at'] = $now;
            $trash['updated_at'] = $now;

            // Insert kembali ke tabel utama
            $this->bookingModel->protect(false);
            $inserted = $this->bookingModel->insert($trash);

            if ($inserted === false) {
                log_message('error', 'Restore gagal insert: ' . json_encode($this->bookingModel->errors()));
                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal merestore data (DB error).'
                ]);
            }

            // LOG RESTORE
            addLog('Merestore Booking Job No:"' . $restoredNoJob . '"');

            // Hapus dari trash setelah restore berhasil
            $this->trashModel->delete($id, true);

            return $this->response->setJSON([
                'status'  => 'ok',
                'message' => 'Data berhasil direstore.'
            ]);
        } catch (\Exception $e) {

            log_message('error', 'Exception restore: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server.'
            ]);
        } finally {
            // pastikan protect dikembalikan
            $this->bookingModel->protect(true);
        }
    }



    /**
     * Hapus permanen data dari trash
     */
    public function deletePermanent($id)
    {
        try {
            // Ambil data dari trash
            $row = $this->trashModel->find($id);
            if (!$row) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status'  => 'error',
                    'message' => 'Data tidak ditemukan di trash.'
                ]);
            }

            // Simpan dulu data untuk log
            $noJob = $row['no_job'];

            // Hapus permanen (force delete)
            $deleted = $this->trashModel->delete($id, true);

            if ($deleted) {

                // LOG: catat bahwa data dihapus permanen
                addLog('Menghapus Permanen Booking Job No:"' . $noJob . '"');

                return $this->response->setJSON([
                    'status'  => 'ok',
                    'message' => 'Data berhasil dihapus permanen.'
                ]);
            } else {
                log_message('error', 'Delete permanent gagal untuk id=' . $id);

                return $this->response->setStatusCode(500)->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menghapus data permanen (DB error).'
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Exception deletePermanent: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan server.'
            ]);
        }
    }
}
