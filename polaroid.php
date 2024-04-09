<?php

include 'lib/main.inc.php';

$uid = $_GET['uid']??false;
$anonyme = $_GET['anonyme']??false;
$classic = $_GET['classic']??false;
$size = $_GET['size']??false;
if(!$uid) erreur(404);


$data = get_polaroid_data($uid);


generer_polaroid($data, ['size'=>$size, 'anonyme'=>$anonyme, 'classic'=>$classic]);



purge_temp_files();