<?php defined('INPUSH') || die() ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Declare some used variables inside javascript */
    window.inpush.plan_id = $('input[name="plan_id"]').val();
    window.inpush.monthly_price = $('input[name="monthly_price"]').val();
    window.inpush.annual_price = $('input[name="annual_price"]').val();
    window.inpush.lifetime_price = $('input[name="lifetime_price"]').val();
    window.inpush.code = null;

    window.inpush.payment_type_one_time_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['one_time', 'both'])) ?>;
    window.inpush.payment_type_recurring_enabled = <?= json_encode((bool) in_array(settings()->payment->type, ['recurring', 'both'])) ?>;

    window.inpush.taxes = <?= json_encode($data->plan_taxes ? $data->plan_taxes : null) ?>;
</script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>

<div class="container">
    <?= \Inpush\Alerts::output_alerts() ?>

    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= language()->index->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('plan') ?>"><?= language()->plan->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('pay-billing/' . $data->plan_id) ?>"><?= language()->pay_billing->breadcrumb ?></a> <i class="fa fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= sprintf(language()->pay->breadcrumb, $data->plan->name) ?></li>
        </ol>
    </nav>

    <?php if($data->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])): ?>
        <h1 class="h3"><?= sprintf(language()->pay->trial->header, $data->plan->name) ?></h1>
        <div class="text-muted mb-5"><?= language()->pay->trial->subheader ?></div>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-xl-8 order-1 order-xl-0">
                    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= sprintf(language()->pay->trial->trial_start, $data->plan->trial_days) ?></button>
                    <a href="<?= url('pay/' . $data->plan_id . '?trial_skip=true') ?>" class="btn btn-block btn-outline-secondary"><?= language()->pay->trial->trial_skip ?></a>

                    <div class="mt-3 text-muted text-center">
                        <small>
                            <?= sprintf(
                                language()->pay->accept,
                                '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . language()->global->terms_and_conditions . '</a>',
                                '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . language()->global->privacy_policy . '</a>'
                            ) ?>
                        </small>
                    </div>

                </div>

                <div class="mb-5 col-12 col-xl-4 order-0 order-xl-1">
                    <div class="">
                        <div class="">
                            <h2 class="h4 mb-4 text-muted"><?= language()->pay->plan_details ?></h2>

                            <?= (new \Inpush\Views\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row"><div class="col-12 col-xl-8"></div></div>
        </form>


    <?php elseif(is_numeric($data->plan_id)): ?>

    <?php
    /* Check for extra savings on the prices */
    $annual_price_savings = number_format($data->plan->annual_price - ($data->plan->monthly_price * 12), 2);

    ?>

        <h1 class="h3"><?= sprintf(language()->pay->custom_plan->header, $data->plan->name) ?></h1>
        <div class="text-muted mb-5"><?= language()->pay->custom_plan->subheader ?></div>

        <form action="" method="post" enctype="multipart/form-data" role="form">
            <input type="hidden" name="plan_id" value="<?= $data->plan_id ?>" />
            <input type="hidden" name="monthly_price" value="<?= $data->plan->monthly_price ?>" />
            <input type="hidden" name="annual_price" value="<?= $data->plan->annual_price ?>" />
            <input type="hidden" name="lifetime_price" value="<?= $data->plan->lifetime_price ?>" />
            <input type="hidden" name="token" value="<?= \Inpush\Middlewares\Csrf::get() ?>" />

            <div class="row">
                <div class="col-12 col-xl-8">

                    <h2 class="h5 mb-4 text-muted"><i class="fa fa-fw fa-sm fa-box-open mr-1"></i> <?= language()->pay->custom_plan->payment_frequency ?></h2>

                    <div>
                        <div class="row d-flex align-items-stretch">

                            <?php if($data->plan->monthly_price): ?>
                                <label class="col-12 my-2 custom-radio-box">
                                    <input type="radio" id="monthly_price" name="payment_frequency" value="monthly" class="custom-control-input" required="required">

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= language()->pay->custom_plan->monthly ?></div>

                                            <div class="">
                                                <div class="d-flex align-items-center">
                                                    <span id="monthly_price_amount" class="custom-radio-box-main-text"><?= $data->plan->monthly_price ?></span>
                                                    <span class="ml-1"><?= settings()->payment->currency ?></span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($data->plan->annual_price): ?>
                                <label class="col-12 my-2 custom-radio-box">
                                    <input type="radio" id="annual_price" name="payment_frequency" value="annual" class="custom-control-input" required="required">

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= language()->pay->custom_plan->annual ?></div>

                                            <div class="d-flex align-items-center">
                                                <?php if($data->plan->monthly_price && $annual_price_savings > 0): ?>
                                                    <div class="payment-price-savings mr-2">
                                                        <span><?= sprintf(language()->pay->custom_plan->annual_savings, '<span class="badge badge-success">-' . $annual_price_savings, settings()->payment->currency . '</span>') ?></span>
                                                    </div>
                                                <?php endif ?>

                                                <span id="annual_price_amount" class="custom-radio-box-main-text"><?= $data->plan->annual_price ?></span>
                                                <span class="ml-1"><?= settings()->payment->currency ?></span>
                                            </div>

                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                            <?php if($data->plan->lifetime_price): ?>
                                <label class="col-12 my-2 custom-radio-box">
                                    <input type="radio" id="lifetime_price" name="payment_frequency" value="lifetime" class="custom-control-input" required="required">

                                    <div class="card">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <div class="card-title mb-0"><?= language()->pay->custom_plan->lifetime ?></div>

                                            <div class="d-flex align-items-center">
                                                <div class="payment-price-savings mr-2">
                                                    <small><?= language()->pay->custom_plan->lifetime_help ?></small>
                                                </div>

                                                <span id="lifetime_price_amount" class="custom-radio-box-main-text"><?= $data->plan->lifetime_price ?></span>
                                                <span class="ml-1"><?= settings()->payment->currency ?></span>
                                            </div>

                                        </div>
                                    </div>
                                </label>
                            <?php endif ?>

                        </div>
                    </div>

                    <h2 class="h5 mt-5 mb-4 text-muted"><i class="fa fa-fw fa-sm fa-money-check-alt mr-1"></i> <?= language()->pay->custom_plan->payment_processor ?></h2>
                    <?php if(!settings()->yookassa->is_enabled && !settings()->paypal->is_enabled && !settings()->stripe->is_enabled && !settings()->offline_payment->is_enabled && !settings()->coinbase->is_enabled && !settings()->payu->is_enabled && !settings()->paystack->is_enabled && !settings()->razorpay->is_enabled && !settings()->mollie->is_enabled): ?>
                        <div class="alert alert-info" role="alert">
                            <?= language()->pay->custom_plan->no_processor ?>
                        </div>
                    <?php else: ?>
                        <div>
                            <div class="row d-flex align-items-stretch">
                                <?php foreach($data->payment_processors as $key => $value): ?>
                                    <?php if(settings()->{$key}->is_enabled): ?>
                                        <label class="col-12 my-2 custom-radio-box">
                                            <input type="radio" name="payment_processor" value="<?= $key ?>" class="custom-control-input" required="required">

                                            <div class="card">
                                                <div class="card-body d-flex align-items-center justify-content-between">
                                                    <div class="card-title mb-0"><?= language()->pay->custom_plan->{$key} ?></div>

                                                    <div class="">
                                                        <span class="custom-radio-box-main-icon"><i class="<?= $value['icon'] ?> fa-fw"></i></span>
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    <?php endif ?>
                                <?php endforeach ?>
                            </div>

                            <div id="offline_payment_processor_wrapper" style="display: none;">
                                <div class="form-group mt-4">
                                    <label><?= language()->pay->custom_plan->offline_payment_instructions ?></label>
                                    <div class="card"><div class="card-body"><?= nl2br(settings()->offline_payment->instructions) ?></div></div>
                                </div>

                                <div class="form-group mt-4">
                                    <label><?= language()->pay->custom_plan->offline_payment_proof ?></label>
                                    <input id="offline_payment_proof" type="file" name="offline_payment_proof" accept="<?= \Inpush\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs') ?>" class="form-control" />
                                    <div class="mt-2"><span class="text-muted"><?= sprintf(language()->global->accessibility->whitelisted_file_extensions, \Inpush\Uploads::get_whitelisted_file_extensions_accept('offline_payment_proofs')) ?></span></div>
                                </div>
                            </div>
                        </div>
                    <?php endif ?>

                    <h2 class="h5 mt-5 mb-4 text-muted"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->pay->custom_plan->payment_type ?></h2>

                    <div>
                        <div class="row d-flex align-items-stretch">

                            <label class="col-12 my-2 custom-radio-box" id="one_time_type_label" <?= in_array(settings()->payment->type, ['one_time', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="one_time_type" name="payment_type" value="one_time" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= language()->pay->custom_plan->one_time_type ?></div>

                                        <div class="">
                                            <span class="custom-radio-box-main-icon"><i class="fa fa-fw fa-hand-holding-usd"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>

                            <label class="col-12 my-2 custom-radio-box" id="recurring_type_label" <?= in_array(settings()->payment->type, ['recurring', 'both']) ? null : 'style="display: none"' ?>>
                                <input type="radio" id="recurring_type" name="payment_type" value="recurring" class="custom-control-input" required="required">

                                <div class="card">
                                    <div class="card-body d-flex align-items-center justify-content-between">

                                        <div class="card-title mb-0"><?= language()->pay->custom_plan->recurring_type ?></div>

                                        <div class="">
                                            <span class="custom-radio-box-main-icon"><i class="fa fa-fw fa-sync-alt"></i></span>
                                        </div>

                                    </div>
                                </div>
                            </label>

                        </div>
                    </div>

                </div>

                <div class="mt-5 mt-xl-0 col-12 col-xl-4">
                    <div class="">
                        <div class="mb-5">
                            <h2 class="h4 mb-4 text-muted"><?= language()->pay->plan_details ?></h2>

                            <?= (new \Inpush\Views\View('partials/plan_features'))->run(['plan_settings' => $data->plan->settings]) ?>
                        </div>

                        <div class="card">
                            <div class="card-header text-muted font-weight-bold">
                                <?= language()->pay->custom_plan->summary->header ?>
                            </div>

                            <div class="card-body">

                                <div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= language()->pay->custom_plan->summary->plan ?>
                                        </span>

                                        <span>
                                            <?= $data->plan->name ?>
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= language()->pay->custom_plan->summary->payment_frequency ?>
                                        </span>

                                        <div id="summary_payment_frequency_monthly" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= language()->pay->custom_plan->summary->monthly ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= language()->pay->custom_plan->summary->monthly_help ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_annual" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= language()->pay->custom_plan->summary->annual ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= language()->pay->custom_plan->summary->annual_help ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_frequency_lifetime" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= language()->pay->custom_plan->summary->lifetime ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= language()->pay->custom_plan->summary->lifetime_help ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= language()->pay->custom_plan->summary->payment_type ?>
                                        </span>

                                        <div id="summary_payment_type_one_time" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= language()->pay->custom_plan->summary->one_time ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= language()->pay->custom_plan->summary->one_time_help ?>
                                                </small>
                                            </div>
                                        </div>

                                        <div id="summary_payment_type_recurring" style="display: none;">
                                            <div class="d-flex flex-column">
                                                <span class="text-right">
                                                    <?= language()->pay->custom_plan->summary->recurring ?>
                                                </span>
                                                <small class="text-right text-muted">
                                                    <?= language()->pay->custom_plan->summary->recurring_help ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= language()->pay->custom_plan->summary->payment_processor ?>
                                        </span>

                                        <?php foreach($data->payment_processors as $key => $value): ?>
                                            <?php if(settings()->{$key}->is_enabled): ?>
                                                <span data-summary-payment-processor="<?= $key ?>" class="d-none">
                                                    <?= language()->pay->custom_plan->{$key} ?>
                                                </span>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">
                                            <?= language()->pay->custom_plan->summary->plan_price ?>
                                        </span>

                                        <div>
                                            <span id="summary_plan_price"></span>

                                            <span class="text-muted"><?= settings()->payment->currency ?></span>
                                        </div>
                                    </div>

                                    <div id="summary_discount" class="d-none">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted">
                                                <?= language()->pay->custom_plan->summary->discount ?>
                                            </span>

                                            <div>
                                                <span class="discount-value"></span>

                                                <span class="text-muted"><?= settings()->payment->currency ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="summary_taxes">
                                        <?php if($data->plan_taxes): ?>
                                            <?php foreach($data->plan_taxes as $row): ?>

                                                <div id="summary_tax_id_<?= $row->tax_id ?>" class="d-flex justify-content-between mb-3">
                                                    <div class="d-flex flex-column">
                                                    <span class="text-muted">
                                                        <?= $row->name ?>

                                                        <span data-toggle="tooltip" title="<?= $row->description ?>"><i class="fa fa-fw fa-sm fa-question-circle"></i></span>
                                                    </span>
                                                        <small class="text-muted">
                                                            <?= language()->pay->custom_plan->summary->{$row->type == 'inclusive' ? 'tax_inclusive' : 'tax_exclusive'} ?>
                                                        </small>
                                                    </div>

                                                    <span>
                                                    <?php if($row->value_type == 'percentage'): ?>

                                                        <span class="tax-value"></span>
                                                        <span class="text-muted"><?= settings()->payment->currency ?></span>
                                                        <span class="tax-details text-muted">(<?= $row->value ?>%)</span>

                                                    <?php elseif($row->value_type == 'fixed'): ?>

                                                        <span class="tax-value"></span>
                                                        <span class="tax-details"><?= '+' . $row->value ?> <span class="text-muted"><?= settings()->payment->currency ?></span></span>

                                                    <?php endif ?>
                                                </span>
                                                </div>

                                            <?php endforeach ?>
                                        <?php endif ?>
                                    </div>
                                </div>

                                <?php if(settings()->payment->codes_is_enabled): ?>
                                    <div class="mt-4">
                                        <button type="button" id="code_button" class="btn btn-block btn-outline-secondary border-gray-100"><?= language()->pay->custom_plan->code_button ?></button>

                                        <div style="display: none;" id="code_block">
                                            <div class="form-group">
                                                <label for="code"><i class="fa fa-fw fa-sm fa-tags mr-1"></i> <?= language()->pay->custom_plan->code ?></label>
                                                <input id="code" type="text" name="code" class="form-control" />
                                                <div id="code_help"></div>
                                            </div>
                                        </div>
                                    </div>

                                <?php ob_start() ?>
                                    <script>
                                        'use strict';

                                        document.querySelector('#code_button').addEventListener('click', event => {
                                            document.querySelector('#code_block').style.display = '';
                                            document.querySelector('#code_button').style.display = 'none';

                                            event.preventDefault();
                                        });

                                        /* Function to check the discount code */
                                        let check_code = () => {
                                            let code = document.querySelector('input[name="code"]').value;

                                            /* Reset */
                                            if(code.trim() == '') {
                                                document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                inpush.code = null;

                                                /* Change submit text */
                                                document.querySelector('#submit_default_text').classList.remove('d-none');
                                                document.querySelector('#submit_text').classList.add('d-none');

                                                calculate_prices();

                                                return;
                                            }

                                            fetch(`${url}pay/code`, {
                                                method: 'POST',
                                                body: JSON.stringify({
                                                    code, global_token, plan_id: inpush.plan_id
                                                }),
                                                headers: {
                                                    'Content-Type': 'application/json; charset=UTF-8'
                                                }
                                            })
                                                .then(response => {
                                                    return response.ok ? response.json() : Promise.reject(response);
                                                })
                                                .then(data => {
                                                    document.querySelector('#code_help').innerHTML = data.message;

                                                    if(data.status == 'success') {
                                                        document.querySelector('input[name="code"]').classList.add('is-valid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-invalid');
                                                        document.querySelector('#code_help').classList.add('valid-feedback');
                                                        document.querySelector('#code_help').classList.remove('invalid-feedback');

                                                        /* Set the code variable */
                                                        inpush.code = data.details.code;

                                                        /* Change submit text */
                                                        document.querySelector('#submit_default_text').classList.add('d-none');
                                                        document.querySelector('#submit_text').classList.remove('d-none');
                                                        document.querySelector('#submit_text').innerText = data.details.submit_text;

                                                    } else {
                                                        document.querySelector('input[name="code"]').classList.add('is-invalid');
                                                        document.querySelector('input[name="code"]').classList.remove('is-valid');
                                                        document.querySelector('#code_help').classList.add('invalid-feedback');
                                                        document.querySelector('#code_help').classList.remove('valid-feedback');

                                                        /* Set the code variable */
                                                        inpush.code = null;

                                                        /* Change submit text */
                                                        document.querySelector('#submit_default_text').classList.remove('d-none');
                                                        document.querySelector('#submit_text').classList.add('d-none');
                                                    }

                                                    calculate_prices();
                                                })
                                                .catch(error => {});

                                        };

                                        /* Writing handler on the input */
                                        let timer = null;
                                        let timer_function = () => {
                                            clearTimeout(timer);

                                            timer = setTimeout(() => {
                                                check_code();
                                            }, 500);
                                        }

                                        document.querySelector('input[name="code"]').addEventListener('change', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('paste', timer_function);
                                        document.querySelector('input[name="code"]').addEventListener('keyup', timer_function);

                                        /* Autofill code field on header query */
                                        let current_url = new URL(window.location.href);

                                        if(current_url.searchParams.get('code')) {
                                            document.querySelector('#code_button').click();
                                            document.querySelector('input[name="code"]').value = current_url.searchParams.get('code');
                                            check_code();
                                        }

                                    </script>
                                    <?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
                                <?php endif ?>

                            </div>

                            <div class="card-footer bg-white">
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span class="text-muted">
                                        <?= language()->pay->custom_plan->summary->total ?>
                                    </span>

                                    <div>
                                        <span id="summary_total"></span>

                                        <span class="text-muted"><?= settings()->payment->currency ?></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="mt-5">
                        <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary">
                            <span id="submit_default_text"><?= language()->pay->custom_plan->pay ?></span>
                            <span id="submit_text" class="d-none"><?= language()->pay->custom_plan->pay ?></span>
                        </button>
                    </div>

                    <div class="mt-3 text-muted text-center">
                        <small>
                            <?= sprintf(
                                language()->pay->accept,
                                '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . language()->global->terms_and_conditions . '</a>',
                                '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . language()->global->privacy_policy . '</a>'
                            ) ?>
                        </small>
                    </div>

                </div>
            </div>
        </form>

        <?php if(settings()->stripe->is_enabled): ?>
            <?php if($data->stripe_session): ?>
                <script src="https://js.stripe.com/v3/"></script>
        
                <script>
                    'use strict';
        
                    let stripe = Stripe(<?= json_encode(settings()->stripe->publishable_key) ?>);
        
                    stripe.redirectToCheckout({
                        sessionId: <?= json_encode($data->stripe_session->id) ?>,
                    }).then((result) => {
        
                        /* Nothing for the moment */
        
                    });
                </script>
            <?php endif ?>
        <?php endif ?>

    <?php endif ?>
</div>


<?php ob_start() ?>
<script>
    'use strict';

    /* Handlers */
    let check_payment_frequency = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;

        switch(payment_frequency) {
            case 'monthly':

                $('#summary_payment_frequency_monthly').show();
                $('#summary_payment_frequency_annual').hide();
                $('#summary_payment_frequency_lifetime').hide();

                if(inpush.payment_type_one_time_enabled) {
                    $('#one_time_type_label').show();
                } else {
                    $('#one_time_type_label').hide();
                }

                if(inpush.payment_type_recurring_enabled) {
                    $('#recurring_type_label').show();
                } else {
                    $('#recurring_type_label').hide();
                }

                break;

            case 'annual':

                $('#summary_payment_frequency_monthly').hide();
                $('#summary_payment_frequency_annual').show();
                $('#summary_payment_frequency_lifetime').hide();


                if(inpush.payment_type_one_time_enabled) {
                    $('#one_time_type_label').show();
                } else {
                    $('#one_time_type_label').hide();
                }

                if(inpush.payment_type_recurring_enabled) {
                    $('#recurring_type_label').show();
                } else {
                    $('#recurring_type_label').hide();
                }

                break;

            case 'lifetime':

                $('#summary_payment_frequency_monthly').hide();
                $('#summary_payment_frequency_annual').hide();
                $('#summary_payment_frequency_lifetime').show();

                /* Show only the one time payment option for the lifetime plan */
                $('#recurring_type_label').hide();
                $('#one_time_type_label').show();

                break;
        }

        $('[name="payment_type"]').filter(':visible:first').click();
    }

    $('[name="payment_frequency"]').on('change', event => {
        check_payment_frequency();
        check_payment_processor();
        calculate_prices();
    });

    let check_payment_processor = () => {
        let payment_processor = document.querySelector('[name="payment_processor"]:checked')?.value;

        if(!payment_processor) {
            return;
        }

        document.querySelectorAll(`[data-summary-payment-processor]:not([data-summary-payment-processor="${payment_processor}"])`).forEach(element => {
            element.classList.add('d-none');
        });

        document.querySelector(`[data-summary-payment-processor="${payment_processor}"]`).classList.remove('d-none');

        if(['offline_payment', 'coinbase', 'payu', 'yookassa'].includes(payment_processor)) {
            $('#recurring_type_label').hide();
            $('#one_time_type_label').show();
        }

        if(payment_processor == 'offline_payment') {
            $('#offline_payment_processor_wrapper').show();
        } else {
            $('#offline_payment_processor_wrapper').hide();
        }

        $('[name="payment_type"]').filter(':visible:first').click();
    };

    $('[name="payment_processor"]').on('change', event => {
        check_payment_frequency();
        check_payment_processor();
    });


    $('[name="payment_type"]').on('change', event => {
        let payment_type = document.querySelector('[name="payment_type"]:checked')?.value;

        switch(payment_type) {
            case 'one_time':

                $('#summary_payment_type_one_time').show();
                $('#summary_payment_type_recurring').hide();

                break;

            case 'recurring':

                $('#summary_payment_type_one_time').hide();
                $('#summary_payment_type_recurring').show();

                break;
        }
    });

    let calculate_prices = () => {
        let payment_frequency = document.querySelector('[name="payment_frequency"]:checked')?.value;

        let full_price = 0;
        let exclusive_taxes = 0;
        let price_without_inclusive_taxes = 0;
        let price_with_taxes = 0;

        full_price = inpush[`${payment_frequency}_price`];

        let price = parseFloat(full_price);

        /* Display the price */
        document.querySelector('#summary_plan_price').innerHTML = price;

        /* Display taxes by default */
        document.querySelector('#summary_taxes').classList.remove('d-none');

        /* Check for potential discounts */
        if(inpush.code) {
            inpush.code.discount = parseInt(inpush.code.discount);
            let discount_value = parseFloat((price * inpush.code.discount / 100).toFixed(2));

            price = price - discount_value;

            /* Show it on the summary */
            document.querySelector('#summary_discount').classList.remove('d-none');
            document.querySelector('#summary_discount .discount-value').innerHTML = nr(-discount_value, 2);

            /* Check for redeemable code */
            if(inpush.code.type == 'redeemable') {
                document.querySelector('#summary_taxes').classList.add('d-none');
            }
        } else {
            document.querySelector('#summary_discount').classList.add('d-none');
        }

        /* Calculate with taxes, if any */
        if(inpush.taxes && inpush.code?.type != 'redeemable') {

            /* Check for the inclusives */
            let inclusive_taxes_total_percentage = 0;

            for(let row of inpush.taxes) {
                if(row.type == 'exclusive') continue;

                inclusive_taxes_total_percentage += parseInt(row.value);
            }

            let total_inclusive_tax = parseFloat((price - (price / (1 + inclusive_taxes_total_percentage / 100))).toFixed(2));

            for(let row of inpush.taxes) {
                if(row.type == 'exclusive') continue;

                let percentage_of_total_inclusive_tax = parseInt(row.value) * 100 / inclusive_taxes_total_percentage;

                let inclusive_tax = parseFloat(total_inclusive_tax * percentage_of_total_inclusive_tax / 100).toFixed(2)

                /* Display the value of the tax */
                $(`#summary_tax_id_${row.tax_id} .tax-value`).html(nr(inclusive_tax, 2));

            }

            price_without_inclusive_taxes = price - total_inclusive_tax;

            /* Check for the exclusives */
            let exclusive_taxes_array = [];

            for(let row of inpush.taxes) {
                if(row.type == 'inclusive') continue;

                let exclusive_tax = parseFloat((row.value_type == 'percentage' ? price_without_inclusive_taxes * (parseInt(row.value) / 100) : parseFloat(row.value)).toFixed(2));

                exclusive_taxes_array.push(exclusive_tax);

                /* Display the value of the tax */
                if(row.value_type == 'percentage') {
                    $(`#summary_tax_id_${row.tax_id} .tax-value`).html(`+${nr(exclusive_tax, 2)}`);
                }

            }

            exclusive_taxes = exclusive_taxes_array.reduce((total, number) => total + number, 0);

            /* Price with all the taxes */
            price_with_taxes = price + exclusive_taxes;

            price = price_with_taxes;
        }

        /* Display the total */
        $('#summary_total').html(nr(price, 2));
    }

    /* Select default values */
    $('[name="payment_frequency"]:first').click();
    $('[name="payment_processor"]:first').click();
    $('[name="payment_type"]').filter(':visible:first').click();
</script>

																									  
																   

			
																	   
																  

							  
																		  
		   
			 

			  

<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
