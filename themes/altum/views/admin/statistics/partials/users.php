<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div id="countries_map"></div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-globe-europe fa-xs text-primary-900 mr-2"></i> <?= l('global.continents') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.continent') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->continents as $continent_code => $total): ?>
                    <tr>
                        <td class="text-nowrap">
                            <?= $continent_code ? get_continent_from_continent_code($continent_code) : l('global.unknown') ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total / $data->total['continents'] * 100, 2) . '%'; ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-flag fa-xs text-primary-900 mr-2"></i> <?= l('global.countries') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.country') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->countries as $country_code => $total): ?>
                    <tr>
                        <td class="text-nowrap">
                            <?php if($country_code): ?>
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($country_code) . '.svg' ?>" class="icon-favicon mr-2" title="<?= get_country_from_country_code($country_code) ?>" />
                                <?= get_country_from_country_code($country_code) ?>
                            <?php else: ?>
                                <?= l('global.unknown') ?>
                            <?php endif ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total / $data->total['countries'] * 100, 2) . '%'; ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-sign-in-alt fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.sources') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.users.source') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->sources as $source => $total): ?>
                    <tr>
                        <td class="text-nowrap">
                            <?= l('admin_users.main.source.' . $source) ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total / $data->total['sources'] * 100, 2) . '%'; ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-box-open fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.plans') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.users.source') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->plans as $plan => $total): ?>
                    <tr>
                        <td class="text-nowrap">
                            <?= (new \Altum\Models\Plan())->get_plan_by_id($plan)->name ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total / $data->total['plans'] * 100, 2) . '%'; ?>
                        </td>
                        <td class="text-nowrap">
                            <?= nr($total) ?>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/svgMap.min.css' ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/svgMap.min.js' ?>"></script>

<script>
    'use strict';

    /* Create the map */
    new svgMap({
        targetElementID: 'countries_map',
        data: {
            data: {
                users: {
                    name: '',
                    format: '{0} <?= l('admin_statistics.users') ?>',
                    thousandSeparator: thousands_separator,
                },
            },
            applyData: 'users',
            values: <?= json_encode($data->countries_map) ?>,
        },
        colorMin: css.getPropertyValue('--primary-100'),
        colorMax: css.getPropertyValue('--primary-800'),
        colorNoData: css.getPropertyValue('--gray-200'),
        flagType: 'emoji',
        noDataText: <?= json_encode(l('global.no_data')) ?>
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
