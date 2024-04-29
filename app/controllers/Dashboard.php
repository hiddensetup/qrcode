<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Get some stats */
        $total_links = \Altum\Cache::cache_function_result('links_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('links', 'count(*)');
        });

        $total_pixels = \Altum\Cache::cache_function_result('pixels_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('pixels', 'count(*)');
        });

        $total_domains = \Altum\Cache::cache_function_result('domains_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('domains', 'count(*)');
        });

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* QR Codes */
        if(settings()->codes->qr_codes_is_enabled) {
            $total_qr_codes = \Altum\Cache::cache_function_result('qr_codes_total?user_id=' . $this->user->user_id, null, function () {
                return db()->where('user_id', $this->user->user_id)->getValue('qr_codes', 'count(*)');
            });

            /* Get the QR codes */
            $qr_codes = \Altum\Cache::cache_function_result('qr_codes_dashboard?user_id=' . $this->user->user_id, null, function() {
                $qr_codes = [];
                $qr_codes_result = database()->query("SELECT * FROM `qr_codes` WHERE `user_id` = {$this->user->user_id} ORDER BY `qr_code_id` DESC LIMIT 5");
                while ($row = $qr_codes_result->fetch_object()) {
                    $row->settings = json_decode($row->settings ?? '');
                    $qr_codes[] = $row;
                }

                return $qr_codes;
            });

            $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';
        }

        /* Barcodes */
        if(settings()->codes->barcodes_is_enabled) {
            $total_barcodes = \Altum\Cache::cache_function_result('barcodes_total?user_id=' . $this->user->user_id, null, function () {
                return db()->where('user_id', $this->user->user_id)->getValue('barcodes', 'count(*)');
            });

            /* Get the barcodes */
            $barcodes = \Altum\Cache::cache_function_result('barcodes_dashboard?user_id=' . $this->user->user_id, null, function() {
                $barcodes = [];
                $barcodes_result = database()->query("SELECT * FROM `barcodes` WHERE `user_id` = {$this->user->user_id} ORDER BY `barcode_id` DESC LIMIT 5");
                while ($row = $barcodes_result->fetch_object()) {
                    $row->settings = json_decode($row->settings ?? '');
                    $barcodes[] = $row;
                }

                return $barcodes;
            });

            $available_barcodes = require APP_PATH . 'includes/enabled_barcodes.php';
        }

        /* Prepare the View */
        $data = [
            'qr_codes' => $qr_codes ?? [],
            'total_qr_codes' => $total_qr_codes ?? null,
            'available_qr_codes'  => $available_qr_codes ?? [],

            'barcodes' => $barcodes ?? [],
            'total_barcodes' => $total_barcodes ?? null,
            'available_barcodes'  => $available_barcodes ?? [],

            'projects' => $projects,
            'total_links' => $total_links,
            'total_pixels' => $total_pixels,
            'total_domains' => $total_domains,
            'total_projects' => count($projects),
        ];

        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
