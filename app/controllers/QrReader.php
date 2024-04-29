<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;

class QrReader extends Controller {

    public function index() {

        if(!settings()->codes->qr_reader_is_enabled) {
            redirect('not-found');
        }

        if(!$this->user->plan_settings->qr_reader_is_enabled) {
            Alerts::add_info(l('global.info_message.plan_feature_no_access'));
            redirect();
        }

        /* Main View */
        $data = [];

        $view = new \Altum\View('qr-reader/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
