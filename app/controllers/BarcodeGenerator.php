<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

use Altum\Alerts;
use Altum\Response;
use Altum\Uploads;
use Picqer\Barcode\BarcodeGeneratorSVG;
use SimpleSoftwareIO\QrCode\Generator;
use SVG\Nodes\Embedded\SVGImage;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Structures\SVGGroup;
use SVG\SVG;

class BarcodeGenerator extends Controller {

    public function index() {

        if(empty($_POST)) {
            redirect();
        }

        /* :) */
        $available_barcodes = require APP_PATH . 'includes/enabled_barcodes.php';

        if(isset($_POST['json'])) {
            $_POST = json_decode($_POST['json'], true);
        }

        $_POST['type'] = isset($_POST['type']) && array_key_exists($_POST['type'], $available_barcodes) ? $_POST['type'] : 'text';

        /* Check for the API Key if needed */
        if(!isset($_POST['api_key']) || (isset($_POST['api_key']) && empty($_POST['api_key']))) {
            /* Check the guest plan */
            if(!$this->user->plan_settings->enabled_barcodes->{$_POST['type']}) {
                die();
            }
        } else {
            $user = db()->where('api_key', $_POST['api_key'])->where('status', 1)->getOne('users');

            if(!$user) {
                die();
            }
        }

        /* Process variables */
        $_POST['foreground_color'] = isset($_POST['foreground_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_color']) ? $_POST['foreground_color'] : '#000000';
        $_POST['width_scale'] = isset($_POST['width_scale']) && in_array($_POST['width_scale'], range(1, 10)) ? (int) $_POST['width_scale'] : 2;
        $_POST['height'] = isset($_POST['height']) && in_array($_POST['height'], range(30, 1000)) ? (int) $_POST['height'] : 30;

        $_POST['is_bulk'] = (int) (bool) ($_POST['is_bulk'] ?? 0);
        if($_POST['is_bulk']) {
            $_POST['value'] = preg_split('/\r\n|\r|\n/', $_POST['value'])[0];
        }

        $data = trim($_POST['value']);

        /* :) */
        $barcode = new BarcodeGeneratorSVG();

        /* Check if data is empty */
        if(!trim($data)) {
            $data = 1;
        }

        /* Generate the first SVG */
        try {
            $svg = $barcode->getBarcode($data, $_POST['type'], $_POST['width_scale'], $_POST['height'], $_POST['foreground_color']);
        } catch (\Exception $exception) {
            Response::json($exception->getMessage(), 'error');
        }

        $image_data = 'data:image/svg+xml;base64,' . base64_encode($svg);

        Response::json('', 'success', ['data' => $image_data, 'embedded_data' => $data]);

    }

}
