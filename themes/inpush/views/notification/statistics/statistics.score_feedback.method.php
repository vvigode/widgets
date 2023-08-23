<?php
defined('INPUSH') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Feedback Chart */ ?>
<?php ob_start() ?>
<div class="chart-container mb-5">
    <canvas id="clicks_chart"></canvas>
</div>
<?php $html['charts'] = ob_get_clean() ?>


<?php

ob_start();

/* Logs for the charts */
$result = database()->query("
    SELECT
         `type`,
         COUNT(`id`) AS `total`
    FROM
         `track_notifications`
    WHERE
        `notification_id` = {$data->notification->notification_id}
        AND (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')
        AND `type` LIKE 'feedback_score_%'
    GROUP BY
        `type`
    ORDER BY
        `total` DESC
");

?>

<h2 class="h3 mt-5"><?= language()->notification->statistics->header_feedback ?></h2>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= language()->notification->statistics->feedback ?></th>
            <th><?= language()->notification->statistics->feedback_total ?></th>
        </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_object()): ?>
            <tr>
                <td class="text-nowrap">
                    <i class="<?= language()->notification->score_feedback->icon ?>"></i> <?= language()->notification->score_feedback->{$row->type} ?>
                </td>
                <td class="text-nowrap"><?= nr($row->total) ?></td>
            </tr>
        <?php endwhile ?>
        </tbody>
    </table>
</div>

<?php $html['feedback'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    let clicks_chart = document.getElementById('clicks_chart').getContext('2d');

    new Chart(clicks_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->notification->score_feedback->feedback_score_1) ?>,
                    data: <?= $data->logs_chart['feedback_score_1'] ?? '[]' ?>,
                    borderColor: '#ED4956',
                    fill: false
                },
                {
                    label: <?= json_encode(language()->notification->score_feedback->feedback_score_2) ?>,
                    data: <?= $data->logs_chart['feedback_score_2'] ?? '[]' ?>,
                    borderColor: '#ed804c',
                    fill: false
                },
                {
                    label: <?= json_encode(language()->notification->score_feedback->feedback_score_3) ?>,
                    data: <?= $data->logs_chart['feedback_score_3'] ?? '[]' ?>,
                    borderColor: '#8f8f8f',
                    fill: false
                },
                {
                    label: <?= json_encode(language()->notification->score_feedback->feedback_score_4) ?>,
                    data: <?= $data->logs_chart['feedback_score_4'] ?? '[]' ?>,
                    borderColor: '#6c94ed',
                    fill: false
                },
                {
                    label: <?= json_encode(language()->notification->score_feedback->feedback_score_5) ?>,
                    data: <?= $data->logs_chart['feedback_score_5'] ?? '[]' ?>,
                    borderColor: '#4aed92',
                    fill: false
                }
            ]
        },
        options: {
            tooltips: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: (tooltipItem, data) => {
                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];

                        return `${nr(value)} ${data.datasets[tooltipItem.datasetIndex].label}`;
                    }
                }
            },
            title: {
                display: true,
                text: <?= json_encode(language()->notification->statistics->feedback_chart) ?>
            },
            legend: {
                display: true
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        beginAtZero: true,
                        userCallback: (value, index, values) => {
                            if (Math.floor(value) === value) {
                                return nr(value);
                            }
                        }
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            }
        }
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
