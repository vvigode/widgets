<?php


namespace Inpush\Controllers;

use Inpush\Database\Database;
use Inpush\Middlewares\Authentication;

class Refer extends Controller {

    public function index() {

        Authentication::guard('guest');

        if(!\Inpush\Plugin::is_active('affiliate') || (\Inpush\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            redirect();
        }

        $referral_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        /* Get the owner user of the referral key */
        if(!$user = db()->where('referral_key', $referral_key)->getOne('users', ['user_id', 'plan_settings', 'status', 'referral_key'])) {
            redirect();
        }

        /* Make sure the user is still active */
        if($user->status != 1) {
            redirect();
        }

        /* Make sure the user has access to the affiliate program */
        $user->plan_settings = json_decode($user->plan_settings);
        if(!$user->plan_settings->affiliate_is_enabled) {
            redirect();
        }

        /* Set the cookie for 90 days */
        setcookie('referred_by', $user->referral_key, time()+60*60*24*90, COOKIE_PATH);

        /* Redirect to the landing page */
        $redirect = isset($_GET['redirect']) ? Database::clean_string($_GET['redirect']) : 'dashboard';
        redirect($redirect);

    }

}
