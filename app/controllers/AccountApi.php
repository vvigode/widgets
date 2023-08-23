<?php

namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Middlewares\Authentication;
use Inpush\Middlewares\Csrf;

class AccountApi extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $api_key = md5($this->user->email . microtime() . microtime());

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', ['api_key' => $api_key]);

                /* Set a nice success message */
                Alerts::add_success(language()->account_api->success_message);

                /* Clear the cache */
                \Inpush\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account-api');
            }

        }

        /* Establish the account sub menu view */
        $menu = new \Inpush\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $view = new \Inpush\Views\View('account-api/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
