<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ContentModel;


class ContentController extends BaseController
{

    public function index()
    {
        try {

            $contentModel = new ContentModel();
            $list_content = $contentModel->findAll();

            $list_tnc = $contentModel->getTnC();
            $point_tnc = $list_tnc[0]['tnc_text'] ?? '';


            $rate_notes = [];

            foreach ($list_content as $item) {
                $rate_id = $item['rate_id'];
                $rate_note = json_decode($item['rate_note'], true);

                // Tambahkan ke array berdasarkan rate_id
                $rate_notes['rate_note_' . $rate_id] = $rate_note;
            }

            return $this->response->setJSON([
                'status' => 'success',
                'code' => 200,
                'message' => 'Mengembalikan data konten.',
                'point_tnc' => $point_tnc,
                'rate_note_1' => $rate_notes['rate_note_1'] ?? [],
                'rate_note_2' => $rate_notes['rate_note_2'] ?? [],
                'rate_note_3' => $rate_notes['rate_note_3'] ?? [],
                'rate_note_4' => $rate_notes['rate_note_4'] ?? [],
                'rate_note_5' => $rate_notes['rate_note_5'] ?? [],


            ])->setStatusCode(200);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'code' => 500,
                'message' => 'Terjadi kesalahan pada server.'
                // 'error' => $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
