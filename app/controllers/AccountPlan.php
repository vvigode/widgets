<?php


namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Logger;
use Inpush\Middlewares\Authentication;
use Inpush\Middlewares\Csrf;
use Inpush\Models\Model;
use Inpush\Models\Plan;
use Inpush\Models\User;
use Inpush\Response;

class AccountPlan extends Controller {

    public function index() {

        Authentication::guard();

        /* Establish the account header view */
        $menu = new \Inpush\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $view = new \Inpush\Views\View('account-plan/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function cancel_subscription() {

        Authentication::guard();

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('account-plan');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            try {
                (new User())->cancel_subscription($this->user->user_id);
            } catch (\Exception $exception) {
                Alerts::add_error($exception->getCode() . ':' . $exception->getMessage());
                redirect('account-plan');
            }

            /* Set a nice success message */
            Alerts::add_success(language()->account_plan->success_message->subscription_canceled);

            redirect('account-plan');

        }

    }

}
