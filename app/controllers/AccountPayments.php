<?php


namespace Inpush\Controllers;

use Inpush\Middlewares\Authentication;

class AccountPayments extends Controller {

    public function index() {

        Authentication::guard();

        if(!settings()->payment->is_enabled) {
            redirect('dashboard');
        }

        /* Prepare the filtering system */
        $filters = (new \Inpush\Filters(['processor', 'type', 'frequency'], [], ['total_amount', 'datetime']));
        $filters->set_default_order_by('id', settings()->main->default_order_type);
        $filters->set_default_results_per_page(settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payments` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Inpush\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('account-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the payments list for the user */
        $payments = [];
        $payments_result = database()->query("SELECT `payments`.*, plans.`name` AS `plan_name` FROM `payments` LEFT JOIN plans ON `payments`.plan_id = plans.plan_id WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where('payments')} {$filters->get_sql_order_by('payments')} {$paginator->get_sql_limit()}");
        while($row = $payments_result->fetch_object()) $payments[] = $row;

        /* Export handler */
        process_export_json($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'email', 'name', 'processor', 'type', 'frequency', 'billing', 'taxes_ids', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'datetime']);
        process_export_csv($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'email', 'name', 'processor', 'type', 'frequency', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Inpush\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Establish the account header view */
        $menu = new \Inpush\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $data = [
            'payments' => $payments,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Inpush\Views\View('account-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
