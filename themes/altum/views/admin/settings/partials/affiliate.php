<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the affiliate plugin system.
        </div>
    <?php endif ?>

    <div <?= !\Altum\Plugin::is_active('affiliate') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('affiliate')->name ?? 'affiliate') . '"' : null ?>>
        <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) || !\Altum\Plugin::is_active('affiliate') ? 'container-disabled' : null ?>">
            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><?= l('admin_settings.affiliate.is_enabled') ?></label>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.is_enabled_help') ?></small>
            </div>

            <div class="form-group">
                <label for="commission_type"><?= l('admin_settings.affiliate.commission_type') ?></label>
                <select id="commission_type" name="commission_type" class="custom-select">
                    <option value="once" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->commission_type == 'once' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.commission_type_once') ?></option>
                    <option value="forever" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->commission_type == 'forever' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.commission_type_forever') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="tracking_type"><?= l('admin_settings.affiliate.tracking_type') ?></label>
                <select id="tracking_type" name="tracking_type" class="custom-select">
                    <option value="first" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->tracking_type == 'first' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.tracking_type_first') ?></option>
                    <option value="last" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->tracking_type == 'last' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.tracking_type_last') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="tracking_duration"><?= l('admin_settings.affiliate.tracking_duration') ?></label>
                <div class="input-group">
                    <input id="tracking_duration" type="number" min="1" name="tracking_duration" class="form-control" value="<?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->tracking_duration : 30 ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.date.days') ?></span>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.tracking_duration_help') ?></small>
            </div>

            <div class="form-group">
                <label for="minimum_withdrawal_amount"><?= l('admin_settings.affiliate.minimum_withdrawal_amount') ?></label>
                <div class="input-group">
                    <input id="minimum_withdrawal_amount" type="number" min="1" name="minimum_withdrawal_amount" class="form-control" value="<?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->minimum_withdrawal_amount : 1 ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= settings()->payment->default_currency ?></span>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.minimum_withdrawal_amount_help') ?></small>
            </div>

            <div class="form-group">
                <label for="withdrawal_notes"><?= l('admin_settings.affiliate.withdrawal_notes') ?></label>
                <textarea id="withdrawal_notes" name="withdrawal_notes" class="form-control"><?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->withdrawal_notes : null ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.withdrawal_notes_help') ?></small>
            </div>
        </div>
    </div>
</div>

<?php if(\Altum\Plugin::is_active('affiliate')): ?>
    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
<?php endif ?>
