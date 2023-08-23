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
        <label for="settings_input_placeholder"><?= language()->notification->settings->input_placeholder ?></label>
        <input type="text" id="settings_input_placeholder" name="input_placeholder" class="form-control" value="<?= $data->notification->settings->input_placeholder ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <label for="settings_button_text"><?= language()->notification->settings->button_text ?></label>
        <input type="text" id="settings_button_text" name="button_text" class="form-control" value="<?= $data->notification->settings->button_text ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <div class="custom-control custom-switch">
            <input id="settings_show_agreement" name="show_agreement" type="checkbox" class="custom-control-input" <?= $data->notification->settings->show_agreement ? 'checked="checked"' : null ?>>
            <label class="custom-control-label" for="settings_show_agreement"><?= language()->notification->settings->show_agreement ?></label>
            <div><small class="form-text text-muted"><?= language()->notification->settings->show_agreement_help ?></small></div>
        </div>
    </div>

    <div id="agreement">
        <div class="form-group">
            <label for="settings_agreement_text"><?= language()->notification->settings->agreement_text ?></label>
            <input type="text" id="settings_agreement_text" name="agreement_text" class="form-control" value="<?= $data->notification->settings->agreement_text ?>" maxlength="256" />
        </div>

        <div class="form-group">
            <label for="settings_agreement_url"><?= language()->notification->settings->agreement_url ?></label>
            <input type="url" id="settings_agreement_url" name="agreement_url" class="form-control" value="<?= $data->notification->settings->agreement_url ?>" maxlength="2048" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_thank_you_url"><?= language()->notification->settings->thank_you_url ?></label>
        <input type="url" id="settings_thank_you_url" name="thank_you_url" class="form-control" value="<?= $data->notification->settings->thank_you_url ?>" />
        <small class="form-text text-muted"><?= language()->notification->settings->thank_you_url_help ?></small>
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


<?php /* Data Tab */ ?>
<?php ob_start() ?>
    <div class="custom-control custom-switch mr-3 mb-3">
        <input
                type="checkbox"
                class="custom-control-input"
                id="data_send_is_enabled"
                name="data_send_is_enabled"
            <?= $data->notification->settings->data_send_is_enabled ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label clickable" for="data_send_is_enabled"><?= language()->notification->settings->data_send_is_enabled ?></label>
    </div>

    <div id="data_send" class="container-disabled">
        <div class="form-group">
            <label for="settings_data_send_webhook"><?= language()->notification->settings->data_send_webhook ?></label>
            <input type="text" id="settings_data_send_webhook" name="data_send_webhook" class="form-control" value="<?= $data->notification->settings->data_send_webhook ?>" maxlength="2048" placeholder="<?= language()->notification->settings->data_send_webhook_placeholder ?>" aria-label="<?= language()->notification->settings->data_send_webhook_placeholder ?>" />
            <small class="form-text text-muted"><?= language()->notification->settings->data_send_webhook_help ?></small>
        </div>

        <div class="form-group">
            <label for="settings_data_send_email"><?= language()->notification->settings->data_send_email ?></label>
            <input type="text" id="settings_data_send_email" name="data_send_email" class="form-control" value="<?= $data->notification->settings->data_send_email ?>" maxlength="320"  placeholder="<?= language()->notification->settings->data_send_email_placeholder ?>" aria-label="<?= language()->notification->settings->data_send_email_placeholder ?>" />
            <small class="form-text text-muted"><?= language()->notification->settings->data_send_email_help ?></small>
        </div>
    </div>

<?php $html['data'] = ob_get_clean() ?>


<?php ob_start() ?>
    <script>
        /* Dont show the agreement fields if unchecked */
        let show_agreement_check = () => {
            if($('#settings_show_agreement').is(':checked')) {
                $('#agreement').show();
            } else {
                $('#agreement').hide();
            }
        };
        show_agreement_check();
        $('#settings_show_agreement').on('change', show_agreement_check);

        /* Cancel the submit button form of the email collector */
        $('#inpush-collector-modal-form').on('submit', event => event.preventDefault());

        /* Notification Preview Handlers */
        $('#settings_title').on('change paste keyup', event => {
            $('#notification_preview .inpush-collector-modal-title').text($(event.currentTarget).val());
        });

        $('#settings_description').on('change paste keyup', event => {
            $('#notification_preview .inpush-collector-modal-description').text($(event.currentTarget).val());
        });

        $('#settings_input_placeholder').on('change paste keyup', event => {
            $('#notification_preview [name="input"]').attr('placeholder', $(event.currentTarget).val());
        });

        $('#settings_button_text').on('change paste keyup', event => {
            $('#notification_preview [name="button"]').text($(event.currentTarget).val());
        });

        $('#settings_image').on('change paste keyup', event => {
            $('#notification_preview .inpush-collector-modal-image-holder').css('background-image', `url('${$(event.currentTarget).val()}')`);
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
            $('#notification_preview .inpush-collector-modal-title').css('color', hsva.toHEXA().toString());
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
            $('#notification_preview .inpush-collector-modal-description').css('color', hsva.toHEXA().toString());
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
            $('#notification_preview [name="button"]').css('background', hsva.toHEXA().toString());
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
            $('#notification_preview [name="button"]').css('color', hsva.toHEXA().toString());
        });

        /* Data Send Handler */
        let data_send_status_handler = () => {

            if($('#data_send_is_enabled:checked').length > 0) {

                /* Remove disabled container if depending on the status of the trigger checkbox */
                $('#data_send').removeClass('container-disabled');

            } else {

                /* Disable the container visually */
                $('#data_send').addClass('container-disabled');

            }
        };

        /* Trigger it for the first initial load */
        data_send_status_handler();

        /* Trigger on status change live of the checkbox */
        $('#data_send_is_enabled').on('change', data_send_status_handler);
    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
