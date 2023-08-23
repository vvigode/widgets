<?php


namespace Inpush;

class Uploads {
    public static $uploads = null;

    private static function initialize() {
        if(!self::$uploads) {
            self::$uploads = require APP_PATH . 'includes/uploads.php';
        }
    }

    public static function get_whitelisted_file_extensions($key) {
        self::initialize();
        return self::$uploads[$key]['whitelisted_file_extensions'];
    }

    public static function get_whitelisted_file_extensions_accept($key) {
        self::initialize();
        return implode(', ', array_map(function($value) { return '.' . $value; }, self::$uploads[$key]['whitelisted_file_extensions']));
    }
}
