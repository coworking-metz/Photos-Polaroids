<?php

include 'lib/main.inc.php';

$uid = $_GET['uid']??false;
$size = $_GET['size']??false;
if(!$uid) erreur(404);


$data = get_polaroid_data($uid);


generer_polaroid($data, ['size'=>$size]);



purge_temp_files();