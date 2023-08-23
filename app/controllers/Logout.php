<?php


namespace Inpush\Controllers;

use Inpush\Middlewares\Authentication;

class Logout extends Controller {

    public function index() {

        /* Exit admin impersonation */
        if(isset($_GET['admin_impersonate_user'])) {
            $admin_user_id = $_SESSION['admin_user_id'];

            /* Logout of the current users */
            Authentication::logout(false);

            /* Login as the admin */
            session_start();
            $_SESSION['user_id'] = $admin_user_id;

            redirect('admin/users');
        }

        /* Normal logout */
        else {
            Authentication::logout();
        }

    }

}
