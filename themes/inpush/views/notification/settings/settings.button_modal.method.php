<?php
defined('INPUSH') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Basic Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_name"><?= language()->notification->settings->name ?></label>
        <input type="text" id="settings_name" name="name" class="form-control" value="<?= $data->notification->name ?>" maxlength="256" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_title"><?= language()->notification->settings->title ?></label>
        <input type="text" id="settings_title" name="title" class="form-control" value="<?= $data->notification->settings->title ?>" maxlength="256" />
    </div>

    <div class="form-group">
        <label for="settings_description"><?= language()->notification->settings->description ?></label>
        <input type="text" id="settings_description" name="description" class="form-control" value="<?= $data->notification->settings->description ?>" maxlength="512" />
    </div>

    <div class="form-group">
        <label for="settings_image"><?= language()->notification->settings->image ?></label>
        <input type="url" id="settings_image" name="image" class="form-control" value="<?= $data->notification->settings->image ?>" maxlength="2048" />
        <small class="form-text text-muted"><?= language()->notification->settings->image_help ?></small>
    </div>

    <div class="form-group">
        <label for="settings_image_alt"><?= language()->notification->settings->image_alt ?></label>
        <input type="text" id="settings_image_alt" name="image_alt" class="form-control" value="<?= $data->notification->settings->image_alt ?>" maxlength="100" />
        <small class="form-text text-muted"><?= language()->notification->settings->image_alt_help ?></small>
    </div>

    <div class="form-group">
        <label for="settings_button_text"><?= language()->notification->settings->button_text ?></label>
        <input type="text" id="settings_button_text" name="button_text" class="form-control" value="<?= $data->notification->settings->button_text ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <label for="settings_button_url"><?= language()->notification->settings->button_url ?></label>
        <input type="url" id="settings_button_url" name="button_url" class="form-control" value="<?= $data->notification->settings->button_url ?>" maxlength="2048" />
    </div>
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_title_color"><?= language()->notification->settings->title_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_title_color_pickr"></div>
            </div>
            <input type="text" id="settings_title_color" name="title_color" class="form-control border-left-0" value="<?= $data->notification->settings->title_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_description_color"><?= language()->notification->settings->description_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_description_color_pickr"></div>
            </div>
            <input type="text" id="settings_description_color" name="description_color" class="form-control border-left-0" value="<?= $data->notification->settings->description_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_background_color"><?= language()->notification->settings->background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_background_color" name="background_color" class="form-control border-left-0" value="<?= $data->notification->settings->background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_background_pattern"><?= language()->notification->settings->background_pattern ?></label>
        <select class="form-control" id="settings_background_pattern" name="background_pattern">
            <option value="" <?= $data->notification->settings->background_pattern == '' ? 'selected="selected"' : null ?>><?= language()->notification->settings->background_pattern_none ?></option>

            <?php $background_patterns = (require_once APP_PATH . 'includes/notifications_background_patterns.php')(); ?>

            <?php foreach($background_patterns as $key => $value): ?>
                <option value="<?= $key ?>" <?= $data->notification->settings->background_pattern == $key ? 'selected="selected"' : null ?> data-value="<?= $value ?>"><?= language()->notification->settings->{'background_pattern_' . $key} ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label for="settings_button_background_color"><?= language()->notification->settings->button_background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_button_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_button_background_color" name="button_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->button_background_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_button_color"><?= language()->notification->settings->button_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_button_color_pickr"></div>
            </div>
            <input type="text" id="settings_button_color" name="button_color" class="form-control border-left-0" value="<?= $data->notification->settings->button_color ?>" required="required" />
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_radius"><?= language()->notification->settings->border_radius ?></label>
                <select class="form-control" name="border_radius">
                    <option value="rounded" <?= $data->notification->settings->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_rounded ?></option>
                </select>
                <small class="form-text text-muted"><?= language()->notification->settings->border_radius_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_width"><?= language()->notification->settings->border_width ?></label>
                <input type="number" min="0" max="5" id="settings_border_width" name="border_width" class="form-control" value="<?= $data->notification->settings->border_width ?>" />
                <small class="form-text text-muted"><?= language()->notification->settings->border_width_help ?></small>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_color"><?= language()->notification->settings->border_color ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div id="settings_border_color_pickr"></div>
                    </div>
                    <input type="text" id="settings_border_color" name="border_color" class="form-control border-left-0" value="<?= $data->notification->settings->border_color ?>" required="required" />
                </div>
            </div>
        </div>
    </div>

    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_shadow"
                name="shadow"
                <?= $data->notification->settings->shadow ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_shadow"><?= language()->notification->settings->shadow ?></label>

        <div>
            <small class="form-text text-muted"><?= language()->notification->settings->shadow_help ?></small>
        </div>
    </div>
<?php $html['customize'] = ob_get_clean() ?>

<?php ob_start() ?>
    <script>
        /* Notification Preview Handlers */
        $('#settings_title').on('change paste keyup', event => {
            $('#notification_preview .inpush-button-modal-title').text($(event.currentTarget).val());
        });

        $('#settings_description').on('change paste keyup', event => {
            $('#notification_preview .inpush-button-modal-description').text($(event.currentTarget).val());
        });

        $('#settings_button_text').on('change paste keyup', event => {
            $('#notification_preview .inpush-button-modal-button').text($(event.currentTarget).val());
        });

        $('#settings_image').on('change paste keyup', event => {
            $('#notification_preview .inpush-button-modal-image').attr('src', $(event.currentTarget).val());
        });

        /* Title Color Handler */
        let settings_title_color_pickr = Pickr.create({
            el: '#settings_title_color_pickr',
            default: $('#settings_title_color').val(),
            ...pickr_options
        });

        settings_title_color_pickr.on('change', hsva => {
            $('#settings_title_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-button-modal-title').css('color', hsva.toHEXA().toString());
        });


        /* Description Color Handler */
        let settings_description_color_pickr = Pickr.create({
            el: '#settings_description_color_pickr',
            default: $('#settings_description_color').val(),
            ...pickr_options
        });

        settings_description_color_pickr.on('change', hsva => {
            $('#settings_description_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-button-modal-description').css('color', hsva.toHEXA().toString());
        });


        /* Background Color Handler */
        let settings_background_color_pickr = Pickr.create({
            el: '#settings_background_color_pickr',
            default: $('#settings_background_color').val(),
            ...pickr_options
        });

        settings_background_color_pickr.on('change', hsva => {
            $('#settings_background_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-wrapper').css('background-color', hsva.toHEXA().toString());
        });

        /* Background Pattern Handler */
        $('#settings_background_pattern').on('change paste keyup', event => {
            let value = $(event.currentTarget).find(':selected').data('value');

            if(value) {
                $('#notification_preview .inpush-wrapper').css('background-image', `url(${value})`);
            } else {
                $('#notification_preview .inpush-wrapper').css('background-image', '');
            }
        });

        /* Submit Background Color Handler */
        let settings_button_background_color_pickr = Pickr.create({
            el: '#settings_button_background_color_pickr',
            default: $('#settings_button_background_color').val(),
            ...pickr_options
        });

        settings_button_background_color_pickr.on('change', hsva => {
            $('#settings_button_background_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-button-modal-button').css('background', hsva.toHEXA().toString());
        });

        /* Submit Background Color Handler */
        let settings_button_color_pickr = Pickr.create({
            el: '#settings_button_color_pickr',
            default: $('#settings_button_color').val(),
            ...pickr_options
        });

        settings_button_color_pickr.on('change', hsva => {
            $('#settings_button_color').val(hsva.toHEXA().toString());

            /* Notification Preview Handler */
            $('#notification_preview .inpush-button-modal-button').css('color', hsva.toHEXA().toString());
        });

    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
