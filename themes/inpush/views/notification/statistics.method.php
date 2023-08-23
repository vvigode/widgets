<?php
defined('INPUSH') || die();

/* Include the extra content of the notification */
$statistics = require THEME_PATH . 'views/notification/statistics/statistics.' . mb_strtolower($data->notification->type) . '.method.php';

?>

<?php if(!settings()->smoorf->analytics_is_enabled): ?>
    <div class="alert alert-warning" role="alert">
        <?= language()->notification->statistics->disabled ?>
    </div>
<?php endif ?>

<div class="mt-5 mb-3">
    <div class="d-flex flex-column flex-md-row justify-content-between">
        <div>
            <h2 class="h3"><?= language()->notification->statistics->header ?></h2>
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

<?php if(!count($data->logs)): ?>

    <div class="d-flex flex-column align-items-center justify-content-center">
        <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= language()->global->no_data ?>" />
        <h2 class="h4 text-muted"><?= language()->global->no_data ?></h2>
        <p><?= language()->notification->info_message->no_data ?></a></p>
    </div>

<?php else: ?>

    <div class="row justify-content-between mb-5">
        <div class="col-12 col-md-4 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-eye fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['impression']) ?></div>
                        <small class="text-muted"><?= language()->notification->statistics->impressions_chart ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-mouse-pointer fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['hover']) ?></div>
                        <small class="text-muted"><?= language()->notification->statistics->hovers_chart ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4 mb-3 mb-xl-0">
            <div class="card border-0 h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card border-0 bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fa fa-fw fa-mouse fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['click']) ?></div>
                        <small class="text-muted"><?= language()->notification->statistics->clicks_chart ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="chart-container mb-5">
        <canvas id="impressions_chart"></canvas>
    </div>

    <div class="chart-container mb-5">
        <canvas id="hovers_chart"></canvas>
    </div>

    <?php foreach($statistics->html as $html): echo $html; endforeach ?>

    <?php if($data->top_pages_result->num_rows): ?>

        <h2 class="h3 mt-5"><?= language()->notification->statistics->header_top_pages ?></h2>
        <p class="text-muted"><?= language()->notification->statistics->subheader_top_pages ?></p>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th></th>
                        <th>
                            <?= language()->notification->statistics->pageviews ?>
                        </th>
                    </tr>
                </thead>
                <tbody>

                <?php while($row = $data->top_pages_result->fetch_object()): ?>

                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex flex-column">
                                <?= language()->notification->statistics->{$row->type} ?>
                                <span class="text-muted"><?= $row->url ?></span>
                            </div>
                        </td>
                        <td class="text-nowrap"><?= nr($row->pageviews) ?></td>
                    </tr>

                <?php endwhile ?>

                </tbody>
            </table>
        </div>

    <?php endif ?>

<?php endif ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>

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
        redirect(`<?= url('notification/' . $data->notification->notification_id . '/statistics') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

    <?php if(count($data->logs)): ?>
    let impressions_chart = document.getElementById('impressions_chart').getContext('2d');

    let gradient = impressions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 122, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(96, 122, 226, 0.05)');

    new Chart(impressions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(language()->notification->statistics->impressions_chart) ?>,
                data: <?= $data->logs_chart['impression'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#607ae2',
                fill: true
            }]
        },
        options: chart_options
    });


    let hovers_chart = document.getElementById('hovers_chart').getContext('2d');

    gradient = hovers_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(213, 96, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(213, 96, 226, 0.05)');

    new Chart(hovers_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(language()->notification->statistics->hovers_chart) ?>,
                data: <?= $data->logs_chart['hover'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#d560e2',
                fill: true
            }]
        },
        options: chart_options
    });
    <?php endif ?>

</script>

<?= $statistics->javascript ?>

<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>

