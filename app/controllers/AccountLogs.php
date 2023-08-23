<?php


namespace Inpush\Controllers;

use Inpush\Database\Database;
use Inpush\Middlewares\Authentication;

class AccountLogs extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Inpush\Filters(['user_id'], ['type', 'ip', 'country_code', 'device_type'], ['datetime']));
        $filters->set_default_order_by('id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `users_logs` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Inpush\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('account-logs?' . $filters->get_get() . 'page=%d')));

        /* Get the logs list for the user */
        $logs = [];
        $logs_result = database()->query("SELECT * FROM `users_logs` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $logs_result->fetch_object()) $logs[] = $row;

        /* Export handler */
        process_export_json($logs, 'include', ['id', 'user_id', 'type', 'ip', 'country_code', 'device_type', 'datetime']);
        process_export_csv($logs, 'include', ['id', 'user_id', 'type', 'ip', 'country_code', 'device_type', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Inpush\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account header view */
        $menu = new \Inpush\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $data = [
            'logs' => $logs,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Inpush\Views\View('account-logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


}
