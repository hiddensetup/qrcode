<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $data->cloaking && $data->cloaking->cloaking_is_enabled ? $data->cloaking->cloaking_title : null ?></title>

        <base href="<?= SITE_URL; ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(!empty($data->cloaking->cloaking_favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicons') . $data->cloaking->cloaking_favicon ?>" rel="shortcut icon" />
        <?php elseif(!empty(settings()->main->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="shortcut icon" />
        <?php endif ?>
    </head>

    <body>
        <?php if($data->cloaking): ?>
            <iframe id="iframe" src="<?= $data->location_url ?>" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index: 1;"></iframe>
        <?php endif ?>

        <?= count($data->pixels) ? $this->views['pixels'] : null ?>

        <?php if(!$data->cloaking): ?>
            <script>
                <?php if($data->app_linking_location_url): ?>
                window.location = <?= json_encode($data->app_linking_location_url) ?>;
                <?php endif ?>

                setTimeout(() => {
                    window.location = <?= json_encode($data->location_url) ?>;
                }, 650);
            </script>
        <?php endif ?>
    </body>
</html>
