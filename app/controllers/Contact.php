<?php


namespace Inpush\Controllers;

use Inpush\Alerts;
use Inpush\Captcha;
use Inpush\Middlewares\Authentication;
use Inpush\Middlewares\Csrf;

class Contact extends Controller {

    public function index() {

        if(!settings()->email_notifications->contact || empty(settings()->email_notifications->emails)) {
            redirect();
        }

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            $_POST['name'] = mb_substr(trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING)), 0, 64);
            $_POST['email'] = mb_substr(trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)), 0, 320);
            $_POST['subject'] = mb_substr(trim(filter_var($_POST['subject'], FILTER_SANITIZE_STRING)), 0, 128);
            $_POST['message'] = mb_substr(trim(filter_var($_POST['message'], FILTER_SANITIZE_STRING)), 0, 2048);

            

            /* Check for any errors */
            $required_fields = ['name', 'email', 'subject', 'message'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            if(settings()->captcha->contact_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', language()->global->error_message->invalid_captcha);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Prepare the email */
                $email_template = get_email_template(
                    [
                        '{{NAME}}' => $_POST['name'],
                        '{{SUBJECT}}' => $_POST['subject'],
                    ],
                    language()->global->emails->admin_contact->subject,
                    [
                        '{{NAME}}' => $_POST['name'],
                        '{{EMAIL}}' => $_POST['email'],
                        '{{MESSAGE}}' => $_POST['message'],
                    ],
                    language()->global->emails->admin_contact->body
                );

                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

                /* Set a nice success message */
                Alerts::add_success(language()->contact->success_message);

                redirect('contact');
            }
        }

        $values = [
            'name' => Authentication::check() ? $this->user->name : ($_POST['name'] ??  ''),
            'email' => Authentication::check() ? $this->user->email : ($_POST['email'] ??  ''),
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];

        /* Prepare the View */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
        ];

        $view = new \Inpush\Views\View('contact/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


