<?php


if(isset($_GET['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}



define('CHEMIN_SITE',realpath(__DIR__.'/..').'/');
define('CHEMIN_FONTS',CHEMIN_SITE.'fonts/');
define('CHEMIN_MEDAILLES',CHEMIN_SITE.'images/medailles/');
include 'utils.inc.php';
include 'cache.inc.php';
include 'images.inc.php';
include 'ranking.inc.php';
include 'redis.inc.php';
include 'polaroid.inc.php';
