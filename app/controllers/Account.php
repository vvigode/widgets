<?php

namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Database\Database;
use Inpush\Middlewares\Authentication;
use Inpush\Middlewares\Csrf;
use Inpush\Models\User;

class Account extends Controller {

    public function index() {

        Authentication::guard();

        /* Prepare the TwoFA codes just in case we need them */
        $twofa = new \RobThree\Auth\TwoFactorAuth(settings()->main->title, 6, 30);
        $twofa_secret = $twofa->createSecret();
        $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->name, $twofa_secret);

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['email']		= mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);
            $_POST['name']		= mb_substr(filter_var($_POST['name'], FILTER_SANITIZE_STRING), 0, 64);
            $_POST['timezone']  = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? Database::clean_string($_POST['timezone']) : settings()->main->default_timezone;
            $_POST['twofa_is_enabled']  = (bool) $_POST['twofa_is_enabled'];
            $_POST['twofa_token']       = trim(filter_var($_POST['twofa_token'], FILTER_SANITIZE_STRING));
            $twofa_secret               = $_POST['twofa_is_enabled'] ? $this->user->twofa_secret : null;

            /* Billing */
            if(empty($this->user->payment_subscription_id)) {
                $_POST['billing_type'] = in_array($_POST['billing_type'], ['personal', 'business']) ? Database::clean_string($_POST['billing_type']) : 'personal';
                $_POST['billing_name'] = mb_substr(trim(Database::clean_string($_POST['billing_name'])), 0, 128);
                $_POST['billing_address'] = mb_substr(trim(Database::clean_string($_POST['billing_address'])), 0, 128);
                $_POST['billing_city'] = mb_substr(trim(Database::clean_string($_POST['billing_city'])), 0, 64);
                $_POST['billing_county'] = mb_substr(trim(Database::clean_string($_POST['billing_county'])), 0, 64);
                $_POST['billing_zip'] = mb_substr(trim(Database::clean_string($_POST['billing_zip'])), 0, 32);
                $_POST['billing_country'] = array_key_exists($_POST['billing_country'], get_countries_array()) ? Database::clean_string($_POST['billing_country']) : 'US';
                $_POST['billing_phone'] = mb_substr(trim(Database::clean_string($_POST['billing_phone'])), 0, 32);
                $_POST['billing_tax_id'] = $_POST['billing_type'] == 'business' ? mb_substr(trim(Database::clean_string($_POST['billing_tax_id'])), 0, 64) : '';
                $_POST['billing'] = json_encode([
                    'type' => $_POST['billing_type'],
                    'name' => $_POST['billing_name'],
                    'address' => $_POST['billing_address'],
                    'city' => $_POST['billing_city'],
                    'county' => $_POST['billing_county'],
                    'zip' => $_POST['billing_zip'],
                    'country' => $_POST['billing_country'],
                    'phone' => $_POST['billing_phone'],
                    'tax_id' => $_POST['billing_tax_id'],
                ]);
            }
            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                Alerts::add_field_error('email', language()->register->error_message->invalid_email);
            }
            if(db()->where('email', $_POST['email'])->has('users') && $_POST['email'] !== $this->user->email) {
                Alerts::add_field_error('email', language()->register->error_message->email_exists);
            }

            if(mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 64) {
                Alerts::add_field_error('name', language()->register->error_message->name_length);
            }

            if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                if(!password_verify($_POST['old_password'], $this->user->password)) {
                    Alerts::add_field_error('old_password', language()->account->error_message->invalid_current_password);
                }
                if(mb_strlen($_POST['new_password']) < 6 || mb_strlen($_POST['new_password']) > 64) {
                    Alerts::add_field_error('new_password', language()->global->error_message->password_length);
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    Alerts::add_field_error('repeat_password', language()->global->error_message->passwords_not_matching);
                }
            }

            if($_POST['twofa_is_enabled'] && $_POST['twofa_token']) {
                $twofa_check = $twofa->verifyCode($_SESSION['twofa_potential_secret'], $_POST['twofa_token']);

                if(!$twofa_check) {
                    Alerts::add_field_error('twofa_token', language()->account->error_message->twofa_check);

                    /* Regenerate */
                    $twofa_secret = $twofa->createSecret();
                    $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->name, $twofa_secret);

                } else {
                    $twofa_secret = $_SESSION['twofa_potential_secret'];
                }

            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Only update the billing if no active subscriptions are found */
                if(!empty($this->user->payment_subscription_id)) {
                    $_POST['billing'] = json_encode($this->user->billing);
                }

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'name' => $_POST['name'],
                    'billing' => $_POST['billing'],
                    'timezone' => $_POST['timezone'],
                    'twofa_secret' => $twofa_secret
                ]);

                /* Set a nice success message */
                Alerts::add_success(language()->account->success_message->account_updated);

                /* Check for an email address change */
                if($_POST['email'] != $this->user->email) {

                    if(settings()->users->email_confirmation) {
                        $email_activation_code = md5($_POST['email'] . microtime());

                        /* Prepare the email */
                        $email_template = get_email_template(
                            [],
                            language()->global->emails->user_pending_email->subject,
                            [
                                '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_activation_code . '&type=user_pending_email'),
                                '{{NAME}}' => $this->user->name,
                                '{{CURRENT_EMAIL}}' => $this->user->email,
                                '{{NEW_EMAIL}}' => $_POST['email']
                            ],
                            language()->global->emails->user_pending_email->body
                        );

                        send_mail($_POST['email'], $email_template->subject, $email_template->body);

                        /* Save the potential new email as pending */
                        db()->where('user_id', $this->user->user_id)->update('users', [
                            'pending_email' => $_POST['email'],
                            'email_activation_code' => $email_activation_code,
                        ]);

                        Alerts::add_info(language()->account->info_message->user_pending_email);

                    } else {

                        /* Save the new email without verification */
                        db()->where('user_id', $this->user->user_id)->update('users', ['email' => $_POST['email']]);

                    }

                }

                if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    db()->where('user_id', $this->user->user_id)->update('users', ['password' => $new_password]);

                    /* Logout of the user */
                    Authentication::logout(false);

                    /* Start a new session to set a success message */
                    session_start();

                    /* Set a nice success message */
                    Alerts::add_success(language()->account->success_message->password_updated);

                    redirect('login');
                }

                /* Clear the cache */
                \Inpush\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account');
            }

        }

        /* Store the potential secret */
        $_SESSION['twofa_potential_secret'] = $twofa_secret;

        /* Establish the account header view */
        $menu = new \Inpush\Views\View('partials/account_header', (array) $this);
        $this->add_view_content('account_header', $menu->run());

        /* Prepare the View */
        $data = [
            'twofa_secret'  => $twofa_secret,
            'twofa_image'   => $twofa_image
        ];

        $view = new \Inpush\Views\View('account/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
