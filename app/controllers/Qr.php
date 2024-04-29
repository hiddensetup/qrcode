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
use Altum\Title;

class Qr extends Controller {

    public function index() {

        if(!settings()->codes->qr_codes_is_enabled) {
            redirect('not-found');
        }

        $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';
        $frames = require APP_PATH . 'includes/qr_codes_frames.php';
        $frames_fonts = require APP_PATH . 'includes/qr_codes_frames_text_fonts.php';

        $type = isset($this->params[0]) && array_key_exists($this->params[0], $available_qr_codes) ? $this->params[0] : null;

        if($type) {
            if(!$this->user->plan_settings->enabled_qr_codes->{$type}) {
                Alerts::add_info(l('global.info_message.plan_feature_no_access'));
                redirect('qr');
            }

            /* Set a custom title */
            Title::set(sprintf(l('qr.title_dynamic'), l('qr_codes.type.' . $type)));

            if($type == 'url' && \Altum\Authentication::check()) {
                /* Existing links */
                $links = (new \Altum\Models\Link())->get_full_links_by_user_id($this->user->user_id);
            }

            /* Process dynamic view */
            $data = [
                'available_qr_codes' => $available_qr_codes,
                'frames_fonts' => $frames_fonts,
                'frames' => $frames,
                'links' => $links ?? [],
            ];
            $view = new \Altum\View('qr/partials/' . $type . '_form', (array) $this);
            $this->add_view_content('qr_form', $view->run($data));
        }

        /* Main View */
        $data = [
            'type' => $type,
            'available_qr_codes' => $available_qr_codes,
            'frames_fonts' => $frames_fonts,
            'frames' => $frames,
            'links' => $links ?? [],
        ];

        $view = new \Altum\View('qr/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
