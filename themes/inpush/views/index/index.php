<?php defined('INPUSH') || die() ?>

<div class="index-background-container d-none d-lg-block">
    <div class="index-background-image"></div>
</div>

<div class="index-cover-container d-none d-lg-block">
    <div class="container container-disabled-simple">
        <div class="index-cover">
            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('INFORMATIONAL', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('LATEST_CONVERSION', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('RANDOM_REVIEW', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('EMAIL_COLLECTOR', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <div>
                        <?php $notification = \Inpush\Notification::get('SCORE_FEEDBACK', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                    <div class="mt-3">
                        <?php $notification = \Inpush\Notification::get('CONVERSIONS_COUNTER', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>
                </div>

                <div class="col-4">
                    <div>
                        <?php $notification = \Inpush\Notification::get('EMOJI_FEEDBACK', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                    <div class="mt-3">
                        <?php $notification = \Inpush\Notification::get('COOKIE_NOTIFICATION', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('SOCIAL_SHARE', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('REQUEST_COLLECTOR', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Inpush\Notification::get('LIVE_COUNTER', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="index-container">
    <div class="container">
        <?= \Inpush\Alerts::output_alerts() ?>

        <div class="row  mt-8">
            <div class="col">
                <div class="text-left">
                    <h1 class="index-header mb-4" data-aos="fade-down"><?= language()->index->header ?></h1>
                    <p class="index-subheader text-muted mb-5" data-aos="fade-down" data-aos-delay="200"><?= sprintf(language()->index->subheader, $data->total_notifications) ?></p>

                    <div data-aos="fade-down" data-aos-delay="300">
                        <a href="<?= url('register') ?>" class="btn btn-primary index-button"><?= language()->index->sign_up ?></a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<div class="container mt-10">
    <div class="text-center" data-aos="fade">
        <h2 class="text-gray-700"><?= language()->index->steps->header ?></h2>
    </div>

    <div class="row mt-8">

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column align-items-center mb-5 mb-md-0" data-aos="fade-down" data-aos-delay="200">
                <div class="index-icon-container mb-4">
                    <i class="fa fa-fw fa-plug"></i>
                </div>

                <h3 class="h4 mb-3 text-center"><?= language()->index->steps->one ?></h3>

                <p class="text-muted text-center"><?= language()->index->steps->one_text ?></p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column align-items-center mb-5 mb-md-0" data-aos="fade-down" data-aos-delay="300">
                <div class="index-icon-container mb-4">
                    <i class="fa fa-fw fa-plus"></i>
                </div>

                <h3 class="h4 mb-3 text-center"><?= language()->index->steps->two ?></h3>

                <p class="text-muted text-center"><?= language()->index->steps->two_text ?></p>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex flex-column align-items-center mb-5 mb-md-0" data-aos="fade-down" data-aos-delay="400">
                <div class="index-icon-container mb-4">
                    <i class="fa fa-fw fa-money-bill-wave"></i>
                </div>

                <h3 class="h4 mb-3 text-center"><?= language()->index->steps->three ?></h3>

                <p class="text-muted text-center"><?= language()->index->steps->three_text ?></p>
            </div>
        </div>
    </div>
</div>


<div class="container mt-10">
    <div class="text-center">
        <h2 class="text-gray-700"><?= language()->index->setup->header ?></h2>
        <p class="text-muted mt-3"><?= language()->index->setup->description ?></p>
    </div>

    <div class="row mt-5 d-flex align-items-center">
        <div class="col-md-3 col-sm-6 col-12 mb-5 mb-md-0 text-center">
            <img src="<?= ASSETS_FULL_URL . 'images/shopify_logo.svg' ?>" class="zoomer" alt="<?= language()->index->setup->shopify ?>">
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-5 mb-md-0 text-center">
            <img src="<?= ASSETS_FULL_URL . 'images/worpress_logo.svg' ?>" class="zoomer" alt="<?= language()->index->setup->wordpress ?>">
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-5 mb-md-0 text-center">
            <img src="<?= ASSETS_FULL_URL . 'images/zapier_logo.svg' ?>" class="zoomer" alt="<?= language()->index->setup->zapier ?>">
        </div>
        <div class="col-md-3 col-sm-6 col-12 mb-5 mb-md-0 text-center">
            <img src="<?= ASSETS_FULL_URL . 'images/tilda_logo.png' ?>" class="zoomer" alt="Tilda">
        </div>
    </div>

</div>

<div class="py-5 bg-gray-100 mt-10">
    <div class="container">
        <div class="text-center">
            <h2><?= sprintf(language()->index->tools->header, nr($data->total_track_notifications)) ?></h2>

            <p class="text-muted mt-5"><?= language()->index->tools->subheader ?></p>
        </div>
    </div>
</div>

<div class="container mt-8">

    <div class="mb-3 d-flex justify-content-between align-items-center flex-column flex-md-row">
        <div>
            <h3><span class="underline"><?= language()->index->tools->preview ?></span></h3>
            <p class="text-muted"><?= language()->index->tools->preview_description ?></p>
        </div>

        <div id="notification_preview" class="container-disabled-simple"></div>
    </div>

    <div class="mt-8 row d-flex align-items-center">

        <?php foreach($data->notifications as $notification_type => $notification_config): ?>

            <?php $notification = \Inpush\Notification::get($notification_type) ?>

            <label class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4 custom-radio-box mb-3">

                <input type="radio" name="type" value="<?= $notification_type ?>" class="custom-control-input" required="required">

                <div class="card shadow-lg zoomer h-100">
                    <div class="card-body">

                        <div class="mb-3 text-center">
                            <span class="custom-radio-box-main-icon"><i class="<?= language()->notification->{mb_strtolower($notification_type)}->icon ?>"></i></span>
                        </div>

                        <div class="card-title font-weight-bold text-center"><?= language()->notification->{mb_strtolower($notification_type)}->name ?></div>

                        <p class="text-muted text-center"><?= language()->notification->{mb_strtolower($notification_type)}->description ?></p>

                    </div>
                </div>

                <div class="preview" style="display: none">
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

            </label>

            <?php if($notification_type == 'ENGAGEMENT_LINKS'): ?>
            <?php ob_start() ?>
                <script>
                    $('.inpush-engagement-links-wrapper .inpush-engagement-links-hidden').removeClass('inpush-engagement-links-hidden').addClass('inpush-engagement-links-shown');
                </script>
                <?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
            <?php endif ?>

        <?php endforeach ?>

    </div>
</div>

<div class="container mt-10">
    <div class="text-center mb-8">
        <h2><?= language()->index->pricing->header ?></h2>

        <p class="text-muted mt-5"><?= language()->index->pricing->subheader ?></p>
    </div>

    <?= $this->views['plans'] ?>
</div>

<?php if(settings()->users->register_is_enabled): ?>
<div class="index-register-container mt-9">
    <div class="container">
        <div class="d-flex flex-row justify-content-around">
            <div>
                <h2><?= language()->index->cta->header ?></h2>
                <p><?= language()->index->cta->subheader ?></p>
            </div>

            <div>
                <a href="<?= url('register') ?>" class="btn btn-outline-light index-button"><?= language()->index->cta->sign_up ?></a>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<?php ob_start() ?>
<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/aos.min.css' ?>">
<link href="<?= ASSETS_FULL_URL . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Inpush\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js' ?>"></script>

<script>
    AOS.init({
        delay: 100,
        duration: 600
    });

    /* Preview handler */
    $('input[name="type"]').on('change', (event, first_trigger = false) => {

        let preview_html = $(event.currentTarget).closest('label').find('.preview').html();

        $('#notification_preview').hide().html(preview_html).fadeIn();

        /* Make sure its not the first check */
        if(!first_trigger) {
            document.querySelector('#notification_preview').scrollIntoView();
        }

    });

    /* Select a default option */
    $('input[name="type"]:first').attr('checked', true).trigger('change', true);
</script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>

