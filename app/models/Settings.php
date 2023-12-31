<?php


namespace Inpush\Models;

class Settings extends Model {

    public function get() {

        $cache_instance = \Inpush\Cache::$adapter->getItem('settings');

        /* Set cache if not existing */
        if(!$cache_instance->get()) {

            $result = database()->query("SELECT * FROM `settings`");
            $data = new \StdClass();

            while($row = $result->fetch_object()) {

                /* Put the value in a variable so we can check if its json or not */
                $value = json_decode($row->value);

                $data->{$row->key} = is_null($value) ? $row->value : $value;

            }

            \Inpush\Cache::$adapter->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS));

        } else {

            /* Get cache */
            $data = $cache_instance->get('settings');

        }

        /* Define some stuff from the database */
        if(!defined('PRODUCT_VERSION')) define('PRODUCT_VERSION', $data->product_info->version);
        if(!defined('PRODUCT_CODE')) define('PRODUCT_CODE', $data->product_info->code);

        /* Set the full url for assets */
        define('ASSETS_FULL_URL', \Inpush\Plugin::is_active('offload') && isset($data->offload) && isset($data->offload->assets_url) && !empty($data->offload->assets_url) ? $data->offload->assets_url : SITE_URL . ASSETS_URL_PATH);
        define('UPLOADS_FULL_URL', \Inpush\Plugin::is_active('offload') && isset($data->offload) && isset($data->offload->uploads_url) && !empty($data->offload->uploads_url) ? $data->offload->uploads_url : SITE_URL . UPLOADS_URL_PATH);

        return $data;
    }

}
