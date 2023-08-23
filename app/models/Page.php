<?php


namespace Inpush\Models;

class Page extends Model {

    public function get_pages($position) {

        $data = [];

        $cache_instance = \Inpush\Cache::$adapter->getItem('pages_' . $position);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            $result = database()->query("SELECT `url`, `title`, `type` FROM `pages` WHERE `position` = '{$position}' ORDER BY `order`");

            while($row = $result->fetch_object()) {

                if($row->type == 'internal') {

                    $row->target = '_self';
                    $row->url = url('page/' . $row->url);

                } else {

                    $row->target = '_blank';

                }

                $data[] = $row;
            }

            \Inpush\Cache::$adapter->save($cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS));

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

}
