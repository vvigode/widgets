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
    <canvas id="clicks_chart"></canvas>
</div>
<?php $html['charts'] = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let clicks_chart = document.getElementById('clicks_chart').getContext('2d');

    gradient = clicks_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 187, 226, 0.4)');
    gradient.addColorStop(1, 'rgba(96, 187, 226, 0.05)');

    new Chart(clicks_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(language()->notification->statistics->clicks_chart) ?>,
                data: <?= $data->logs_chart['click'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#60BBE2',
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
