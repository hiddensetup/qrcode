<?php defined('ALTUMCODE') || die() ?>

<div>
    <ul class="nav nav-pills d-flex flex-fill flex-column flex-lg-row mb-3" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link active" id="pills-guests-tab" data-toggle="pill" href="#pills-guests" role="tab" aria-controls="pills-home" aria-selected="true"><?= l('admin_settings.announcements.guests') ?></a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab" aria-controls="pills-users" aria-selected="false"><?= l('admin_settings.announcements.users') ?></a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-guests" role="tabpanel" aria-labelledby="pills-guests-tab">
            <div class="form-group">
                <label for="guests_content"><?= l('admin_settings.announcements.content') ?></label>
                <textarea id="guests_content" name="guests_content" class="form-control"><?= settings()->announcements->guests_content ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.announcements.content_help') ?></small>
            </div>

            <div class="form-group">
                <label for="guests_text_color"><?= l('admin_settings.announcements.text_color') ?></label>
                <input id="guests_text_color" type="hidden" name="guests_text_color" class="form-control" value="<?= settings()->announcements->guests_text_color ?>" data-color-picker />
            </div>

            <div class="form-group">
                <label for="guests_background_color"><?= l('admin_settings.announcements.background_color') ?></label>
                <input id="guests_background_color" type="hidden" name="guests_background_color" class="form-control" value="<?= settings()->announcements->guests_background_color ?>" data-color-picker />
            </div>
        </div>

        <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            <div class="form-group">
                <label for="users_content"><?= l('admin_settings.announcements.content') ?></label>
                <textarea id="users_content" name="users_content" class="form-control"><?= settings()->announcements->users_content ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.announcements.content_help') ?></small>
            </div>

            <div class="form-group">
                <label for="users_text_color"><?= l('admin_settings.announcements.text_color') ?></label>
                <input id="users_text_color" type="hidden" name="users_text_color" class="form-control" value="<?= settings()->announcements->users_text_color ?>" data-color-picker />
            </div>

            <div class="form-group">
                <label for="users_background_color"><?= l('admin_settings.announcements.background_color') ?></label>
                <input id="users_background_color" type="hidden" name="users_background_color" class="form-control" value="<?= settings()->announcements->users_background_color ?>" data-color-picker />
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
