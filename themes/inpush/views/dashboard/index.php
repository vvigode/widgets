<?php defined('INPUSH') || die() ?>

<header class="header">
    <div class="container">

        <div class="d-flex justify-content-between">
            <h1 class="h2"><span class="underline"><?= language()->dashboard->header ?></span></h1>
        </div>

        <p>
            <span class="badge badge-success"><?= sprintf(language()->account->plan->header, $this->user->plan->name) ?></span>

            <?php if($this->user->plan_id != 'free'): ?>
                <span><?= sprintf(language()->account->plan->subheader, '<strong>' . \Inpush\Date::get($this->user->plan_expiration_date, 2) . '</strong>') ?></span>
            <?php endif ?>

            <?php if(settings()->payment->is_enabled): ?>
                <span>(<a href="<?= url('plan/upgrade') ?>"><?= language()->account->plan->renew ?></a>)</span>
            <?php endif ?>
        </p>

        <?php if($this->user->plan_settings->notifications_impressions_limit != -1): ?>
            <?php
            $progress_percentage = $this->user->plan_settings->notifications_impressions_limit == '0' ? 100 : ($this->user->current_month_notifications_impressions / $this->user->plan_settings->notifications_impressions_limit) * 100;
            $progress_class = $progress_percentage > 60 ? ($progress_percentage > 85 ? 'badge-danger' : 'badge-warning') : 'badge-success';
            ?>
            <p class="text-muted">
                <?=
                    sprintf(language()->account->plan->notifications_impressions_limit,
                        '<span class="badge ' . $progress_class . '">' . nr($progress_percentage) . '%</span>',
                        nr($this->user->plan_settings->notifications_impressions_limit)
                    );
                ?>
            </p>
        <?php endif ?>

    </div>
</header>

<section class="container">

    <?= \Inpush\Alerts::output_alerts() ?>

    <div class="mt-5 d-flex justify-content-between">
        <h2 class="h3"><?= language()->dashboard->campaigns->header ?></h2>

        <div class="col-auto p-0 d-flex">
            <div>
                <?php if($this->user->plan_settings->campaigns_limit != -1 && $data->campaigns_total >= $this->user->plan_settings->campaigns_limit): ?>
                    <button type="button" data-toggle="tooltip" title="<?= language()->campaign->error_message->campaigns_limit ?>" class="btn btn-primary disabled">
                        <i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->dashboard->campaigns->create ?>
                    </button>
                <?php else: ?>
                    <button type="button" data-toggle="modal" data-target="#create_campaign" class="btn btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= language()->dashboard->campaigns->create ?></button>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                            <?php if(count($data->filters->get)): ?>
                                <a href="<?= url('dashboard') ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= language()->global->filters->search ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= language()->global->filters->search_by ?></label>
                                <select name="search_by" id="filters_search_by" class="form-control form-control-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= language()->dashboard->filters->search_by_name ?></option>
                                    <option value="domain" <?= $data->filters->search_by == 'domain' ? 'selected="selected"' : null ?>><?= language()->dashboard->filters->search_by_domain ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_enabled" class="small"><?= language()->global->filters->status ?></label>
                                <select name="is_enabled" id="filters_is_enabled" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= language()->global->active ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= language()->global->disabled ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= language()->global->filters->order_by ?></label>
                                <select name="order_by" id="filters_order_by" class="form-control form-control-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= language()->dashboard->filters->order_by_name ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= language()->global->filters->order_type ?></label>
                                <select name="order_type" id="filters_order_type" class="form-control form-control-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_asc ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_desc ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= language()->global->filters->results_per_page ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="form-control form-control-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= language()->global->submit ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->campaigns)): ?>
        <div class="table-responsive table-custom-container mt-3">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= language()->dashboard->campaigns->name ?></th>
                    <th class="d-none d-md-table-cell"><?= language()->dashboard->campaigns->date ?></th>
                    <th><?= language()->dashboard->campaigns->is_enabled ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <?php foreach($data->campaigns as $row): ?>
                    <?php
                    $row->branding = json_decode($row->branding);

                    $icon = new \Jdenticon\Identicon([
                        'value' => $row->domain,
                        'size' => 50,
                        'style' => [
                            'hues' => [235],
                            'backgroundColor' => '#86444400',
                            'colorLightness' => [0.41, 0.80],
                            'grayscaleLightness' => [0.30, 0.70],
                            'colorSaturation' => 0.85,
                            'grayscaleSaturation' => 0.40,
                        ]
                    ]);
                    $row->icon = $icon->getImageDataUri();

                    ?>
                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex">
                                <img src="<?= $row->icon ?>" class="campaign-avatar rounded-circle mr-3" alt="" />

                                <div class="d-flex flex-column">
                                    <a href="<?= url('campaign/' . $row->campaign_id) ?>"><?= $row->name ?></a>

                                    <span class="text-muted">
                                        <?= $row->domain ?>
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="text-nowrap d-none d-md-table-cell"><span class="text-muted" data-toggle="tooltip" title="<?= \Inpush\Date::get($row->datetime) ?>"><?= \Inpush\Date::get($row->datetime, 2) ?></span></td>
                        <td class="text-nowrap">
                            <div class="d-flex">
                                <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= language()->dashboard->campaigns->is_enabled_tooltip ?>">
                                    <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="campaign_is_enabled_<?= $row->campaign_id ?>"
                                            data-row-id="<?= $row->campaign_id ?>"
                                            onchange="ajax_call_helper(event, 'campaigns-ajax', 'is_enabled_toggle')"
                                        <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                    >
                                    <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $row->campaign_id ?>"></label>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <div class="dropdown">
                                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                    <i class="fa fa-fw fa-ellipsis-v"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="<?= url('campaign/' . $row->campaign_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-server mr-1"></i> <?= language()->global->view ?></a>
                                    <a href="<?= url('campaign/' . $row->campaign_id . '/statistics') ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-chart-bar mr-1"></i> <?= language()->campaign->statistics->link ?></a>
                                    <a href="#" data-toggle="modal" data-target="#update_campaign" data-campaign-id="<?= $row->campaign_id ?>" data-name="<?= $row->name ?>" data-domain="<?= $row->domain ?>" data-include-subdomains="<?= (bool) $row->include_subdomains ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= language()->global->edit ?></a>

                                    <a
                                        href="#"
                                        data-toggle="modal"
                                        data-target="#campaign_pixel_key"
                                        data-pixel-key="<?= $row->pixel_key ?>"
                                        data-campaign-id="<?= $row->campaign_id ?>"
                                        class="dropdown-item"
                                    ><i class="fa fa-fw fa-sm fa-code mr-1"></i> <?= language()->campaign->header->pixel_key ?></a>

                                    <?php if($this->user->plan_settings->custom_branding): ?>
                                        <a href="#" data-toggle="modal" data-target="#custom_branding_campaign" data-campaign-id="<?= $row->campaign_id ?>" data-branding-name="<?= $row->branding->name ?? '' ?>" data-branding-url="<?= $row->branding->url ?? '' ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-random mr-1"></i> <?= language()->campaign->header->custom_branding ?></a>
                                    <?php endif ?>

                                    <a href="#" data-toggle="modal" data-target="#campaign_delete_modal" data-campaign-id="<?= $row->campaign_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
                                </div>
                            </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>

    <?php else: ?>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= language()->global->no_data ?>" />
            <h2 class="h4 text-muted"><?= language()->global->no_data ?></h2>
            <p><?= language()->dashboard->campaigns->no_data ?></a></p>
        </div>

    <?php endif ?>

</section>

<?php \Inpush\Event::add_content(include_view(THEME_PATH . 'views/campaign/create_campaign_modal.php'), 'modals'); ?>
<?php \Inpush\Event::add_content(include_view(THEME_PATH . 'views/campaign/campaign_delete_modal.php'), 'modals'); ?>
<?php \Inpush\Event::add_content(include_view(THEME_PATH . 'views/campaign/campaign_pixel_key_modal.php'), 'modals'); ?>
<?php \Inpush\Event::add_content(include_view(THEME_PATH . 'views/campaign/update_campaign_modal.php'), 'modals'); ?>
<?php \Inpush\Event::add_content(include_view(THEME_PATH . 'views/campaign/custom_branding_campaign_modal.php'), 'modals'); ?>

<?php ob_start() ?>
    <script>
        $('[data-delete]').on('click', event => {
            let message = $(event.currentTarget).attr('data-delete');

            if(!confirm(message)) return false;

            /* Continue with the deletion */
            ajax_call_helper(event, 'campaigns-ajax', 'delete', () => {

                /* On success delete the actual row from the DOM */
                $(event.currentTarget).closest('tr').remove();

            });

            event.preventDefault();
        });

        <?php if(isset($_GET['pixel_key_modal'])): ?>
        /* Open the pixel key modal */
        $('[data-campaign-id="<?= (int) $_GET['pixel_key_modal'] ?>"][data-pixel-key]').trigger('click');
        <?php endif ?>

    </script>
<?php \Inpush\Event::add_content(ob_get_clean(), 'javascript') ?>
