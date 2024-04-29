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
use Altum\Title;

class Barcode extends Controller {

    public function index() {

        if(!settings()->codes->barcodes_is_enabled) {
            redirect('not-found');
        }

        $available_barcodes = require APP_PATH . 'includes/enabled_barcodes.php';

        $type = isset($this->params[0]) && array_key_exists($this->params[0], $available_barcodes) ? $this->params[0] : null;

        if($type) {
            if(!$this->user->plan_settings->enabled_barcodes->{$type}) {
                Alerts::add_info(l('global.info_message.plan_feature_no_access'));
                redirect('barcode');
            }

            /* Set a custom title */
            Title::set(sprintf(l('barcode.title_dynamic'), $type));
        }

        $settings = [
            'width_scale' => 2,
            'height' => 30,
            'foreground_color' => '#000000',
        ];

        /* Set default values */
        $settings['value'] = $settings['value'] ?? $_GET['value'] ?? null;

        $values = [
            'settings' => $settings,
        ];

        /* Prepare the View */
        $data = [
            'type' => $type,
            'values' => $values,
            'available_barcodes' => $available_barcodes,
        ];

        $view = new \Altum\View('barcode/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
