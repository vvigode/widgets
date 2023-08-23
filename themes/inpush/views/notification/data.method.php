<?php defined('INPUSH') || die(); ?>

<div class="mt-5 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div class="d-flex">
            <h2 class="h3 mr-3"><?= language()->notification->data->header ?></h2>

            <div>
                <?php if(count($data->conversions)): ?>
                    <a href="<?= url('notification/' . $data->notification->notification_id . '/data/' . $data->datetime['start_date'] . '/' . $data->datetime['end_date'] . '?page=' . ($_GET['page'] ?? 1) . '&json') ?>" target="_blank" class="btn btn-sm btn-light mr-3"><i class="fa fa-fw fa-file-csv"></i> <?= language()->global->export_json ?></a>
                <?php endif ?>
                <button type="button" data-toggle="modal" data-target="#create_notification_data" class="btn btn-sm btn-primary mr-3"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->notification->data->create ?></button>
            </div>
        </div>

        <div>
            <button
                    id="daterangepicker"
                    type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-min-date="<?= \Inpush\Date::get($data->notification->datetime, 4) ?>"
                    data-max-date="<?= \Inpush\Date::get('', 4) ?>"
            >
                <i class="fa fa-fw fa-calendar mr-1"></i>
                <span>
                    <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                        <?= \Inpush\Date::get($data->datetime['start_date'], 2, \Inpush\Date::$default_timezone) ?>
                    <?php else: ?>
                        <?= \Inpush\Date::get($data->datetime['start_date'], 2, \Inpush\Date::$default_timezone) . ' - ' . \Inpush\Date::get($data->datetime['end_date'], 2, \Inpush\Date::$default_timezone) ?>
                    <?php endif ?>
                </span>
                <i class="fa fa-fw fa-caret-down ml-1"></i>
            </button>
        </div>
    </div>
</div>


<?php if(!count($data->conversions)): ?>

    <div class="d-flex flex-column align-items-center justify-content-center">
        <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= language()->global->no_data ?>" />
        <h2 class="h4 text-muted"><?= language()->global->no_data ?></h2>
        <p><?= language()->notification->info_message->no_data ?></a></p>
    </div>

<?php else: ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= language()->notification->data->data ?></th>
                <th><?= language()->notification->data->type ?></th>
                <th><?= language()->notification->data->date ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="accordion" id="accordion">

            <?php foreach($data->conversions as $row): ?>
                <tr class="clickable" data-toggle="collapse" data-target="#<?= 'data_collapse_' . $row->id ?>" aria-expanded="true" aria-controls="<?= 'data_collapse_' . $row->id ?>">
                    <td class="text-nowrap">
                        <strong><?= language()->notification->data->expand_data ?></strong>
                    </td>
                    <td class="text-nowrap"><?= language()->notification->data->{'type_' . $row->type} ?></td>
                    <td class="text-nowrap"><span class="text-muted" data-toggle="tooltip" title="<?= \Inpush\Date::get($row->datetime, 1) ?>"><?= \Inpush\Date::get($row->datetime) ?></span></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                    <i class="fa fa-fw fa-ellipsis-v"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" data-delete-notification-data="<?= language()->global->info_message->confirm_delete ?>" data-row-id="<?= $row->id ?>"><i class="fa fa-fw fa-times"></i> <?= language()->global->delete ?></a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr id="<?= 'data_collapse_' . $row->id ?>" data-id="<?= $row->id ?>" data-notification-id="<?= $row->notification_id ?>" class="collapse" data-parent="#accordion">
                    <td colspan="4">
                        <div class="row">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow"></div>
                            </div>
                        </div>
                    </td>
                </tr>

            <?php endforeach ?>

            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>
<?php endif ?>


<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->datetime['start_date']) ?>,
        endDate: <?= json_encode($data->datetime['end_date']) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode(language()->global->date->today) ?>: [moment(), moment()],
            <?= json_encode(language()->global->date->yesterday) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode(language()->global->date->last_7_days) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode(language()->global->date->last_30_days) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode(language()->global->date->this_month) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode(language()->global->date->last_month) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            <?= json_encode(language()->global->date->all_time) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`<?= url('notification/' . $data->notification->notification_id . '/data') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });


    /* Handle the opening and closing of the details for conversions */
    $('[id^="data_collapse_"]').on('show.bs.collapse', event => {
        let id = $(event.currentTarget).data('id');
        let notification_id = $(event.currentTarget).data('notification-id');
        let request_type = 'read_data_conversion';

        $.ajax({
            type: 'GET',
            url: `notifications-ajax?id=${id}&notification_id=${notification_id}&global_token=${global_token}&request_type=${request_type}`,
            success: (result) => {

                $(event.currentTarget).find('.row').html(result.details.html);

                /* Refresh tooltips */
                $('[data-toggle="tooltip"]').tooltip();

            },
            dataType: 'json'
        });


    });

    /* Delete handler for the conversion */
    $('[data-delete-notification-data]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete-notification-data');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'notification-data-ajax', 'delete', () => {

            /* On success delete the actual row from the DOM */
            let current_tr = $(event.currentTarget).closest('tr');
            let next_tr = current_tr.next();

            current_tr.remove();
            next_tr.remove();

        });
    });
</script>

<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
