<?php


const INPUSH = 1;
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');
const APP_PATH = ROOT_PATH . 'app/';
const PLUGINS_PATH = ROOT_PATH . 'plugins/';
const THEME_PATH = ROOT_PATH . 'themes/inpush/';
const THEME_URL_PATH = 'themes/inpush/';
const ASSETS_PATH = THEME_PATH . 'assets/';
const ASSETS_URL_PATH = THEME_URL_PATH . 'assets/';
const UPLOADS_PATH = ROOT_PATH . 'uploads/';
const UPLOADS_URL_PATH = 'uploads/';
const CACHE_DEFAULT_SECONDS = 2592000;

/* Config file */
require_once ROOT_PATH . 'config.php';

/* Establish cookie / session on this path specifically */
define('COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', SITE_URL));

/* Determine if we should set the samesite=strict */
$samesite_strict = isset($_GET['inpush']) && mb_strpos($_GET['inpush'], 'pixel') === 0;

session_set_cookie_params([
    'lifetime' => null,
    'path' => COOKIE_PATH,
    'samesite' => $samesite_strict ? 'Strict' : 'Lax'
]);

/* Only start a session handler if we need to */
$should_start_session = !isset($_GET['inpush'])
    || (
        isset($_GET['inpush'])
        && !(mb_strpos($_GET['inpush'], 'pixel') === 0)
        && !(mb_strpos($_GET['inpush'], 'pixel-track') === 0)
        && !(mb_strpos($_GET['inpush'], 'cron') === 0)
        && !(mb_strpos($_GET['inpush'], 'sitemap') === 0)
    );


if($should_start_session) {
    session_start();
}

/* Starting to include the required files */
require_once APP_PATH . 'includes/debug.php';
require_once APP_PATH . 'includes/product.php';

/* Load the traits */
require_once APP_PATH . 'traits/Paramsable.php';
require_once APP_PATH . 'traits/Apiable.php';

/* Require the core files */
require_once APP_PATH . 'core/App.php';
require_once APP_PATH . 'core/Router.php';
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/Model.php';
require_once APP_PATH . 'core/View.php';
require_once APP_PATH . 'core/Middleware.php';
require_once APP_PATH . 'core/Language.php';
require_once APP_PATH . 'core/Settings.php';
require_once APP_PATH . 'core/Title.php';
require_once APP_PATH . 'core/Meta.php';
require_once APP_PATH . 'core/Database.php';
require_once APP_PATH . 'core/MysqliDb.php';
require_once APP_PATH . 'core/Logger.php';
require_once APP_PATH . 'core/Plugin.php';

/* Load the middlewares */
require_once APP_PATH . 'middlewares/Authentication.php';
require_once APP_PATH . 'middlewares/Csrf.php';

/* Load the models */
require_once APP_PATH . 'models/Plan.php';
require_once APP_PATH . 'models/Page.php';
require_once APP_PATH . 'models/User.php';
require_once APP_PATH . 'models/Payments.php';
require_once APP_PATH . 'models/Settings.php';
require_once APP_PATH . 'models/Campaign.php';

/* Load some helpers */
require_once APP_PATH . 'helpers/Cache.php';
require_once APP_PATH . 'helpers/ThemeStyle.php';
require_once APP_PATH . 'helpers/Event.php';
require_once APP_PATH . 'helpers/Notification.php';
require_once APP_PATH . 'helpers/Date.php';
require_once APP_PATH . 'helpers/Captcha.php';
require_once APP_PATH . 'helpers/Response.php';
require_once APP_PATH . 'helpers/links.php';
require_once APP_PATH . 'helpers/strings.php';
require_once APP_PATH . 'helpers/email.php';
require_once APP_PATH . 'helpers/notifications.php';
require_once APP_PATH . 'helpers/others.php';
require_once APP_PATH . 'helpers/Paginator.php';
require_once APP_PATH . 'helpers/Filters.php';
require_once APP_PATH . 'helpers/Alerts.php';
require_once APP_PATH . 'helpers/core.php';
require_once APP_PATH . 'helpers/Paypal.php';
require_once APP_PATH . 'helpers/Paystack.php';
require_once APP_PATH . 'helpers/Coinbase.php';
require_once APP_PATH . 'helpers/smoorf.php';
require_once APP_PATH . 'helpers/Uploads.php';

/* Autoload for vendor */
require_once ROOT_PATH . 'vendor/autoload.php';

