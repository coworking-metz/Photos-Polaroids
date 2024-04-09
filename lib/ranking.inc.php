<?php

function get_metal($annee) {
    
    $annee_courante = date("Y");
    $depuis = $annee_courante - $annee; 
    
    if ($annee_courante == $annee)
        return 'chocolat';
    if ($depuis > 4)
        return 'gold';
    else if ($depuis > 2)
        return 'silver';
    else
        return 'bronze';
    
}
function get_medaille($ranking) {

    if(!$ranking) return;
    $w = get_metal($ranking);

    $cle = 'medaille-'.$w.'-'.$ranking;

    $content = redis_get($cle);

    if($content) {
        return to_temp_file($content);
    } else {
        $png = remove_background(svgToPng(CHEMIN_MEDAILLES.$w.'.svg',['annee'=>$ranking]));
        redis_set($cle, file_get_contents($png));
        return $png;        
    }
}