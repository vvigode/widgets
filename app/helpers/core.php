<?php


function settings() {
    return \Inpush\Settings::$settings;
}

function db() {
    return \Inpush\Database\Database::$db;
}

function database() {
    return \Inpush\Database\Database::$database;
}

function language($language = null) {
    return \Inpush\Language::get($language);
}
