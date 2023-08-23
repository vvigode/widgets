<?php


namespace Inpush\Controllers;

class NotFound extends Controller {

    public function index() {

        /* Custom 404 redirect if set */
        if(!empty(settings()->main->not_found_url)) {
            header('Location: ' . settings()->main->not_found_url); die();
        }

        header('HTTP/1.0 404 Not Found');

        $view = new \Inpush\Views\View('notfound/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}
