<?php defined('ALTUMCODE') || die() ?>

<?php if(count(\Altum\Plugin::$plugins)): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-puzzle-piece text-primary-900 mr-2"></i> <?= l('admin_plugins.header') ?></h1>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('admin_plugins.table.plugin') ?></th>
                <th><?= l('admin_plugins.table.version') ?></th>
                <th><?= l('admin_plugins.table.author') ?></th>
                <th><?= l('global.status') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach(\Altum\Plugin::$plugins as $plugin): ?>

                <tr>
                    <td>
                        <div class="d-flex">
                            <div class="user-avatar rounded-circle mr-3 d-flex justify-content-center align-items-center" style="<?= $plugin->avatar_style ?? null ?>">
                                <?= $plugin->icon ?? null ?>
                            </div>

                            <div class="d-flex flex-column">
                                <div>
                                    <span><?= $plugin->name ?></span>
                                    <a href="<?= $plugin->url ?>" target="_blank" rel="nofollow noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                                </div>

                                <small class="text-muted" data-toggle="tooltip" title="<?= $plugin->description ?>"><?= string_truncate($plugin->description, 90) ?></small>
                            </div>
                        </div>
                    </td>

                    <td class="text-nowrap">
                        <span class="badge badge-info">
                            <?= 'v' . $plugin->version ?>
                        </span>
                    </td>

                    <td class="text-nowrap">
                        <a href="<?= $plugin->author_url ?>" class="badge badge-gray-200" target="_blank" rel="nofollow noreferrer">
                            <i class="fas fa-fw fa-sm fa-user mr-1"></i>
                            <?= $plugin->author ?>
                        </a>
                    </td>

                    <td class="text-nowrap">
                        <?php if($plugin->status === -2 || $plugin->status == 'inexistent'): ?>
                            <a href="<?= $plugin->url ?>" target="_blank" rel="nofollow noreferrer" class="btn btn-sm btn-success">
                                <i class="fas fa-fw fa-sm fa-download mr-1"></i>
                                <?= l('admin_plugins.status_inexistent') ?>
                            </a>
                        <?php elseif($plugin->status === -1 || $plugin->status == 'uninstalled'): ?>
                            <span class="badge badge-light">
                                <i class="fas fa-fw fa-sm fa-times mr-1"></i> <?= l('admin_plugins.status_uninstalled') ?>
                            </span>
                        <?php elseif($plugin->status === 0 || $plugin->status == 'installed'): ?>
                            <span class="badge badge-secondary">
                                <i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i>
                                <?= l('admin_plugins.status_disabled') ?>
                            </span>
                        <?php elseif($plugin->status === 1 || $plugin->status == 'active'): ?>
                            <span class="badge badge-success">
                                <i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('admin_plugins.status_active') ?>
                            </span>
                        <?php endif ?>
                    </td>

                    <td>
                        <div class="d-flex justify-content-end">
                            <?php if($plugin->actions && ($plugin->status !== -2 && $plugin->status != 'inexistent')): ?>
                                <?= include_view(THEME_PATH . 'views/admin/plugins/admin_plugin_dropdown_button.php', ['id' => $plugin->plugin_id, 'status' => $plugin->status, 'settings_url' => $plugin->settings_url ?? null]) ?>
                            <?php endif ?>
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
            <i class="fas fa-fw fa-7x fa-puzzle-piece text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3 m-0"><?= l('admin_plugins.header_no_data') ?></h1>
            <p class="text-muted"><?= l('admin_plugins.subheader_no_data') ?></p>

        </div>
    </div>

<?php endif ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/plugins/plugin_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/plugins/plugin_uninstall_modal.php'), 'modals'); ?>
