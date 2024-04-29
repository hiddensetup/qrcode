<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$access = [
    'read' => [
        'read.all' => l('global.all')
    ],

    'create' => [
        'create.links' => l('links.title'),
        'create.pixels' => l('pixels.title'),
        'create.projects' => l('projects.title'),
    ],

    'update' => [
        'update.links' => l('links.title'),
        'update.pixels' => l('pixels.title'),
        'update.projects' => l('projects.title'),
    ],

    'delete' => [
        'delete.links' => l('links.title'),
        'delete.pixels' => l('pixels.title'),
        'delete.projects' => l('projects.title'),
    ],
];

if(settings()->codes->qr_codes_is_enabled) {
    $access['create']['create.qr_codes'] = l('qr_codes.title');
    $access['update']['update.qr_codes'] = l('qr_codes.title');
    $access['delete']['delete.qr_codes'] = l('qr_codes.title');
}

if(settings()->codes->barcodes_is_enabled) {
    $access['create']['create.barcodes'] = l('barcodes.title');
    $access['update']['update.barcodes'] = l('barcodes.title');
    $access['delete']['delete.barcodes'] = l('barcodes.title');
}

if(settings()->links->domains_is_enabled) {
    $access['create']['create.domains'] = l('domains.title');
    $access['update']['update.domains'] = l('domains.title');
    $access['delete']['delete.domains'] = l('domains.title');
}

return $access;
