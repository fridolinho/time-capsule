<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once  '../../models/Items.php';

$database = new Database();
$db = $database->connect();

$item = new Item($db);

$result = $item->getProduct($_REQUEST['token']);

echo json_encode($result);


?>