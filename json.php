<?php
include 'lib/main.inc.php';

$uid = $_GET['uid'] ?? false;

$payload = get_urls($uid);

header('Content-type: application/json');
echo json_encode($payload);