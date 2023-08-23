<?php


namespace Inpush\Controllers;

use Inpush\Title;

class ApiDocumentation extends Controller {

    public function index() {

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function user() {

        Title::set(language()->api_documentation->user->title);

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/user', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function campaigns() {

        Title::set(language()->api_documentation->campaigns->title);

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/campaigns', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function notifications() {

        Title::set(language()->api_documentation->notifications->title);

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/notifications', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function payments() {

        Title::set(language()->api_documentation->payments->title);

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/payments', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function users_logs() {

        Title::set(language()->api_documentation->users_logs->title);

        /* Prepare the View */
        $view = new \Inpush\Views\View('api-documentation/users_logs', (array) $this);

        $this->add_view_content('content', $view->run());

    }
}


