<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="from_name"><?= l('admin_settings.smtp.from_name') ?></label>
        <input id="from_name" type="text" name="from_name" class="form-control" value="<?= settings()->smtp->from_name ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.from_name_help') ?></small>
    </div>

    <div class="form-group">
        <label for="from"><?= l('admin_settings.smtp.from') ?></label>
        <input id="from" type="text" name="from" class="form-control" value="<?= settings()->smtp->from ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.from_help') ?></small>
    </div>

    <div class="form-group">
        <label for="host"><?= l('admin_settings.smtp.host') ?></label>
        <input id="host" type="text" name="host" class="form-control" value="<?= settings()->smtp->host ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.host_help') ?></small>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="encryption"><?= l('admin_settings.smtp.encryption') ?></label>
                <select id="encryption" name="encryption" class="custom-select" autocomplete="off">
                    <option value="0" <?= settings()->smtp->encryption == '0' ? 'selected="selected"' : null ?>>None</option>
                    <option value="ssl" <?= settings()->smtp->encryption == 'ssl' ? 'selected="selected"' : null ?>>SSL</option>
                    <option value="tls" <?= settings()->smtp->encryption == 'tls' ? 'selected="selected"' : null ?>>TLS</option>
                </select>
            </div>
        </div>

        <div class="col-md-9">
            <div class="form-group">
                <label for="port"><?= l('admin_settings.smtp.port') ?></label>
                <input id="port" type="text" name="port" class="form-control" value="<?= settings()->smtp->port ?>" autocomplete="off" />
            </div>
        </div>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="auth" name="auth" type="checkbox" class="custom-control-input" <?= settings()->smtp->auth ? 'checked="checked"' : null ?> autocomplete="off">
        <label class="custom-control-label" for="auth"><?= l('admin_settings.smtp.auth') ?></label>
    </div>

    <div class="form-group">
        <label for="username"><?= l('admin_settings.smtp.username') ?></label>
        <input id="username" type="text" name="username" class="form-control" value="<?= settings()->smtp->username ?>" autocomplete="off" />
    </div>

    <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
        <label for="password"><?= l('admin_settings.smtp.password') ?></label>
        <input id="password" type="password" name="password" class="form-control" value="<?= settings()->smtp->password ?>" autocomplete="off" />
    </div>

    <div class="my-3">
        <button type="button" class="btn btn-block btn-outline-info" data-toggle="modal" data-target="#settings_send_test_email_modal"><?= l('admin_settings_send_test_email_modal.header') ?></button>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="display_socials" name="display_socials" type="checkbox" class="custom-control-input" <?= settings()->smtp->display_socials ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="display_socials"><i class="fab fa-fw fa-sm fa-instagram text-muted mr-1"></i> <?= l('admin_settings.smtp.display_socials') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.smtp.display_socials_help') ?></small>
    </div>

    <div class="form-group">
        <label for="company_details"><?= l('admin_settings.smtp.company_details') ?></label>
        <textarea id="company_details" name="company_details" class="form-control" autocomplete="off"><?= settings()->smtp->company_details ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.smtp.company_details_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php ob_start() ?>
<script>
    'use strict';

    /* SMTP */
    let auth_handler = () => {
        if(document.querySelector('input[name="auth"]').checked) {
            document.querySelector('input[name="username"]').removeAttribute('readonly');
            document.querySelector('input[name="password"]').removeAttribute('readonly');
        } else {
            document.querySelector('input[name="username"]').setAttribute('readonly', 'readonly');
            document.querySelector('input[name="password"]').setAttribute('readonly', 'readonly');
        }
    }

    auth_handler();
    document.querySelector('input[name="auth"]').addEventListener('change', auth_handler);

    /* Disable send test email if the smtp fields change & are not saved */
    let fields = {};

    document.querySelectorAll('input').forEach(element => {
        fields[element.name] = element.value;

        element.addEventListener('change', event => {
            if(fields[element.name] !== element.value) {
                document.querySelector('[data-target="#settings_send_test_email_modal"]').classList.add('disabled');
                document.querySelector('[data-target="#settings_send_test_email_modal"]').setAttribute('disabled', 'disabled');
            }
        }) ;
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/settings/settings_send_test_email_modal.php', ['email' => $this->user->email]), 'modals'); ?>
