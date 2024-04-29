<?php defined('ALTUMCODE') || die() ?>

<div class="card my-3">
    <div class="card-body">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center">
                <h3 class="h5 text-truncate m-0"><?= sprintf(l('link_statistics.statistics.utm_campaign'), $data->utm_source, $data->utm_medium) ?></h3>
            </div>

            <div class="d-flex align-items-center col-auto p-0">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('link-statistics/' . $data->link->link_id . '?' . \Altum\Router::$original_request_query . '&export=csv') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('link-statistics/' . $data->link->link_id . '?' . \Altum\Router::$original_request_query . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" onclick="window.print();return false;" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!count($data->rows)): ?>
            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'global',
            'has_secondary_text' => false,
            'has_wrapper' => false,
        ]); ?>
        <?php else: ?>

            <?php foreach($data->rows as $row): ?>
                <?php $percentage = round($row->total / $data->total_sum * 100, 1) ?>

                <div class="mt-4">
                    <div class="d-flex justify-content-between mb-1">
                        <div class="text-truncate">
                            <span><?= $row->utm_campaign ?></span>
                        </div>

                        <div>
                            <small class="text-muted"><?= nr($percentage) . '%' ?></small>
                            <span class="ml-3"><?= nr($row->total) ?></span>
                        </div>
                    </div>

                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            <?php endforeach ?>

        <?php endif ?>
    </div>
</div>
