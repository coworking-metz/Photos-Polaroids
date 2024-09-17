<?php
include 'lib/main.inc.php';

$uid = $_GET['uid'] ?? false;

$payload = get_urls($uid);

$data = get_polaroid_data($uid);
$payload['isLegacy'] = $data['legacy'] ?? false;

header('Content-type: application/json');
echo json_encode($payload);
