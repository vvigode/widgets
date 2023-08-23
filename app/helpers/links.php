<?php


function url($append = '') {
    return SITE_URL . (\Inpush\Language::$default_language != \Inpush\Language::$language ? \Inpush\Language::$language_code . '/' : null)  . $append;
}

function redirect($append = '') {
    header('Location: ' . SITE_URL . $append);

    die();
}

function get_slug($string, $delimiter = '-', $lowercase = true) {

    mb_regex_encoding('UTF-8');
    mb_regex_set_options('ug');

    /* Replace all non words characters with the specified $delimiter */
    $string = mb_ereg_replace('[^a-zA-Z0-9.-\u{1f300}-\u{1f5ff}\u{1f900}-\u{1f9ff}\u{1f600}-\u{1f64f}\u{1f680}-\u{1f6ff}\u{2600}-\u{26ff}\u{2700}-\u{27bf}\u{1f1e6}-\u{1f1ff}\u{1f191}-\u{1f251}\u{1f004}\u{1f0cf}\u{1f170}-\u{1f171}\u{1f17e}-\u{1f17f}\u{1f18e}\u{3030}\u{2b50}\u{2b55}\u{2934}-\u{2935}\u{2b05}-\u{2b07}\u{2b1b}-\u{2b1c}\u{3297}\u{3299}\u{303d}\u{00a9}\u{00ae}\u{2122}\u{23f3}\u{24c2}\u{23e9}-\u{23ef}\u{25b6}\u{23f8}-\u{23fa}]+', $delimiter, $string);

    /* Check for double $delimiters and remove them so it only will be 1 delimiter */
    $string = mb_ereg_replace('' . $delimiter . '+', $delimiter, $string);

    /* Remove the $delimiter character from the start and the end of the string */
    $string = trim($string, $delimiter);

    /* Make sure to lowercase it */
    $string = $lowercase ? mb_strtolower($string) : $string;

    return $string;
}

function google_safe_browsing_check($url, $api_key = '') {
    $api_url = 'https://safebrowsing.googleapis.com/v4/threatMatches:find?key=' . $api_key;

    $body = Unirest\Request\Body::json([
        'client' => [
            'clientId' => '',
            'clientVersion' => '1.5.2'
        ],
        'threatInfo' => [
            'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING','THREAT_TYPE_UNSPECIFIED'],
            'platformTypes' => ['ANY_PLATFORM'],
            'threatEntryTypes' => ['URL'],
            'threatEntries' => [
                ['url' => $url]
            ]
        ]

    ]);

    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Token :)'
    ];

    $response = Unirest\Request::post($api_url, $headers, $body);

    if(isset($response->body->matches[0]->threatType) && $response->body->matches[0]->threatType) return true;

    return false;
}

function get_domain_from_url($url) {

    $host = parse_url($url, PHP_URL_HOST);

    $host = explode('.', $host);

    /* Return only the last 2 array values combined */
    return implode('.', array_slice($host, -2, 2));
}

function get_domain_from_email($email) {

    $host = explode('@', $email)[1];

    $host = explode('.', $host);

    /* Return only the last 2 array values combined */
    return mb_strtolower(implode('.', array_slice($host, -2, 2)));
}
