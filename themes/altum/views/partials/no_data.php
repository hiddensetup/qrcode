<?php defined('ALTUMCODE') || die() ?>

<?php if($data->has_wrapper ?? true): ?>
<div class="card">
    <div class="card-body">
        <?php endif ?>

        <?php if(count($data->filters_get)): ?>

            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                <?= sprintf(file_get_contents(ROOT_PATH . ASSETS_URL_PATH . 'images/no_filtered_data.svg'), 'var(--primary)', 'col-10 col-md-7 col-lg-4 mb-4') ?>

                <h2 class="h4 text-muted text-center"><?= l('global.filters.no_data') ?></h2>
                <p class="text-muted text-center mb-3"><?= l('global.filters.no_data_help') ?></p>

                <a href="<?= url(\Altum\Router::$original_request) ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-fw fa-sm fa-eraser mr-1"></i> <?= l('global.filters.clear') ?>
                </a>
            </div>

        <?php else: ?>

            <div class="d-flex flex-column align-items-center justify-content-center py-4">
                <?= sprintf(file_get_contents(ROOT_PATH . ASSETS_URL_PATH . 'images/no_data.svg'), 'var(--primary)', 'col-10 col-md-7 col-lg-4 mb-4') ?>

                <h2 class="h4 text-muted text-center"><?= l($data->name . '.no_data') ?></h2>

                <?php if($data->has_secondary_text): ?>
                    <p class="text-muted text-center m-0"><?= l($data->name . '.no_data_help') ?></p>
                <?php endif ?>
            </div>

        <?php endif ?>

        <?php if($data->has_wrapper ?? true): ?>
    </div>
</div>
<?php endif ?>
