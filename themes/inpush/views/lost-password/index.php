<?php defined('INPUSH') || die() ?>

<div class="container">

    <div class="d-flex flex-column align-items-center">
        <div class="col-sm-12 col-md-8 col-xl-6">
            <?= \Inpush\Alerts::output_alerts() ?>

            <div class="card border-0 shadow-md">
                <div class="card-body p-5">

                    <h1 class="h4 card-title d-flex justify-content-between"><?= language()->lost_password->header ?></h1>
                    <p class="text-muted"><?= language()->lost_password->subheader ?></p>

                    <form action="" method="post" class="mt-4" role="form">
                        <div class="form-group">
                            <label for="email"><?= language()->lost_password->email ?></label>
                            <input id="email" type="email" name="email" class="form-control form-control-lg <?= \Inpush\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
                            <?= \Inpush\Alerts::output_field_error('email') ?>
                        </div>

                        <?php if(settings()->captcha->lost_password_is_enabled): ?>
                            <div class="form-group">
                                <?php $data->captcha->display() ?>
                            </div>
                        <?php endif ?>

                        <div class="form-group mt-3">
                            <button type="submit" name="submit" class="btn btn-primary btn-block my-1"><?= language()->lost_password->submit ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <small><a href="login" class="text-muted"><?= language()->lost_password->return ?></a></small>
            </div>
        </div>
    </div>
</div>

