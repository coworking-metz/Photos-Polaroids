<?php

include 'lib/main.inc.php';

$uid = $_GET['uid']??false;
$anonyme = $_GET['anonyme']??false;
$size = $_GET['size']??false;
if(!$uid) erreur(404);

if($size == 'micro') {
    $width = 15;
} else if($size == 'small') {
    $width = 150;
} else if($size == 'big') {
    $width = 1000;
} else {
    $width = 400;
}

$data = get_polaroid_data($uid);


outputImageWithHeaders($data['photo'], $width);


