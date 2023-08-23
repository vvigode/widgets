<?php defined('INPUSH') || die() ?>

<header class="header pb-0">
    <div class="container">
        <?= $this->views['account_header'] ?>
    </div>
</header>

<section class="container pt-5">

    <?= \Inpush\Alerts::output_alerts() ?>

    <div class="row mb-3">
        <div class="col-12 col-lg mb-3 mb-lg-0">
            <h2 class="h4"><?= language()->account_logs->header ?></h2>
            <p class="text-muted m-0"><?= language()->account_logs->subheader ?></p>
        </div>

        <?php if(count($data->logs) || count($data->filters->get)): ?>
            <div class="col-12 col-lg-auto d-flex">
                <div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" title="<?= language()->global->export ?>">
                            <i class="fa fa-fw fa-sm fa-download"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right d-print-none">
                            <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                                <i class="fa fa-fw fa-sm fa-file-csv mr-1"></i> <?= language()->global->export_csv ?>
                            </a>
                            <a href="<?= url('account-logs?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                                <i class="fa fa-fw fa-sm fa-file-code mr-1"></i> <?= language()->global->export_json ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="ml-3">
                    <div class="dropdown">
                        <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                        <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                            <div class="dropdown-header d-flex justify-content-between">
                                <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                                <?php if(count($data->filters->get)): ?>
                                    <a href="<?= url('account-logs') ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
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
                                        <option value="type" <?= $data->filters->search_by == 'type' ? 'selected="selected"' : null ?>><?= language()->account_logs->logs->type ?></option>
                                        <option value="ip" <?= $data->filters->search_by == 'ip' ? 'selected="selected"' : null ?>><?= language()->account_logs->logs->ip ?></option>
                                    </select>
                                </div>

                                <div class="form-group px-4">
                                    <label for="filters_order_by" class="small"><?= language()->global->filters->order_by ?></label>
                                    <select name="order_by" id="filters_order_by" class="form-control form-control-sm">
                                        <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
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
        <?php endif ?>
    </div>

    <?php if(count($data->logs)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= language()->account_logs->logs->type ?></th>
                    <th><?= language()->account_logs->logs->ip ?></th>
                    <th><?= language()->account_logs->logs->details ?></th>
                    <th><?= language()->account_logs->logs->datetime ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->logs as $row): ?>
                    <tr>
                        <td class="text-nowrap"><?= $row->type ?></td>
                        <td class="text-nowrap"><?= $row->ip ?></td>
                        <td class="text-nowrap">
                            <?php if($row->device_type): ?>
                                <span class="mr-2" data-toggle="tooltip" title="<?= $row->device_type ?>">
                                    <i class="fa fa-fw fa-sm fa-<?= $row->device_type ?> text-muted"></i>
                                </span>
                            <?php endif ?>

                            <?php if($row->os_name): ?>
                                <span class="mr-2" data-toggle="tooltip" title="<?= $row->os_name ?>">
                                    <i class="fa fa-fw fa-sm fa-server text-muted"></i>
                                </span>
                            <?php endif ?>

                            <?php if($row->country_code): ?>
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($row->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-2" data-toggle="tooltip" title="<?= get_country_from_country_code($row->country_code) ?>" />
                            <?php endif ?>
                        </td>
                        <td class="text-nowrap"><span class="text-muted" data-toggle="tooltip" title="<?= \Inpush\Date::get($row->datetime, 1) ?>"><?= \Inpush\Date::get_timeago($row->datetime) ?></span></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-7 col-lg-5 mb-3" alt="<?= language()->account_logs->logs->no_data ?>" />
            <h2 class="h4 text-muted mt-3"><?= language()->account_logs->logs->no_data ?></h2>
            <p class="text-muted"><?= language()->account_logs->logs->no_data_help ?></p>
        </div>
    <?php endif ?>

</section>
