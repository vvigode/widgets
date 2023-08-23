<?php defined('INPUSH') || die() ?>

<?php

$notification_settings_default_html = [];

/* Include the extra content of the notification */
$settings = require THEME_PATH . 'views/notification/settings/settings.' . mb_strtolower($data->notification->type) . '.method.php';
?>


<?php /* Default Triggers Tab */ ?>
<?php ob_start() ?>
<div class="custom-control custom-switch mr-3 mb-3">
    <input
            type="checkbox"
            class="custom-control-input"
            id="trigger_all_pages"
            name="trigger_all_pages"
        <?= $data->notification->settings->trigger_all_pages ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="trigger_all_pages"><?= language()->notification->settings->trigger_all_pages ?></label>

    <div>
        <small class="form-text text-muted"><?= language()->notification->settings->trigger_all_pages_help ?></small>
    </div>
</div>

<div id="triggers" class="container-disabled">
    <?php if(count($data->notification->settings->triggers)): ?>
        <?php foreach($data->notification->settings->triggers as $trigger): ?>
            <div class="input-group mb-3">
                <select class="form-control trigger-type-select" name="trigger_type[]">
                    <option value="exact" data-placeholder="<?= language()->notification->settings->trigger_type_exact_placeholder ?>" <?= $trigger->type == 'exact' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_exact ?></option>
                    <option value="not_exact" data-placeholder="<?= language()->notification->settings->trigger_type_not_exact_placeholder ?>" <?= $trigger->type == 'not_exact' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_not_exact ?></option>
                    <option value="contains" data-placeholder="<?= language()->notification->settings->trigger_type_contains_placeholder ?>" <?= $trigger->type == 'contains' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_contains ?></option>
                    <option value="not_contains" data-placeholder="<?= language()->notification->settings->trigger_type_not_contains_placeholder ?>" <?= $trigger->type == 'not_contains' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_not_contains ?></option>
                    <option value="starts_with" data-placeholder="<?= language()->notification->settings->trigger_type_starts_with_placeholder ?>" <?= $trigger->type == 'starts_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_starts_with ?></option>
                    <option value="not_starts_with" data-placeholder="<?= language()->notification->settings->trigger_type_not_starts_with_placeholder ?>" <?= $trigger->type == 'not_starts_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_not_starts_with ?></option>
                    <option value="ends_with" data-placeholder="<?= language()->notification->settings->trigger_type_ends_with_placeholder ?>" <?= $trigger->type == 'ends_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_ends_with ?></option>
                    <option value="not_ends_with" data-placeholder="<?= language()->notification->settings->trigger_type_not_ends_with_placeholder ?>" <?= $trigger->type == 'not_ends_with' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_not_ends_with ?></option>
                    <option value="page_contains" data-placeholder="<?= language()->notification->settings->trigger_type_page_contains_placeholder ?>" <?= $trigger->type == 'page_contains' ? 'selected="selected"' : null ?>><?= language()->notification->settings->trigger_type_page_contains ?></option>
                </select>

                <input type="text" name="trigger_value[]" class="form-control" value="<?= $trigger->value ?>">

                <button type="button" class="trigger-delete ml-3 btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i></button>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div class="mb-3">
    <button type="button" id="trigger_add" class="btn btn-outline-success btn-sm"><i class="fa fa-fw fa-plus-circle"></i> <?= language()->notification->settings->trigger_add ?></button>
</div>

<div class="form-group" id="display_trigger">
    <label><?= language()->notification->settings->display_trigger ?></label>

    <div class="input-group">
        <select class="form-control trigger-type-select" name="display_trigger">
            <option value="delay" data-placeholder="<?= language()->notification->settings->display_trigger_delay_placeholder ?>" <?= $data->notification->settings->display_trigger == 'delay' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_trigger_delay ?></option>
            <option value="exit_intent" <?= $data->notification->settings->display_trigger == 'exit_intent' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_trigger_exit_intent ?></option>
            <option value="scroll" data-placeholder="<?= language()->notification->settings->display_trigger_scroll_placeholder ?>" <?= $data->notification->settings->display_trigger == 'scroll' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_trigger_scroll ?></option>
        </select>

        <input type="number" min="0" name="display_trigger_value" class="form-control" value="<?= $data->notification->settings->display_trigger_value ?>" />
    </div>

    <small class="form-text text-muted"><?= language()->notification->settings->display_trigger_help ?></small>
</div>

<div class="form-group">
    <label for="settings_display_frequency"><?= language()->notification->settings->display_frequency ?></label>
    <select class="form-control" name="display_frequency">
        <option value="all_time" <?= $data->notification->settings->display_frequency == 'all_time' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_frequency_all_time ?></option>
        <option value="once_per_session" <?= $data->notification->settings->display_frequency == 'once_per_session' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_frequency_once_per_session ?></option>
        <option value="once_per_browser" <?= $data->notification->settings->display_frequency == 'once_per_browser' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_frequency_once_per_browser ?></option>
    </select>
    <small class="form-text text-muted"><?= language()->notification->settings->display_frequency_help ?></small>
</div>

<div class="custom-control custom-switch mr-3 mb-3">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_mobile"
            name="display_mobile"
        <?= $data->notification->settings->display_mobile ? 'checked="checked"' : null ?>
    >

    <label class="custom-control-label clickable" for="display_mobile"><i class="fa fa-fw fa-sm fa-mobile text-muted mr-1"></i> <?= language()->notification->settings->display_mobile ?></label>

    <div>
        <small class="form-text text-muted"><?= language()->notification->settings->display_mobile_help ?></small>
    </div>
</div>

<div class="custom-control custom-switch mr-3 mb-3">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_desktop"
            name="display_desktop"
            <?= $data->notification->settings->display_desktop ? 'checked="checked"' : null ?>
    >

    <label class="custom-control-label clickable" for="display_desktop"><i class="fa fa-fw fa-sm fa-desktop text-muted mr-1"></i> <?= language()->notification->settings->display_desktop ?></label>

    <div>
        <small class="form-text text-muted"><?= language()->notification->settings->display_desktop_help ?></small>
    </div>
</div>
<?php $notification_settings_default_html['triggers'] = ob_get_clean() ?>


<?php /* Default Display Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_display_duration"><?= language()->notification->settings->display_duration ?></label>
    <input type="number" min="-1" id="settings_display_duration" name="display_duration" class="form-control" value="<?= $data->notification->settings->display_duration ?>" required="required" />
    <small class="form-text text-muted"><?= language()->notification->settings->display_duration_help ?></small>
</div>

<div class="form-group">
    <label for="settings_display_position"><?= language()->notification->settings->display_position ?></label>
    <select class="form-control" name="display_position">
        <option value="top_left" <?= $data->notification->settings->display_position == 'top_left' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_top_left ?></option>
        <option value="top_center" <?= $data->notification->settings->display_position == 'top_center' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_top_center ?></option>
        <option value="top_right" <?= $data->notification->settings->display_position == 'top_right' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_top_right ?></option>
        <option value="middle_left" <?= $data->notification->settings->display_position == 'middle_left' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_middle_left ?></option>
        <option value="middle_center" <?= $data->notification->settings->display_position == 'middle_center' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_middle_center ?></option>
        <option value="middle_right" <?= $data->notification->settings->display_position == 'middle_right' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_middle_right ?></option>
        <option value="bottom_left" <?= $data->notification->settings->display_position == 'bottom_left' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_bottom_left ?></option>
        <option value="bottom_center" <?= $data->notification->settings->display_position == 'bottom_center' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_bottom_center ?></option>
        <option value="bottom_right" <?= $data->notification->settings->display_position == 'bottom_right' ? 'selected="selected"' : null ?>><?= language()->notification->settings->display_position_bottom_right ?></option>
    </select>
    <small class="form-text text-muted"><?= language()->notification->settings->display_position_help ?></small>
</div>

<div class="custom-control custom-switch mr-3 mb-3">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_close_button"
            name="display_close_button"
        <?= $data->notification->settings->display_close_button ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_close_button"><?= language()->notification->settings->display_close_button ?></label>
</div>

<div class="custom-control custom-switch mr-3 mb-3 <?= !$this->user->plan_settings->removable_branding ? 'container-disabled': null ?>">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_branding"
            name="display_branding"
        <?= $data->notification->settings->display_branding ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_branding"><?= language()->notification->settings->display_branding ?></label>
</div>
<?php $notification_settings_default_html['display'] = ob_get_clean() ?>


<?php /* Standard Customize Tab */ ?>
<?php ob_start() ?>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="settings_on_animation"><?= language()->notification->settings->on_animation ?></label>
            <select class="form-control" name="on_animation">
                <option value="fadeIn" <?= $data->notification->settings->on_animation == 'fadeIn' ? 'selected="selected"' : null ?>><?= language()->notification->settings->on_animation_fadeIn ?></option>
                <option value="slideInUp" <?= $data->notification->settings->on_animation == 'slideInUp' ? 'selected="selected"' : null ?>><?= language()->notification->settings->on_animation_slideInUp ?></option>
                <option value="slideInDown" <?= $data->notification->settings->on_animation == 'slideInDown' ? 'selected="selected"' : null ?>><?= language()->notification->settings->on_animation_slideInDown ?></option>
                <option value="zoomIn" <?= $data->notification->settings->on_animation == 'zoomIn' ? 'selected="selected"' : null ?>><?= language()->notification->settings->on_animation_zoomIn ?></option>
                <option value="bounceIn" <?= $data->notification->settings->on_animation == 'bounceIn' ? 'selected="selected"' : null ?>><?= language()->notification->settings->on_animation_bounceIn ?></option>
            </select>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="settings_off_animation"><?= language()->notification->settings->off_animation ?></label>
            <select class="form-control" name="off_animation">
                <option value="fadeOut" <?= $data->notification->settings->off_animation == 'fadeOut' ? 'selected="selected"' : null ?>><?= language()->notification->settings->off_animation_fadeOut ?></option>
                <option value="slideOutUp" <?= $data->notification->settings->off_animation == 'slideOutUp' ? 'selected="selected"' : null ?>><?= language()->notification->settings->off_animation_slideOutUp ?></option>
                <option value="slideOutDown" <?= $data->notification->settings->off_animation == 'slideOutDown' ? 'selected="selected"' : null ?>><?= language()->notification->settings->off_animation_slideOutDown ?></option>
                <option value="zoomOut" <?= $data->notification->settings->off_animation == 'zoomOut' ? 'selected="selected"' : null ?>><?= language()->notification->settings->off_animation_zoomOut ?></option>
                <option value="bounceOut" <?= $data->notification->settings->off_animation == 'bounceOut' ? 'selected="selected"' : null ?>><?= language()->notification->settings->off_animation_bounceOut ?></option>
            </select>
        </div>
    </div>
</div>


<?php $notification_settings_default_html['customize'] = ob_get_clean() ?>

<div class="mt-5 mb-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
    <div>
        <h2 class="h3"><?= language()->notification->preview->header ?></h2>
        <p class="text-muted"><?= language()->notification->preview->subheader ?></p>
    </div>

    <div id="notification_preview" class="notification-preview-<?= mb_strtolower($data->notification->type) ?>">
        <?= \Inpush\Notification::get($data->notification->type, $data->notification, $this->user)->html ?>
    </div>
</div>


<div class="mt-5 mb-3 d-flex justify-content-between">
    <h2 class="h3"><?= language()->notification->settings->header ?></h2>
</div>

<div class="row">
    <div class="col-12 col-md-3">
        <ul class="nav flex-md-column nav-pills mb-3" id="pills-tab" role="tablist">

            <?php if(in_array('basic', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link active" id="tab_basic_link" data-toggle="pill" href="#tab_basic" role="tab" aria-controls="tab_basic" aria-selected="true">
                        <i class="fa fa-fw fa-sm fa-cog mr-1"></i> <?= language()->notification->settings->tab_basic ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('triggers', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_triggers_link" data-toggle="pill" href="#tab_triggers" role="tab" aria-controls="tab_triggers" aria-selected="false">
                        <i class="fa fa-fw fa-sm fa-angle-up mr-1"></i> <?= language()->notification->settings->tab_triggers ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('display', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_display_link" data-toggle="pill" href="#tab_display" role="tab" aria-controls="tab_display" aria-selected="false">
                        <i class="fa fa-fw fa-sm fa-sliders-h mr-1"></i> <?= language()->notification->settings->tab_display ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('customize', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_customize_link" data-toggle="pill" href="#tab_customize" role="tab" aria-controls="tab_customize" aria-selected="false">
                        <i class="fa fa-fw fa-sm fa-paint-brush mr-1"></i> <?= language()->notification->settings->tab_customize ?>
                    </a>
                </li>
            <?php endif ?>


            <?php if(in_array('data', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_data_link" data-toggle="pill" href="#tab_data" role="tab" aria-controls="tab_data" aria-selected="false">
                        <i class="fa fa-fw fa-sm fa-database mr-1"></i> <?= language()->notification->settings->tab_data ?>
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>

    <div class="col">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" />

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_basic" role="tabpanel" aria-labelledby="tab_basic_link">

                    <?= $settings->html['basic'] ?>

                </div>

                <div class="tab-pane fade" id="tab_triggers" role="tabpanel" aria-labelledby="tab_triggers_link">

                    <?= $notification_settings_default_html['triggers'] ?>

                    <?= isset($settings->html['triggers']) ? $settings->html['triggers'] : null ?>

                </div>

                <div class="tab-pane fade" id="tab_display" role="tabpanel" aria-labelledby="tab_display_link">

                    <?= isset($settings->html['display']) ? $settings->html['display'] : $notification_settings_default_html['display'] ?>

                </div>

                <div class="tab-pane fade" id="tab_customize" role="tabpanel" aria-labelledby="tab_customize_link">

                    <?= $settings->html['customize'] ?>

                    <?= $notification_settings_default_html['customize'] ?>

                </div>

                <div class="tab-pane fade" id="tab_data" role="tabpanel" aria-labelledby="tab_data_link">

                    <?= $settings->html['data'] ?? null ?>

                </div>

            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= language()->global->update ?></button>
            </div>

        </form>
    </div>
</div>


<div style="display:none" id="trigger_rule_sample">
    <div class="input-group mb-3">
        <select class="form-control trigger-type-select" name="trigger_type[]">
            <option value="exact" data-placeholder="<?= language()->notification->settings->trigger_type_exact_placeholder ?>"><?= language()->notification->settings->trigger_type_exact ?></option>
            <option value="not_exact" data-placeholder="<?= language()->notification->settings->trigger_type_not_exact_placeholder ?>"><?= language()->notification->settings->trigger_type_not_exact ?></option>
            <option value="contains" data-placeholder="<?= language()->notification->settings->trigger_type_contains_placeholder ?>"><?= language()->notification->settings->trigger_type_contains ?></option>
            <option value="not_contains" data-placeholder="<?= language()->notification->settings->trigger_type_not_contains_placeholder ?>"><?= language()->notification->settings->trigger_type_not_contains ?></option>
            <option value="starts_with" data-placeholder="<?= language()->notification->settings->trigger_type_starts_with_placeholder ?>"><?= language()->notification->settings->trigger_type_starts_with ?></option>
            <option value="not_starts_with" data-placeholder="<?= language()->notification->settings->trigger_type_not_starts_with_placeholder ?>"><?= language()->notification->settings->trigger_type_not_starts_with ?></option>
            <option value="ends_with" data-placeholder="<?= language()->notification->settings->trigger_type_ends_with_placeholder ?>"><?= language()->notification->settings->trigger_type_ends_with ?></option>
            <option value="not_ends_with" data-placeholder="<?= language()->notification->settings->trigger_type_not_ends_with_placeholder ?>"><?= language()->notification->settings->trigger_type_not_ends_with ?></option>
            <option value="page_contains" data-placeholder="<?= language()->notification->settings->trigger_type_page_contains_placeholder ?>"><?= language()->notification->settings->trigger_type_page_contains ?></option>
        </select>

        <input type="text" name="trigger_value[]" class="form-control">

        <button type="button" class="trigger-delete ml-3 btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i></button>
    </div>
</div>

<div style="display:none" id="data_trigger_auto_rule_sample">
    <div class="input-group mb-3">
        <select class="form-control trigger-type-select" name="data_trigger_auto_type[]">
            <option value="exact"><?= language()->notification->settings->trigger_type_exact ?></option>
            <option value="contains"><?= language()->notification->settings->trigger_type_contains ?></option>
            <option value="starts_with"><?= language()->notification->settings->trigger_type_starts_with ?></option>
            <option value="ends_with"><?= language()->notification->settings->trigger_type_ends_with ?></option>
            <option value="page_contains"><?= language()->notification->settings->trigger_type_page_contains ?></option>
        </select>

        <input type="text" name="data_trigger_auto_value[]" class="form-control" placeholder="<?= language()->notification->settings->trigger_type_exact_placeholder ?>" aria-label="<?= language()->notification->settings->trigger_type_exact_placeholder ?>">

        <button type="button" class="data-trigger-auto-delete ml-3 btn btn-outline-danger btn-sm" aria-label="<?= language()->global->delete ?>"><i class="fa fa-fw fa-times"></i></button>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/pickr.min.js' ?>"></script>

<script>
    /* Initiate the color picker */
    let pickr_options = {
        comparison: false,

        components: {
            preview: true,
            opacity: false,
            hue: true,
            comparison: false,
            interaction: {
                hex: true,
                rgba: false,
                hsla: false,
                hsva: false,
                cmyk: false,
                input: true,
                clear: false,
                save: true
            }
        }
    };


    /* Display Trigger Handler */
    let display_trigger_status_handler = () => {

        let display_trigger = $('select[name="display_trigger"] option:selected');

        switch(display_trigger.val()) {

            case 'delay':
            case 'scroll':

                /* Make sure to show the input field */
                $('input[name="display_trigger_value"]').show();

                /* Add the proper placeholder */
                $('input[name="display_trigger_value"]').attr('placeholder', $(display_trigger).data('placeholder'));

                break;

            case 'exit_intent':

                /* Hide the display trigger value for this option */
                $('input[name="display_trigger_value"]').hide();

                break;

        }

    };

    /* Trigger it for the first initial load */
    display_trigger_status_handler();

    /* Trigger on select change */
    $('select[name="display_trigger"]').on('change', () => {
        display_trigger_status_handler();

        /* Clear the input from previous values */
        $('input[name="display_trigger_value"]').val('');
    });



    /* Triggers Handler */
    let triggers_status_handler = () => {

        if($('#trigger_all_pages').is(':checked')) {

            /* Disable the container visually */
            $('#triggers').addClass('container-disabled');

            /* Remove the new trigger add button */
            $('#trigger_add').hide();

        } else {

            /* Remove disabled container if depending on the status of the trigger checkbox */
            $('#triggers').removeClass('container-disabled');

            /* Bring back the new trigger add button */
            $('#trigger_add').show();

        }

        $('select[name="trigger_type[]"]').off().on('change', event => {

            let input = $(event.currentTarget).closest('div').find('input');
            let placeholder = $(event.currentTarget).find(':checked').data('placeholder');

            /* Add the proper placeholder */
            input.attr('placeholder', placeholder);

        }).trigger('change');

    };

    /* Trigger on status change live of the checkbox */
    $('#trigger_all_pages').on('change', triggers_status_handler);

    /* Delete trigger handler */
    let triggers_delete_handler = () => {

        /* Delete button handler */
        $('.trigger-delete').off().on('click', event => {

            let trigger = $(event.currentTarget).closest('.input-group');

            trigger.remove();

            triggers_count_handler();
        });

    };

    let triggers_add_sample = () => {
        let trigger_rule_sample = $('#trigger_rule_sample').html();

        $('#triggers').append(trigger_rule_sample);
    };

    let triggers_count_handler = () => {

        let total_triggers = $('#triggers > .input-group').length;

        /* Make sure we at least have two input groups to show the delete button */
        if(total_triggers > 1) {
            $('#triggers .trigger-delete').show();

            /* Make sure to set a limit to these triggers */
            if(total_triggers > 10) {
                $('#trigger_add').hide();
            } else {
                $('#trigger_add').show();
            }

        } else {

            if(total_triggers == 0) {
                triggers_add_sample();
            }

            $('#triggers .trigger-delete').hide();
        }
    };

    /* Add new trigger rule handler */
    $('#trigger_add').on('click', () => {
        triggers_add_sample();
        triggers_delete_handler();
        triggers_count_handler();
        triggers_status_handler();
    });

    /* Trigger functions for the first initial load */
    triggers_status_handler();
    triggers_delete_handler();
    triggers_count_handler();


    /* Border radius preview */
    $('select[name="border_radius"]').on('change', event => {
        let border_radius = $(event.currentTarget).find(':checked').val();

        let notification_preview_wrapper = $('#notification_preview .inpush-wrapper');

        notification_preview_wrapper.removeClass('inpush-wrapper-round inpush-wrapper-straight inpush-wrapper-rounded').addClass(`inpush-wrapper-${border_radius}`);
    });

    /* Border Color Handler */
    let settings_border_color_pickr = Pickr.create({
        el: '#settings_border_color_pickr',
        default: $('#settings_border_color').val(),
        ...pickr_options
    });

    settings_border_color_pickr.on('change', hsva => {
        $('#settings_border_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .inpush-wrapper').css('border-color', hsva.toHEXA().toString());
    });

    /* Border Width Handler */
    $('#settings_border_width').on('change', event => {

        /* Notification Preview Handler */
        $('#notification_preview .inpush-wrapper').css('border-width', $(event.currentTarget).val());

    });

    /* Shadow handler */
    $('#settings_shadow').on('change', event => {

        /* Notification Preview Handler */
        if($(event.currentTarget).is(':checked')) {
            $('#notification_preview .inpush-wrapper').addClass('inpush-wrapper-shadow');
        } else {
            $('#notification_preview .inpush-wrapper').removeClass('inpush-wrapper-shadow');
        }

    });

    /* Failsafe on _color fields being empty */
    $('input[name$="_color"]').on('change paste keyup', event => {
       if($(event.currentTarget).val().trim() == '') {
           $(event.currentTarget).val('#000');
       }
    });
</script>

<?= $settings->javascript ?>

<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>

