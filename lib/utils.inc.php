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
/**
 * Récupère le contenu d'une URL et le met en cache dans un fichier temporaire.
 *
 * @param string $url URL à récupérer.
 * @return string Contenu de l'URL.
 */
function get_content($url) {
    $hash = sha1($url);
    $filePath = '/tmp/local-get_content-' . $hash;

    // Vérifie si le fichier existe déjà dans /tmp
    if (!file_exists($filePath)) {
        $content = file_get_contents($url);
        file_put_contents($filePath, $content);
    } else {
        $content = file_get_contents($filePath);
    }

    return $content;
}
function erreur($code) {
    noCacheHeaders();
    http_response_code($code);
    die;
}