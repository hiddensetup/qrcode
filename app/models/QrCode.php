<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

use Altum\Uploads;

class QrCode extends Model {

    public function delete($qr_code_id) {

        if(!$qr_code = db()->where('qr_code_id', $qr_code_id)->getOne('qr_codes', ['user_id', 'qr_code_id', 'qr_code', 'qr_code_logo', 'qr_code_background'])) {
            return;
        }

        Uploads::delete_uploaded_file($qr_code->qr_code, 'qr_codes/logo');
        Uploads::delete_uploaded_file($qr_code->qr_code_logo, 'qr_codes/logo');
        Uploads::delete_uploaded_file($qr_code->qr_code_background, 'qr_code_background');
        Uploads::delete_uploaded_file($qr_code->qr_code_foreground, 'qr_code_foreground');

        /* Delete from database */
        db()->where('qr_code_id', $qr_code_id)->delete('qr_codes');

        /* Clear the cache */
        cache()->deleteItem('qr_codes_total?user_id=' . $qr_code->user_id);
        cache()->deleteItem('qr_codes_dashboard?user_id=' . $qr_code->user_id);

    }
}
