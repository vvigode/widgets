<?php


namespace Inpush;

class Notification {
    public static $notifications;
    public static $notifications_config = null;

    public static function get($notification_type, $notification = null, $user = null, $force_branding = null) {

        if(!self::$notifications_config) {
            self::$notifications_config = require APP_PATH . 'includes/notifications.php';
        }

        /* When no actual notification data is present, use the defaults */
        if(!$notification) {
            $notification = new \StdClass();
            $notification->notification_id = 0;
            $notification->settings = (object) self::$notifications_config[mb_strtoupper($notification_type)];
        }

        /* Determine the notification branding settings */
        if($user && !$user->plan_settings->removable_branding && !$notification->settings->display_branding) {
            $notification->settings->display_branding = true;
        }

        if($user && $user->plan_settings->removable_branding && !$notification->settings->display_branding) {
            $notification->settings->display_branding = false;
        }

        if(!is_null($force_branding)) {
            $notification->settings->display_branding = $force_branding;
        }

        /* Check if we can show the custom branding if available */
        if(isset($notification->branding, $notification->branding->name, $notification->branding->url) && !$user->plan_settings->custom_branding) {
            $notification->branding = false;
        }


        if(self::$notifications_config[mb_strtoupper($notification_type)]['type'] == 'default') {
            $data = require THEME_PATH . 'views/partials/notifications/' . mb_strtolower($notification_type) .'.php';
        } elseif(self::$notifications_config[mb_strtoupper($notification_type)]['type'] == 'pro') {
            $data = require \Inpush\Plugin::get('pro-notifications')->path . 'views/partials/notifications/' . mb_strtolower($notification_type) .'.php';
        }

        return $data;
    }

    public static function get_config($notification_type = false) {

        if(!self::$notifications_config) {
            self::$notifications_config = require APP_PATH . 'includes/notifications.php';
        }

        if(!$notification_type) {

            /* Return the whole configuration */
            return self::$notifications_config;

        } else {

            /* Return only specific notification configuration */
            return self::$notifications_config[mb_strtoupper($notification_type)] ?? false;

        }

    }
}
