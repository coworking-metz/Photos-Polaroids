<?php
function url_to_file($url){
    if(!$url) return;
    $content = get_content($url);
    if(!$content) return;

    return to_temp_file($content);
}

$GLOBALS['to_temp_file']=[];
function to_temp_file($data){
    $tmpfile = tempnam(sys_get_temp_dir(), 'tmp');
    file_put_contents($tmpfile, $data);
    $GLOBALS['to_temp_file'][]=$tmpfile;
    return $tmpfile;
}

function purge_temp_files() {
    foreach($GLOBALS['to_temp_file'] as $file) {
        unlink($file);
    }
}
function get_content($url) {
    $hash = sha1($url);

    $content = redis_get('local-'.$hash);
    if(!$content) {
        $content = file_get_contents($url);
        redis_set('local-'.$hash, $content);
    }
    return $content;
}
function erreur($code) {
    noCacheHeaders();
    http_response_code($code);
    die;
}