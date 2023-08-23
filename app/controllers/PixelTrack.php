<?php


namespace Inpush\Controllers;

use Inpush\Database\Database;
use Inpush\Models\Model;
use Inpush\Models\User;
use MaxMind\Db\Reader;
use Unirest\Request;

class PixelTrack extends Controller {

    public function index() {

        if(!isset($_SERVER['HTTP_REFERER'])) {
            die();
        }

        /* Check against bots */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if($CrawlerDetect->isCrawler()) {
            die();
        }

        /* Get the Payload of the Post */
        $payload = @file_get_contents('php://input');
        $post = json_decode($payload);

        if(!$post) {
            die();
        }

        /* Allowed types of requests to this endpoint */
        $allowed_types = ['track', 'notification', 'auto_capture', 'collector'];
        $date = \Inpush\Date::$date;
        $domain = Database::clean_string(parse_url(trim(Database::clean_string($_SERVER['HTTP_REFERER'])))['host']);
        $pixel_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : false;

        if(!isset($post->type) || isset($post->type) && !in_array($post->type, $allowed_types)) {
            die();
        }


        /* Clean all the received variables */
        foreach($post as $key => $value) {

            /* Whitelist */
            if(in_array($key, ['location'])) {
                continue;
            }

            $post->{$key} = Database::clean_string($value);
        }

        /* Get the details of the campaign from the database */
        $campaign = (new \Inpush\Models\Campaign())->get_campaign_by_pixel_key($pixel_key);

        /* Make sure the campaign has access */
        if(!$campaign) {
            die();
        }

        if(
            !$campaign->is_enabled
            || ($campaign->include_subdomains && !string_ends_with($campaign->domain, $domain))
            || (!$campaign->include_subdomains && $campaign->domain != $domain && $campaign->domain != 'www.' . $domain)
        ) {
            die();
        }

        /* Make sure to get the user data and confirm the user is ok */
        $user = (new \Inpush\Models\User())->get_user_by_user_id($campaign->user_id);

        if(!$user) {
            die();
        }

        if($user->status != 1) {
            die();
        }

        /* Process the plan of the user */
        (new User())->process_user_plan_expiration_by_user($user);

        /* Make sure that the user didnt exceed the current plan */
        if($user->plan_settings->notifications_impressions_limit != -1 && $user->current_month_notifications_impressions >= $user->plan_settings->notifications_impressions_limit) {
            die();
        }

        switch($post->type) {

            /* Tracking the notifications states, impressions, hovers..etc */
            case 'notification':

                $post->notification_id = (int) $post->notification_id;
                $post->subtype = in_array(
                    $post->subtype,
                    [
                        'hover',
                        'impression',
                        'click',
                        'feedback_emoji_angry',
                        'feedback_emoji_sad',
                        'feedback_emoji_neutral',
                        'feedback_emoji_happy',
                        'feedback_emoji_excited',
                        'feedback_score_1',
                        'feedback_score_2',
                        'feedback_score_3',
                        'feedback_score_4',
                        'feedback_score_5'
                    ]
                ) ? $post->subtype : false;

                /* Make sure the type of notification is the correct one */
                if(!$post->subtype) {
                    die();
                }

                /* Make sure the notification provided is a child of the campaign, exists and is enabled */
                if(!$notification = db()->where('notification_id', $post->notification_id)->where('campaign_id', $campaign->campaign_id)->where('is_enabled', 1)->getOne('notifications', ['campaign_id', 'notification_id'])) {
                    die();
                }

                /* Insert or update the log */
                db()->insert('track_notifications', [
                    'notification_id' => $notification->notification_id,
                    'campaign_id' => $notification->campaign_id,
                    'type' => $post->subtype,
                    'url' => $post->url,
                    'datetime' => $date,
                ]);

                /* Count it in the users account if it's an impression */
                if($post->subtype == 'impression') {
                    db()->where('user_id', $campaign->user_id)->update('users', [
                        'current_month_notifications_impressions' => db()->inc(),
                        'total_notifications_impressions' => db()->inc(),
                    ]);
                }

                break;

            /* Tracking the visits of the user */
            case 'track':

                /* Generate an id for the log */
                $ip = get_ip();
                $ip_binary = $ip ? inet_pton($ip) : null;

                /* Insert or update the log */
                db()->insert('track_logs', [
                    'user_id' => $campaign->user_id,
                    'domain' => $domain,
                    'url' => $post->url,
                    'ip_binary' => $ip_binary,
                    'datetime' => $date,
                ]);

                break;

            /* Getting the data from the email collector form */
            case 'collector':

                $post->notification_id = (int) $post->notification_id;

                /* Determine if we have email or input keys */
                $collector_key = false;

                if(isset($post->email) && !empty($post->email)) {
                    $collector_key = 'email';

                    /* Make sure that what we got is an actual email */
                    if(!filter_var($post->email, FILTER_VALIDATE_EMAIL)) {
                        die();
                    }
                }

                if(isset($post->input) && !empty($post->input)) {
                    $collector_key = 'input';
                }

                if(!$collector_key) {
                    die();
                }

                /* Make sure that the data is not already submitted and exists for this notification */
                $result = database()->query("SELECT `id` FROM `track_conversions` WHERE `notification_id` = {$post->notification_id} AND JSON_EXTRACT(`data`, '$.{$collector_key}') = '{$post->{$collector_key}}'");

                if($result->num_rows) {
                    die();
                }

                /* Detect the location */
                try {
                    $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
                } catch(\Exception $exception) {
                    /* :) */
                }
                $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
                $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

                $location_data = json_encode(
                    [
                        'city' => $city_name,
                        'country_code' => $country_code,
                        'country' => get_country_from_country_code($country_code)
                    ]
                );

                /* Data for the conversion */
                $data = json_encode([
                    $collector_key => $post->{$collector_key}
                ]);

                /* Insert the conversion log */
                db()->insert('track_conversions', [
                    'notification_id' => $post->notification_id,
                    'type' => $post->type,
                    'data' => $data,
                    'url' => $post->url,
                    'location' => $location_data,
                    'datetime' => $date,
                ]);

                /* Insert the log in the notification tracking table */
                /* Generate an id for the log */
                $type = 'form_submission';

                /* Insert or update the log */
                db()->insert('track_notifications', [
                    'notification_id' => $post->notification_id,
                    'campaign_id' => $campaign->campaign_id,
                    'type' => $type,
                    'url' => $post->url,
                    'datetime' => $date,
                ]);

                /* Make sure to send the webhook of the conversion */
                $notification = database()->query("SELECT `notifications`.`name`, `notifications`.`settings`, `campaigns`.`name` AS `campaign_name` FROM `notifications` LEFT JOIN `campaigns` ON `campaigns`.`campaign_id` = `notifications`.`campaign_id`  WHERE `notification_id` = {$post->notification_id}")->fetch_object();
                $notification->settings = json_decode($notification->settings);

                /* Only send if we need to */
                if($notification->settings->data_send_is_enabled) {

                    /* Webhook POST to the url the user specified */
                    if(!empty($notification->settings->data_send_webhook)) {

                        /* Send the webhook with the caught details */
                        $body = Request\Body::form([$collector_key => $post->{$collector_key}]);

                        $response = Request::post($notification->settings->data_send_webhook, [], $body);
                    }

                    /* Send email to the url the user specified */
                    if(!empty($notification->settings->data_send_email)) {

                        /* Prepare the html for the email body */
                        $email_body = '<ul>';
                        foreach(array_merge(json_decode($location_data, true), json_decode($data, true), ['ip' => get_ip(), 'url' => $post->url]) as $key => $value) {
                            $email_body .= '<li><strong>' . $key . ':</strong>' . ' ' . $value;
                        }
                        $email_body .= '</ul>';

                        $email_template = get_email_template(
                            [
                                '{{NOTIFICATION_NAME}}' => $notification->name,
                                '{{CAMPAIGN_NAME}}' => $notification->campaign_name,
                            ],
                            language($user->language)->global->emails->user_data_send->subject,
                            [
                                '{{NOTIFICATION_NAME}}' => $notification->name,
                                '{{CAMPAIGN_NAME}}' => $notification->campaign_name,
                                '{{DATA}}' => $email_body
                            ],
                            language($user->language)->global->emails->user_data_send->body
                        );

                        send_mail($notification->settings->data_send_email, $email_template->subject, $email_template->body);

                    }

                }

                break;

            /* Auto Capturing data from forms */
            case 'auto_capture':

                $post->notification_id = (int) $post->notification_id;

                /* Make sure to get only the needed data from the submission */
                $data = [];

                /* Save only parameters that start with "form_" */
                foreach($post as $key => $value) {
                    if(mb_strpos($key, 'form_') === 0) {
                        $data[str_replace('form_', '', $key)] = $value;
                    }
                }

                /* Data for the conversion */
                $data = json_encode($data);

                /* Detect the location */
                try {
                    $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
                } catch(\Exception $exception) {
                    /* :) */
                }
                $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
                $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

                $location_data = json_encode(
                    [
                        'city' => $city_name,
                        'country_code' => $country_code,
                        'country' => get_country_from_country_code($country_code)
                    ]
                );

                /* Insert the conversion log */
                db()->insert('track_conversions', [
                    'notification_id' => $post->notification_id,
                    'type' => $post->type,
                    'data' => $data,
                    'url' => $post->url,
                    'location' => $location_data,
                    'datetime' => $date,
                ]);

                /* Insert the log in the notification tracking table */
                /* Generate an id for the log */
                $type = 'auto_capture';

                /* Insert or update the log */
                db()->insert('track_notifications', [
                    'notification_id' => $post->notification_id,
                    'campaign_id' => $campaign->campaign_id,
                    'type' => $type,
                    'url' => $post->url,
                    'datetime' => $date,
                ]);

                break;
        }

    }

}
