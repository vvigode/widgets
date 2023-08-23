<?php


namespace Inpush\Controllers;

class Sitemap extends Controller {

    public function index() {

        /* Set the header as xml so the browser can read it properly */
        header('Content-Type: text/xml');

        /* Get all custom pages from the database */
        $pages_result = database()->query("SELECT `url` FROM `pages` WHERE `type` = 'internal'");

        /* Main View */
        $data = [
            'pages_result' => $pages_result
        ];

        $view = new \Inpush\Views\View('sitemap/index', (array) $this);

        echo $view->run($data);

        die();
    }

}
