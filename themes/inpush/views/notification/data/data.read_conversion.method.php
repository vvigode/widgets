<?php defined('INPUSH') || die() ?>

<?php foreach((array) $data->conversion->data as $key => $value): ?>
    <div class="col-4 font-weight-bold"><?= $key ?></div>
    <div class="col-8"><?= $value ?></div>
<?php endforeach ?>

<?php if(!empty($data->conversion->url)): ?>
    <div class="col-4 font-weight-bold"><?= language()->notification->data->url ?></div>
    <div class="col-8"><?= $data->conversion->url ?></div>
<?php endif ?>

<?php if(!empty($data->conversion->ip)): ?>
    <div class="col-4 font-weight-bold"><?= language()->notification->data->ip ?></div>
    <div class="col-8"><?= $data->conversion->ip ?></div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->country)): ?>
    <div class="col-4 font-weight-bold">
        <?= language()->notification->data->country ?>
        <span data-toggle="tooltip" title="<?= sprintf(language()->notification->data->variable, 'country') ?>"><i class="fa fa-fw fa-sm fa-question-circle ml-1 text-muted"></i></span>
    </div>
    <div class="col-8">
        <?php if(isset($data->conversion->location->country_code)): ?>
            <img src="https://www.countryflags.io/<?= $data->conversion->location->country_code ?>/flat/16.png" class="mr-1" alt="<?= language()->notification->data->country ?>" />
        <?php endif ?>
        <span class="align-middle"><?= $data->conversion->location->country ?></span>
    </div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->country_code)): ?>
    <div class="col-4 font-weight-bold">
        <?= language()->notification->data->country_code ?>
        <span data-toggle="tooltip" title="<?= sprintf(language()->notification->data->variable, 'country_code') ?>"><i class="fa fa-fw fa-sm fa-question-circle ml-1 text-muted"></i></span>
    </div>
    <div class="col-8"><span class="align-middle"><?= $data->conversion->location->country_code ?></span></div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->city)): ?>
    <div class="col-4 font-weight-bold">
        <?= language()->notification->data->city ?>
        <span data-toggle="tooltip" title="<?= sprintf(language()->notification->data->variable, 'city') ?>"><i class="fa fa-fw fa-sm fa-question-circle ml-1 text-muted"></i></span>
    </div>
    <div class="col-8"><span class="align-middle"><?= $data->conversion->location->city ?></span></div>
<?php endif ?>
