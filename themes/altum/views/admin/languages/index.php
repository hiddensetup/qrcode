<?php defined('ALTUMCODE') || die() ?>

<?php if(count(\Altum\Language::$languages)): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-language text-primary-900 mr-2"></i> <?= l('admin_languages.header') ?></h1>

        <div class="d-flex position-relative">
            <div>
                <a href="<?= url('admin/language-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_language_create.menu') ?></a>
            </div>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('admin_languages.main.language_name') ?></th>
                <th><?= l('admin_languages.main.language_code') ?></th>
                <th><?= l('global.order') ?></th>
                <th><?= l('global.status') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach(\Altum\Language::$languages as $language): ?>

                <tr>
                    <td>
                        <?php if($language['language_flag']): ?>
                            <span class="mr-2"><?= $language['language_flag'] ?></span>
                        <?php endif ?>

                        <a href="<?= url('admin/language-update/' . replace_space_with_plus($language['name'])) ?>"><?= $language['name'] ?></a>

                        <?php if($language['name'] == settings()->main->default_language): ?>
                            <span class="mx-1 badge badge-success"><?= l('admin_languages.main.default_language') ?></span>
                        <?php endif ?>

                        <?php if($language['name'] == \Altum\Language::$main_name): ?>
                            <span class="mx-1 badge badge-info"><?= l('admin_languages.main.main') ?></span>
                        <?php endif ?>
                    </td>

                    <td>
                        <span class="badge badge-light"><i class="fas fa-fw fa-sm fa-language mr-1"></i> <?= $language['code'] ?></span>
                    </td>

                    <td>
                        <?= nr($language['order']) ?>
                    </td>

                    <td>
                        <?php if((settings()->languages->{$language['name']}->status ?? $language['status']) == 'disabled'): ?>
                            <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                        <?php elseif((settings()->languages->{$language['name']}->status ?? $language['status']) == 'active'): ?>
                            <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                        <?php endif ?>
                    </td>

                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/languages/admin_language_dropdown_button.php', ['id' => $language['name'], 'resource_name' => $language['name']]) ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach ?>
            </tbody>
        </table>
    </div>

<?php else: ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center">
        <div class="mb-3 mb-md-0 mr-md-5">
            <i class="fas fa-fw fa-7x fa-language text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3 m-0"><?= l('admin_languages.header_no_data') ?></h1>
        </div>
    </div>

<?php endif ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'language',
    'resource_id' => 'language_name',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/languages/delete/'
]), 'modals'); ?>
