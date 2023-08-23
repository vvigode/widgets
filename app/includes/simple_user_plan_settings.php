<?php


$features = [
    'no_ads',
    'removable_branding',
    'custom_branding',
    'api_is_enabled',
];

if(\Inpush\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled) {
    $features[] = 'affiliate_is_enabled';
}

return $features;

