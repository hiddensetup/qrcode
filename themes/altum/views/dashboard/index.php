<?php defined('ALTUMCODE') || die() ?>


<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <h1 class="h4 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-table-cells mr-1"></i> <?= l('dashboard.header') ?></h1>
        </div>
    </div>

    <div class="my-4">
        <div class="row">
            <?php if(settings()->codes->qr_codes_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('qr-codes') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-qrcode text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('dashboard.total_qr_codes'), '<span class="h6">' . nr($data->total_qr_codes) . '</span>') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->codes->barcodes_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('barcodes') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-barcode text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('dashboard.total_barcodes'), '<span class="h6">' . nr($data->total_barcodes) . '</span>') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('links') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-link text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_links'), '<span class="h6">' . nr($data->total_links) . '</span>') ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('pixels') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-adjust text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_pixels'), '<span class="h6">' . nr($data->total_pixels) . '</span>') ?>
                    </div>
                </div>
            </div>

            <?php if(settings()->links->domains_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                    <div class="card d-flex flex-row h-100 overflow-hidden">
                        <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                            <a href="<?= url('domains') ?>" class="stretched-link">
                                <i class="fas fa-fw fa-globe text-primary-600"></i>
                            </a>
                        </div>

                        <div class="card-body text-truncate">
                            <?= sprintf(l('dashboard.total_domains'), '<span class="h6">' . nr($data->total_domains) . '</span>') ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="col-12 col-sm-6 col-xl-4 p-3 position-relative text-truncate">
                <div class="card d-flex flex-row h-100 overflow-hidden">
                    <div class="border-right border-gray-200 px-3 d-flex flex-column justify-content-center">
                        <a href="<?= url('projects') ?>" class="stretched-link">
                            <i class="fas fa-fw fa-diagram-project text-primary-600"></i>
                        </a>
                    </div>

                    <div class="card-body text-truncate">
                        <?= sprintf(l('dashboard.total_projects'), '<span class="h6">' . nr($data->total_projects) . '</span>') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(settings()->codes->qr_codes_is_enabled): ?>
        <div class="mt-4 mb-5">
            <div class="d-flex align-items-center mb-3">
                <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-qrcode mr-1"></i> <?= l('dashboard.qr_codes_header') ?></h2>

                <div class="flex-fill">
                    <hr class="border-gray-200" />
                </div>

                <div class="ml-3">
                    <a href="<?= url('qr-code-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('qr_codes.create') ?></a>
                </div>
            </div>

            <?php if(count($data->qr_codes)): ?>
                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= l('global.name') ?></th>
                            <th><?= l('global.type') ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->qr_codes as $row): ?>
                            <tr>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3" data-toggle="tooltip" title="<?= l('global.download') ?>">
                                            <a href="<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>" download="<?= $row->name . '.svg' ?>" target="_blank">
                                                <img src="<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>" class="qr-code-avatar" loading="lazy" />
                                            </a>
                                        </div>

                                        <div class="d-flex flex-column ">
                                            <div>
                                                <a href="<?= url('qr-code-update/' . $row->qr_code_id) ?>" class="font-weight-bold text-truncate"><?= $row->name ?></a>
                                            </div>
                                            <?php if($row->type == 'url'): ?>
                                                <div class="d-flex align-items-center">
                                                    <small class="d-inline-block text-truncate text-muted">
                                                        <?= $row->settings->url ?>
                                                    </small>

                                                    <?php if($row->link_id): ?>
                                                        <a href="<?= url('link-update/' . $row->link_id) ?>" class="btn btn-sm btn-link" data-toggle="tooltip" title="<?= l('global.update') ?>"><i class="fas fa-fw fa-pencil-alt"></i></a>
                                                        <a href="<?= url('link-statistics/' . $row->link_id) ?>" class="btn btn-sm btn-link" data-toggle="tooltip" title="<?= l('link_statistics.pageviews') ?>"><i class="fas fa-fw fa-chart-bar"></i></a>
                                                    <?php endif ?>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                <span class="badge badge-light">
                                    <i class="<?= $data->available_qr_codes[$row->type]['icon'] ?> fa-fw fa-sm mr-1"></i>
                                    <?= l('qr_codes.type.' . $row->type) ?>
                                </span>
                                </td>

                                <td class="text-nowrap">
                                    <?php if($row->project_id): ?>
                                        <a href="<?= url('qr-codes?project_id=' . $row->project_id) ?>" class="text-decoration-none" data-toggle="tooltip" title="<?= l('projects.project_id') ?>">
                                            <span class="badge badge-light" style="color: <?= $data->projects[$row->project_id]->color ?> !important;">
                                                <?= $data->projects[$row->project_id]->name ?>
                                            </span>
                                        </a>
                                    <?php endif ?>
                                </td>

                                <td class="text-nowrap text-muted">
                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted"></i>
                                </span>

                                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' : '-')) ?>">
                                    <i class="fas fa-fw fa-history text-muted"></i>
                                </span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <button type="button" class="btn btn-block btn-link dropdown-toggle dropdown-toggle-simple" title="<?= l('global.download') ?>" data-toggle="dropdown" aria-expanded="false" data-tooltip data-tooltip-hide-on-click>
                                                <i class="fas fa-fw fa-sm fa-download"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a href="<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>" class="dropdown-item" download="<?= get_slug($row->name) . '.svg' ?>"><?= sprintf(l('global.download_as'), 'SVG') ?></a>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>', 'png', '<?= get_slug($row->name) . '.png' ?>');"><?= sprintf(l('global.download_as'), 'PNG') ?></button>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>', 'jpg', '<?= get_slug($row->name) . '.jpg' ?>');"><?= sprintf(l('global.download_as'), 'JPG') ?></button>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_qr_code_to_others('<?= \Altum\Uploads::get_full_url('qr_codes') . $row->qr_code ?>', 'webp', '<?= get_slug($row->name) . '.webp' ?>');"><?= sprintf(l('global.download_as'), 'WEBP') ?></button>
                                            </div>
                                        </div>

                                        <?= include_view(THEME_PATH . 'views/qr-codes/qr_code_dropdown_button.php', ['id' => $row->qr_code_id, 'resource_name' => $row->name]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5">
                                <a href="<?= url('qr-codes') ?>" class="text-muted">
                                    <i class="fas fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('global.view_all') ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            <?php else: ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'qr_codes',
                    'has_secondary_text' => true,
                ]); ?>

            <?php endif ?>

        </div>
    <?php endif ?>

    <?php if(settings()->codes->barcodes_is_enabled): ?>
        <div class="mt-4 mb-5">
            <div class="d-flex align-items-center mb-3">
                <h2 class="small font-weight-bold text-uppercase text-muted mb-0 mr-3"><i class="fas fa-fw fa-sm fa-qrcode mr-1"></i> <?= l('dashboard.barcodes_header') ?></h2>

                <div class="flex-fill">
                    <hr class="border-gray-200" />
                </div>

                <div class="ml-3">
                    <a href="<?= url('barcode-create') ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('barcodes.create') ?></a>
                </div>
            </div>

            <?php if(count($data->barcodes)): ?>
                <div class="table-responsive table-custom-container">
                    <table class="table table-custom">
                        <thead>
                        <tr>
                            <th><?= l('global.name') ?></th>
                            <th><?= l('global.type') ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach($data->barcodes as $row): ?>
                            <tr>
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3" data-toggle="tooltip" title="<?= l('global.download') ?>">
                                            <a href="<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>" download="<?= $row->name . '.svg' ?>" target="_blank">
                                                <img src="<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>" class="barcode-avatar" loading="lazy" />
                                            </a>
                                        </div>

                                        <div>
                                            <a href="<?= url('barcode-update/' . $row->barcode_id) ?>" class="font-weight-bold text-truncate"><?= $row->name ?></a>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-nowrap">
                                    <span class="badge badge-light">
                                        <?= $row->type ?>
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <?php if($row->project_id): ?>
                                        <a href="<?= url('barcodes?project_id=' . $row->project_id) ?>" class="text-decoration-none" data-toggle="tooltip" title="<?= l('projects.project_id') ?>">
                                            <span class="badge badge-light" style="color: <?= $data->projects[$row->project_id]->color ?> !important;">
                                                <?= $data->projects[$row->project_id]->name ?>
                                            </span>
                                        </a>
                                    <?php endif ?>
                                </td>

                                <td class="text-nowrap text-muted">
                                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>') ?>">
                                        <i class="fas fa-fw fa-calendar text-muted"></i>
                                    </span>

                                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.last_datetime_tooltip'), ($row->last_datetime ? '<br />' . \Altum\Date::get($row->last_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->last_datetime, 3) . '</small>' : '-')) ?>">
                                        <i class="fas fa-fw fa-history text-muted"></i>
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-end">
                                        <div>
                                            <button type="button" class="btn btn-block btn-link dropdown-toggle dropdown-toggle-simple" title="<?= l('global.download') ?>" data-toggle="dropdown" aria-expanded="false" data-tooltip data-tooltip-hide-on-click>
                                                <i class="fas fa-fw fa-sm fa-download"></i>
                                            </button>

                                            <div class="dropdown-menu">
                                                <a href="<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>" class="dropdown-item" download="<?= get_slug($row->name) . '.svg' ?>"><?= sprintf(l('global.download_as'), 'SVG') ?></a>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_barcode_to_others('<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>', 'png', '<?= get_slug($row->name) . '.png' ?>');"><?= sprintf(l('global.download_as'), 'PNG') ?></button>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_barcode_to_others('<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>', 'jpg', '<?= get_slug($row->name) . '.jpg' ?>');"><?= sprintf(l('global.download_as'), 'JPG') ?></button>
                                                <button type="button" class="dropdown-item" onclick="convert_svg_barcode_to_others('<?= \Altum\Uploads::get_full_url('barcodes') . $row->barcode ?>', 'webp', '<?= get_slug($row->name) . '.webp' ?>');"><?= sprintf(l('global.download_as'), 'WEBP') ?></button>
                                            </div>
                                        </div>

                                        <?= include_view(THEME_PATH . 'views/barcodes/barcode_dropdown_button.php', ['id' => $row->barcode_id, 'resource_name' => $row->name]) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>

                        <tr>
                            <td colspan="5">
                                <a href="<?= url('barcodes') ?>" class="text-muted">
                                    <i class="fas fa-angle-right fa-sm fa-fw mr-1"></i> <?= l('global.view_all') ?>
                                </a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            <?php else: ?>

                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'barcodes',
                    'has_secondary_text' => true,
                ]); ?>

            <?php endif ?>

        </div>
    <?php endif ?>
</div>

<?php require THEME_PATH . 'views/qr-codes/js_qr_codes.php' ?>
<?php require THEME_PATH . 'views/barcodes/js_barcodes.php' ?>
