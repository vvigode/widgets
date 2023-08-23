<?php defined('INPUSH') || die() ?>

<div class="container">
    <?= \Inpush\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= language()->contact->breadcrumb ?></li>
        </ol>
    </nav>

    <div>
        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= language()->contact->header ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= language()->contact->subheader ?>">
                    <i class="fa fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label for="email"><?= language()->contact->input->email ?></label>
                <input id="email" type="email" name="email" class="form-control <?= \Inpush\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" maxlength="64" required="required" />
                <?= \Inpush\Alerts::output_field_error('email') ?>
            </div>

            <div class="form-group">
                <label for="name"><?= language()->contact->input->name ?></label>
                <input id="name" type="text" name="name" class="form-control <?= \Inpush\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" maxlength="320" required="required" />
                <?= \Inpush\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="subject"><?= language()->contact->input->subject ?></label>
                <input id="subject" type="text" name="subject" class="form-control <?= \Inpush\Alerts::has_field_errors('subject') ? 'is-invalid' : null ?>" value="<?= $data->values['subject'] ?>" maxlength="128" required="required" />
                <?= \Inpush\Alerts::output_field_error('subject') ?>
            </div>

            <div class="form-group">
                <label for="message"><?= language()->contact->input->message ?></label>
                <textarea id="message" name="message" class="form-control <?= \Inpush\Alerts::has_field_errors('message') ? 'is-invalid' : null ?>" maxlength="2048" required="required"><?= $data->values['message'] ?></textarea>
                <?= \Inpush\Alerts::output_field_error('message') ?>
            </div>

            <?php if(settings()->captcha->contact_is_enabled): ?>
                <div class="form-group">
                    <?php $data->captcha->display() ?>
                </div>
            <?php endif ?>

            <button type="submit" name="submit" class="btn btn-primary btn-block"><?= language()->global->submit ?></button>
        </form>
    </div>
</div>
