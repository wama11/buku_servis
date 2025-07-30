<?php

namespace App\Controllers;

use App\Models\PromoModel;
use CodeIgniter\HTTP\ResponseInterface;

class PromoScraper extends BaseController
{
    public function index()
    {
        $url = 'https://planetban.com/promo';
        $html = file_get_contents($url);

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);
        $promoNodes = $xpath->query("//div[contains(@class, 'promo-group-items')]//a");
        $titleNode = $xpath->query("//ul[contains(@class, 'nav-groups')]//li[contains(@class, 'active')]//span");

        $promoModel = new PromoModel();
        $db = \Config\Database::connect();
        $db->table('promo')->truncate(); // Gunakan query builder agar truncate bekerja

        $title = $titleNode->length > 0 ? $titleNode->item(0)->nodeValue : 'Promo';

        foreach ($promoNodes as $node) {
            $targetUrl = $node->getAttribute('href');
            $img = $node->getElementsByTagName('img')->item(0);
            $imgUrl = $img ? $img->getAttribute('src') : null;

            if ($imgUrl && $targetUrl) {
                $promoModel->insert([
                    'image_url' => $imgUrl,
                    'target_url' => $targetUrl,
                    'title' => $title,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $this->response->setJSON(['message' => 'Data promo berhasil disimpan.']);
    }
}

