<?php


namespace Inpush\Controllers;

class Affiliate extends Controller {

    public function index() {

        if(!\Inpush\Plugin::is_active('affiliate') || (\Inpush\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            redirect();
        }

        /* Prepare the View */
        $view = new \Inpush\Views\View('affiliate/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

}


