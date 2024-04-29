<?php defined('ALTUMCODE') || die() ?>

<div>
    <p class="text-muted"><?= l('admin_settings.cache.help') ?></p>

    <div class="form-group">
        <label for="size"><?= l('admin_settings.cache.size') ?></label>
        <div class="input-group">
            <input id="size" name="size" type="text" class="form-control" value="<?= cache()->getStats()->getSize() / 1000 / 1000 ?>" readonly="readonly" />
            <div class="input-group-append">
                <span class="input-group-text">
                    MB
                </span>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('admin_settings.cache.clear') ?></button>
