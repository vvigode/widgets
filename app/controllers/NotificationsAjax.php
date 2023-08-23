<?php


namespace Inpush\Controllers;

use Inpush\Database\Database;
use Inpush\Date;
use Inpush\Middlewares\Authentication;
use Inpush\Middlewares\Csrf;
use Inpush\Notification;
use Inpush\Response;

class NotificationsAjax extends Controller {

    public function index() {

        Authentication::guard();

        if(!empty($_POST) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        if(!empty($_GET) && (Csrf::check('token') || Csrf::check('global_token')) && isset($_GET['request_type'])) {

            switch($_GET['request_type']) {

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        $_POST['notification_id'] = (int) $_POST['notification_id'];

        /* Get the current status */
        $is_enabled = db()->where('notification_id', $_POST['notification_id'])->getValue('notifications', 'is_enabled');

        /* Update data in database */
        db()->where('notification_id', $_POST['notification_id'])->where('user_id', $this->user->user_id)->update('notifications', [
            'is_enabled' => (int) !$is_enabled,
        ]);

        Response::json('', 'success');
    }

    private function read_data_conversion() {
        $_GET['notification_id'] = (int)$_GET['notification_id'];
        $_GET['id'] = (int)$_GET['id'];

        /* Get the current status */
        $user_id = db()->where('notification_id', $_GET['notification_id'])->getValue('notifications', 'user_id');

        if($user_id && $user_id == $this->user->user_id) {

            /* Get the data from the conversions table */
            $conversion = db()->where('id', $_GET['id'])->where('notification_id', $_GET['notification_id'])->getOne('track_conversions', ['type', 'data', 'location', 'url']);

            if($conversion) {

                $conversion->data = json_decode($conversion->data);
                $conversion->location = !empty($conversion->location) ? json_decode($conversion->location) : null;

                /* Generate the view */
                $data = [
                    'conversion' => $conversion,
                ];
                $view = new \Inpush\Views\View('notification/data/data.read_conversion.method', (array) $this);

                Response::json('', 'success', ['html' => $view->run($data)]);
            }

        }
    }

}
