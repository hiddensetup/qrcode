<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$enabled_barcodes = [];

foreach(require APP_PATH . 'includes/barcodes.php' as $type => $value) {
    if(settings()->codes->available_barcodes->{$type}) {
        $enabled_barcodes[$type] = $value;
    }
}

return $enabled_barcodes;
