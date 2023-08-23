<?php


namespace Inpush\Controllers;

use Inpush\Middlewares\Authentication;

class Dashboard extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Inpush\Filters(['is_enabled'], ['name', 'domain'], ['name', 'datetime']));
        $filters->set_default_order_by('campaign_id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `campaigns` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Inpush\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('dashboard?' . $filters->get_get() . '&page=%d')));

        /* Get the campaigns list for the user */
        $campaigns = [];
        $campaigns_result = database()->query("SELECT * FROM `campaigns` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $campaigns_result->fetch_object()) $campaigns[] = $row;

        /* Prepare the pagination view */
        $pagination = (new \Inpush\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'campaigns' => $campaigns,
            'campaigns_total' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Inpush\Views\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
