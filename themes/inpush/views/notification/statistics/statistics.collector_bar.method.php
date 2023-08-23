<?php
defined('INPUSH') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Clicks Chart */ ?>
<?php ob_start() ?>
<div class="chart-container mb-5">
    <canvas id="form_submissions_chart"></canvas>
</div>
<?php $html['charts'] = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let form_submissions_chart = document.getElementById('form_submissions_chart').getContext('2d');

    gradient = form_submissions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(226, 96, 174, 0.4)');
    gradient.addColorStop(1, 'rgba(226, 96, 174, 0.05)');

    new Chart(form_submissions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(language()->notification->statistics->form_submissions_chart) ?>,
                data: <?= $data->logs_chart['form_submission'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#E260AE',
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
