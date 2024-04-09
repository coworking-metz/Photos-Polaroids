<?php


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