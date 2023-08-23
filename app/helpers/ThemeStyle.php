<?php


namespace Inpush;

use Inpush\Database\Database;

class ThemeStyle {
    public static $themes = [
        'light' => [
            'ltr' => 'bootstrap.min.css',
            'rtl' => 'bootstrap-rtl.min.css'
        ],
        'dark' => [
            'ltr' => 'bootstrap-dark.min.css',
            'rtl' => 'bootstrap-dark-rtl.min.css'
        ],
    ];
    public static $theme = 'light';

    public static function get() {
        if(isset($_COOKIE['theme_style']) && array_key_exists($_COOKIE['theme_style'], self::$themes)) {
            self::$theme = Database::clean_string($_COOKIE['theme_style']);
        }

        return self::$theme;
    }

    public static function get_file() {
        return self::$themes[self::get()][language()->direction];
    }

    public static function set_default($theme) {
        self::$theme = $theme;
    }

}
