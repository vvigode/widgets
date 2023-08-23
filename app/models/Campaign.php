<?php


namespace Inpush\Models;

use Inpush\Database\Database;

class Campaign extends Model {

    public function get_campaign_by_pixel_key($pixel_key) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Inpush\Cache::$adapter->getItem('campaign?pixel_key=' . $pixel_key);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('pixel_key', $pixel_key)->getOne('campaigns');

            if($data) {
                /* Save to cache */
                \Inpush\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(43200)->addTag('users')->addTag('user_id=' . $data->user_id)->addTag('campaign_id=' . $data->campaign_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

}
