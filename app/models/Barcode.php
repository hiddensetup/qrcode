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

class Barcode extends Model {

    public function delete($barcode_id) {

        if(!$barcode = db()->where('barcode_id', $barcode_id)->getOne('barcodes', ['user_id', 'barcode_id', 'barcode'])) {
            return;
        }

        Uploads::delete_uploaded_file($barcode->barcode, 'barcodes/logo');

        /* Delete from database */
        db()->where('barcode_id', $barcode_id)->delete('barcodes');

        /* Clear the cache */
        cache()->deleteItem('barcodes_total?user_id=' . $barcode->user_id);
        cache()->deleteItem('barcodes_dashboard?user_id=' . $barcode->user_id);
    }
}
