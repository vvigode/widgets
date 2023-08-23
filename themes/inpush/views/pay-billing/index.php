<?php defined('INPUSH') || die() ?>


<div class="container">
    <?= \Inpush\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('plan') ?>"><?= language()->plan->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= language()->pay_billing->breadcrumb ?></li>
        </ol>
    </nav>

    <h1 class="h3"><?= language()->pay_billing->header ?></h1>
    <div class="text-muted mb-5"><?= language()->pay_billing->subheader ?></div>

    <form action="" method="post" role="form" class="mt-5">
        <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" />

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <label><?= language()->account->billing->type ?></label>
                    <select name="billing_type" class="form-control">
                        <option value="personal" <?= $this->user->billing->type == 'personal' ? 'selected="selected"' : null ?>><?= language()->account->billing->type_personal ?></option>
                        <option value="business" <?= $this->user->billing->type == 'business' ? 'selected="selected"' : null ?>><?= language()->account->billing->type_business ?></option>
                    </select>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label><?= language()->account->billing->name ?></label>
                    <input type="text" name="billing_name" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_name') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->name ?>" required="required" />
                    <?= \Inpush\Alerts::output_field_error('billing_name') ?>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label><?= language()->account->billing->address ?></label>
                    <input type="text" name="billing_address" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_address') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->address ?>" required="required" />
                    <?= \Inpush\Alerts::output_field_error('billing_address') ?>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="form-group">
                    <label><?= language()->account->billing->city ?></label>
                    <input type="text" name="billing_city" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_city') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->city ?>" required="required" />
                    <?= \Inpush\Alerts::output_field_error('billing_city') ?>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="form-group">
                    <label><?= language()->account->billing->county ?></label>
                    <input type="text" name="billing_county" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_county') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->county ?>" required="required" />
                    <?= \Inpush\Alerts::output_field_error('billing_county') ?>
                </div>
            </div>

            <div class="col-12 col-lg-2">
                <div class="form-group">
                    <label><?= language()->account->billing->zip ?></label>
                    <input type="text" name="billing_zip" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_zip') ? 'is-invalid' : null ?>" value="<?= $this->user->billing->zip ?>" required="required" />
                    <?= \Inpush\Alerts::output_field_error('billing_zip') ?>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label><?= language()->account->billing->country ?></label>
                    <select name="billing_country" class="form-control <?= \Inpush\Alerts::has_field_errors('billing_country') ? 'is-invalid' : null ?>">
                        <?php foreach(get_countries_array() as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $this->user->billing->country == $key ? 'selected="selected"' : null ?>><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= \Inpush\Alerts::output_field_error('billing_country') ?>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label><?= language()->account->billing->phone ?></label>
                    <input type="text" name="billing_phone" class="form-control" value="<?= $this->user->billing->phone ?>" />
                </div>
            </div>

            <div class="col-12" id="billing_tax_id_container">
                <div class="form-group">
                    <label><?= !empty(settings()->business->tax_type) ? settings()->business->tax_type : language()->account->billing->tax_id ?></label>
                    <input type="text" name="billing_tax_id" class="form-control" value="<?= $this->user->billing->tax_id ?>" />
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= sprintf(language()->pay_billing->submit, $data->plan->name) ?></button>
        </div>
    </form>
</div>

<?php ob_start() ?>
    <script>
        'use strict';

        /* Billing type handler */
        let billing_type = () => {
            let type = document.querySelector('select[name="billing_type"]').value;

            if(type == 'personal') {
                document.querySelector('#billing_tax_id_container').style.display = 'none';
            } else {
                document.querySelector('#billing_tax_id_container').style.display = '';
            }
        };

        billing_type();

        document.querySelector('select[name="billing_type"]').addEventListener('change', billing_type);

        <?php if(!empty($this->user->payment_subscription_id)): ?>
        document.querySelectorAll('[name^="billing_"]').forEach(element => {
            element.setAttribute('disabled', 'disabled');
        });
        <?php endif ?>

    </script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
