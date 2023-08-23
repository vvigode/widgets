<?php


namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Logger;
use Inpush\Middlewares\Authentication;
use Inpush\Models\User;

class ResetPassword extends Controller {

    public function index() {

        Authentication::guard('guest');

        $email = (isset($this->params[0])) ? $this->params[0] : null;
        $lost_password_code = (isset($this->params[1])) ? $this->params[1] : null;

        if(!$email || !$lost_password_code) redirect();

        /* Check if the lost password code is correct */
        $user = db()->where('email', $email)->where('lost_password_code', $lost_password_code)->getOne('users', ['user_id', 'name']);

        if($user->user_id < 1 || mb_strlen($lost_password_code) < 1) redirect();

        if(!empty($_POST)) {

            

            /* Check for any errors */
            if(mb_strlen($_POST['new_password']) < 6 || mb_strlen($_POST['new_password']) > 64) {
                Alerts::add_field_error('new_password', language()->global->error_message->password_length);
            }
            if($_POST['new_password'] !== $_POST['repeat_password']) {
                Alerts::add_field_error('repeat_password', language()->global->error_message->passwords_not_matching);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Encrypt the new password */
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                /* Update the password & empty the reset code from the database */
                db()->where('user_id', $user->user_id)->update('users', [
                    'password' => $new_password,
                    'twofa_secret' => null,
                    'lost_password_code' => null
                ]);

                Logger::users($user->user_id, 'reset_password.success');

                /* Set a nice success message */
                Alerts::add_success(language()->reset_password->success_message);

                /* Log the user in */
                $_SESSION['user_id'] = $user->user_id;
                (new User())->login_aftermath_update($user->user_id);
                Alerts::add_info(sprintf(language()->login->info_message->logged_in, $user->name));

                /* Clear the cache */
                \Inpush\Cache::$adapter->deleteItemsByTag('user_id=' . $user->user_id);

                redirect('login');
            }
        }

        /* Prepare the View */
        $data = [
            'values' => [
                'email' => $email
            ]
        ];

        $view = new \Inpush\Views\View('reset-password/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
