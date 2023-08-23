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
        <label for="settings_url"><?= language()->notification->settings->url ?></label>
        <input type="url" id="settings_url" name="url" class="form-control" value="<?= $data->notification->settings->url ?>" maxlength="2048" />
        <small class="form-text text-muted"><?= language()->notification->settings->url_help ?></small>
    </div>

    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_url_new_tab"
                name="url_new_tab"
            <?= $data->notification->settings->url_new_tab ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_url_new_tab"><?= language()->notification->settings->url_new_tab ?></label>

        <div>
            <small class="form-text text-muted"><?= language()->notification->settings->url_new_tab_help ?></small>
        </div>
    </div>

    <div class="form-group">
        <label for="settings_reviews_count"><?= language()->notification->settings->reviews_count ?></label>
        <input type="text" id="settings_reviews_count" name="reviews_count" class="form-control" value="<?= $data->notification->settings->reviews_count ?>" />
        <small class="form-text text-muted"><?= language()->notification->settings->reviews_count_help ?></small>
    </div>

    <div class="form-group">
        <label for="settings_in_between_delay"><?= language()->notification->settings->in_between_delay ?></label>
        <input type="text" id="settings_in_between_delay" name="in_between_delay" class="form-control" value="<?= $data->notification->settings->in_between_delay ?>" />
        <small class="form-text text-muted"><?= language()->notification->settings->in_between_delay_help ?></small>
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

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
                <label for="settings_border_radius"><?= language()->notification->settings->border_radius ?></label>
                <select class="form-control" name="border_radius">
                    <option value="straight" <?= $data->notification->settings->border_radius == 'straight' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_straight ?></option>
                    <option value="rounded" <?= $data->notification->settings->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_rounded ?></option>
                    <option value="round" <?= $data->notification->settings->border_radius == 'round' ? 'selected="selected"' : null ?>><?= language()->notification->settings->border_radius_round ?></option>
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
        $('#notification_preview .inpush-random-review-title').text($(event.currentTarget).val());
    });

    $('#settings_description').on('change paste keyup', event => {
        $('#notification_preview .inpush-random-review-description').text($(event.currentTarget).val());
    });

    $('#settings_image').on('change paste keyup', event => {
        $('#notification_preview .inpush-random-review-image').attr('src', $(event.currentTarget).val());
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
        $('#notification_preview .inpush-random-review-title').css('color', hsva.toHEXA().toString());
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
        $('#notification_preview .inpush-random-review-description').css('color', hsva.toHEXA().toString());
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
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
