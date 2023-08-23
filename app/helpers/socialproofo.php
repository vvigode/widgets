<?php


function array_flatten($array, $prefix = '') {
    $result = [];

    foreach($array as $key=>$value) {
        if(is_array($value)) {
            $result = $result + array_flatten($value, $prefix . $key . '.');
        }
        else {
            $result[$prefix.$key] = $value;
        }
    }

    return $result;
}
