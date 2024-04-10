<?php

function cloudflareHit($urls=false) {
    if(!$urls) {
        $urls = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";    
    }

    
    if(!is_array($urls)) {
        $urls = [$urls];
    }

    $data = ['urls' => $urls];
    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];
    $context = stream_context_create($options);
    file_get_contents('https://cloudflare.coworking-metz.fr/hit', false, $context);
}

// function noCacheHeaders() {
//     header("Cache-Control: no-cache, must-revalidate, max-age=0");
//     header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
//     header('Pragma: no-cache'); // HTTP 1.0.    
// }

 function noCacheHeaders()
{
    header_remove('Pragma');
    header_remove('Expires');
    header_remove('Cache-Control');
    header('Cache-Control: no-store, max-age=30, s-maxage=0');
    header('Expires: 0');
}

 function cacheHeaders($max_age = null)
{
    // if (isset($_GET['nocache'])) return;
    if (is_null($max_age)) {
        // $max_age = 3600;
        $max_age = 3600 * 24;
    }

    header_remove('Pragma');
    header_remove('Expires');
    header_remove('Cache-Control');
    // Add cache-headers so that Cloudflare can cache the response.
    header('Cache-Control: public, max-age=60, s-maxage=' . $max_age . '');
}