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
    if (!$ranking) return;

    $w = get_metal($ranking);
    $cle = 'medaille-' . $ranking;
    $filePath = '/tmp/' . $cle;

    // Vérifie si le fichier de cache existe déjà
    if (file_exists($filePath)) {
        return to_temp_file(file_get_contents($filePath));
    } else {
        $png = remove_background(svgToPng(CHEMIN_MEDAILLES . $w . '.svg', ['annee' => $ranking]));
        file_put_contents($filePath, file_get_contents($png));
        return $png;
    }
}
