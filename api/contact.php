<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/Database.php';
require_once '../classes/Contact.php';

$database = new Database();
$db = $database->getConnection();
$contact = new Contact($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->message)
) {
    $contact->name = $data->name;
    $contact->email = $data->email;
    $contact->message = $data->message;

    if($contact->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Message sent successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to send message."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data."));
}
?>
