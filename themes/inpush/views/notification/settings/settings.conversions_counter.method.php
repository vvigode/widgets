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
        <label for="settings_last_activity"><?= sprintf(language()->notification->settings->last_activity, language()->global->date->hours) ?></label>
        <input type="number" id="settings_last_activity" name="last_activity" class="form-control" value="<?= $data->notification->settings->last_activity ?>" required="required" />
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
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Triggers Tab Extra */ ?>
<?php ob_start() ?>

<div class="form-group">
    <label for="settings_display_minimum_activity"><?= language()->notification->settings->display_minimum_activity ?></label>
    <input type="number" min="0" id="settings_display_minimum_activity" name="display_minimum_activity" class="form-control" value="<?= $data->notification->settings->display_minimum_activity ?>" />
    <small class="form-text text-muted"><?= language()->notification->settings->display_minimum_activity_help ?></small>
</div>

<?php $html['triggers'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_number_color"><?= language()->notification->settings->number_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_number_color_pickr"></div>
            </div>
            <input type="text" id="settings_number_color" name="number_color" class="form-control border-left-0" value="<?= $data->notification->settings->number_color ?>" required="required" />
        </div>
    </div>

    <div class="form-group">
        <label for="settings_number_background_color"><?= language()->notification->settings->number_background_color ?></label>
        <div class="input-group">
            <div class="input-group-prepend">
                <div id="settings_number_background_color_pickr"></div>
            </div>
            <input type="text" id="settings_number_background_color" name="number_background_color" class="form-control border-left-0" value="<?= $data->notification->settings->number_background_color ?>" required="required" />
        </div>
    </div>

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



<?php /* Data Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_data_trigger_input_webhook"><?= language()->notification->settings->data_trigger_webhook ?></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><?= language()->notification->settings->data_trigger_type_webhook ?></span>
        </div>

        <input type="text" id="settings_data_trigger_input_webhook" name="data_trigger_input_webhook" class="form-control" value="<?= url('pixel-webhook/' . $data->notification->notification_key) ?>" placeholder="<?= language()->notification->settings->data_trigger_input_webhook ?>" aria-label="<?= language()->notification->settings->data_trigger_input_webhook ?>" readonly="readonly">
    </div>

    <small class="form-text text-muted"><?= language()->notification->settings->data_trigger_webhook_help ?></small>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="data_trigger_auto"
                name="data_trigger_auto"
            <?= $data->notification->settings->data_trigger_auto ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label clickable" for="data_trigger_auto"><?= language()->notification->settings->data_trigger_auto ?></label>

        <div><small class="form-text text-muted"><?= language()->notification->settings->data_trigger_auto_help ?></small></div>
    </div>
</div>

<div id="data_triggers_auto" class="container-disabled">
    <?php if(count($data->notification->settings->data_triggers_auto)): ?>
        <?php foreach($data->notification->settings->data_triggers_auto as $trigger): ?>
            <div class="input-group mb-3">
                <select class="form-control trigger-type-select" name="data_trigger_auto_type[]">
                    <option value="exact" <?= $trigger->type == 'exact' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_exact ?></option>
                    <option value="contains" <?= $trigger->type == 'contains' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_contains ?></option>
                    <option value="starts_with" <?= $trigger->type == 'starts_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_starts_with ?></option>
                    <option value="ends_with" <?= $trigger->type == 'ends_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_ends_with ?></option>
                    <option value="page_contains" <?= $trigger->type == 'page_contains' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_page_contains ?></option>
                </select>

                <input type="text" name="data_trigger_auto_value[]" class="form-control" value="<?= $trigger->value ?>" placeholder="<?= language()->notification->settings->trigger_type_exact_placeholder ?>" aria-label="<?= language()->notification->settings->trigger_type_exact_placeholder ?>">

                <button type="button" class="data-trigger-auto-delete ml-3 btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i></button>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div>
    <button type="button" id="data_trigger_auto_add" class="btn btn-outline-success btn-sm"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->notification->settings->data_trigger_auto_add ?></button>
</div>
<?php $html['data'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .inpush-conversions-counter-title').text($(event.currentTarget).val());
    });

    $('#settings_description').on('change paste keyup', event => {
        $('#notification_preview .inpush-conversions-counter-description').text($(event.currentTarget).val());
    });

    $('#settings_image').on('change paste keyup', event => {
        $('#notification_preview .inpush-conversions-counter-image').attr('src', $(event.currentTarget).val());
    });

    /* Number Color Handler */
    let settings_number_color_pickr = Pickr.create({
        el: '#settings_number_color_pickr',
        default: $('#settings_number_color').val(),
        ...pickr_options
    });

    settings_number_color_pickr.on('change', hsva => {
        $('#settings_number_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .inpush-conversions-counter-number').css('color', hsva.toHEXA().toString());
    });

    /* Number Background Color Handler */
    let settings_number_background_color_pickr = Pickr.create({
        el: '#settings_number_background_color_pickr',
        default: $('#settings_number_background_color').val(),
        ...pickr_options
    });

    settings_number_background_color_pickr.on('change', hsva => {
        $('#settings_number_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .inpush-conversions-counter-number').css('background', hsva.toHEXA().toString());
    });


    /* Description Color Handler */
    let settings_title_color_pickr = Pickr.create({
        el: '#settings_title_color_pickr',
        default: $('#settings_title_color').val(),
        ...pickr_options
    });

    settings_title_color_pickr.on('change', hsva => {
        $('#settings_title_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .inpush-conversions-counter-title').css('color', hsva.toHEXA().toString());
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

    /* Data Triggers Auto Handler */
    let data_trigger_auto_status_handler = () => {

        if(!$('#data_trigger_auto:checked').length) {

            /* Disable the container visually */
            $('#data_triggers_auto').addClass('container-disabled');

            /* Remove the new trigger add button */
            $('#data_trigger_auto_add').hide();

        } else {

            /* Remove disabled container if depending on the status of the trigger checkbox */
            $('#data_triggers_auto').removeClass('container-disabled');

            /* Bring back the new trigger add button */
            $('#data_trigger_auto_add').show();

        }
    };

    /* Trigger on status change live of the checkbox */
    $('#data_trigger_auto').on('change', data_trigger_auto_status_handler);

    /* Delete trigger handler */
    let data_triggers_auto_delete_handler = () => {

        /* Delete button handler */
        $('.data-trigger-auto-delete').off().on('click', event => {

            let element = $(event.currentTarget).closest('.input-group');

            element.remove();

            data_triggers_auto_count_handler();

        });

    };

    let data_trigger_auto_add_sample = () => {
        let rule_sample = $('#data_trigger_auto_rule_sample').html();

        $('#data_triggers_auto').append(rule_sample);
    };

    let data_triggers_auto_count_handler = () => {

        let total_triggers = $('#data_triggers_auto > .input-group').length;

        /* Make sure we at least have two input groups to show the delete button */
        if(total_triggers > 1) {
            $('#data_triggers_auto .data-trigger-auto-delete').show();

            /* Make sure to set a limit to these triggers */
            if(total_triggers > 10) {
                $('#data_trigger_auto_add').hide();
            } else {
                $('#data_trigger_auto_add').show();
            }

        } else {

            if(total_triggers == 0) {
                data_trigger_auto_add_sample();
            }

            $('#data_triggers_auto .data-trigger-auto-delete').hide();
        }
    };

    /* Add new trigger rule handler */
    $('#data_trigger_auto_add').on('click', () => {
        data_trigger_auto_add_sample();
        data_triggers_auto_delete_handler();
        data_triggers_auto_count_handler();
    });

    /* Trigger functions for the first initial load */
    data_trigger_auto_status_handler();
    data_triggers_auto_delete_handler();
    data_triggers_auto_count_handler();
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
