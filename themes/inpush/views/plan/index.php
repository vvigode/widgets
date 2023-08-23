<?php defined('INPUSH') || die() ?>

<div class="container">
    <div class="d-flex flex-column justify-content-center">

        <?= \Inpush\Alerts::output_alerts() ?>

        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= language()->plan->breadcrumb ?></li>
            </ol>
        </nav>

        <?php if(\Inpush\Middlewares\Authentication::check() && $this->user->plan_is_expired && $this->user->plan_id != 'free'): ?>
            <div class="alert alert-info" role="alert">
                <?= language()->global->info_message->user_plan_is_expired ?>
            </div>
        <?php endif ?>

        <?php if($data->type == 'new'): ?>

            <h1 class="h2"><?= language()->plan->header_new ?></h1>
            <span class="text-muted"><?= language()->plan->subheader_new ?></span>

        <?php elseif($data->type == 'upgrade'): ?>

            <h1 class="h2"><?= language()->plan->header_upgrade ?></h1>
            <span class="text-muted"><?= language()->plan->subheader_upgrade ?></span>

        <?php elseif($data->type == 'renew'): ?>

            <h1 class="h2"><?= language()->plan->header_renew ?></h1>
            <span class="text-muted"><?= language()->plan->subheader_renew ?></span>

        <?php endif ?>


        <div class="mt-5 col-12">
            <?= $this->views['plans'] ?>
        </div>

    </div>
</div>
