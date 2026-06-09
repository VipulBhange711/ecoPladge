<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/Database.php';
require_once '../classes/Footprint.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Unauthorized. Please login."));
    exit();
}

$database = new Database();
$db = $database->getConnection();
$footprint = new Footprint($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->transport) &&
    !empty($data->energy) &&
    !empty($data->waste) &&
    !empty($data->total)
) {
    $footprint->user_id = $_SESSION['user_id'];
    $footprint->transport_kg = $data->transport;
    $footprint->energy_kg = $data->energy;
    $footprint->waste_kg = $data->waste;
    $footprint->total_kg = $data->total;

    if($footprint->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Footprint record created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create footprint record."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
