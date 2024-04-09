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

    $w = get_metal($ranking);
    return svgToPng(CHEMIN_MEDAILLES.$w.'.svg',['annee'=>$ranking]);
}