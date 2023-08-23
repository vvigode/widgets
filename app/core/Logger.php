<?php


namespace Inpush;

use MaxMind\Db\Reader;

class Logger {

    public static function users($user_id, $type) {

        $ip = get_ip();

        /* Detect the location */
        try {
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get($ip);
        } catch(\Exception $exception) {
            /* :) */
        }
        $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        /* Detect extra details about the user */
        $os_name = $whichbrowser->os->name ?? null;

        db()->insert('users_logs', [
            'user_id'       => $user_id,
            'type'          => $type,
            'ip'            => $ip,
            'device_type'   => $device_type,
            'os_name'       => $os_name,
            'country_code'  => $country_code,
            'datetime'      => \Inpush\Date::$date,
        ]);

    }

}
