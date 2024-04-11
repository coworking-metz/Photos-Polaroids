<?php

include 'lib/main.inc.php';

$uid = $_GET['uid']??false;
$anonyme = $_GET['anonyme']??false;
$size = $_GET['size']??false;
if(!$uid) erreur(404);

$taille = get_taille($size);
$data = get_polaroid_data($uid);


outputImageWithHeaders($data['photo'], $taille['width'], $taille['quality']);


