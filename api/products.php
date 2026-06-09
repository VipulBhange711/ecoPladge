<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../config/Database.php';
require_once '../classes/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$stmt = $product->getAll();
$num = $stmt->rowCount();

if($num > 0) {
    $products_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $product_item = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "carbon_saved_kg" => $carbon_saved_kg,
            "image_url" => $image_url
        );
        array_push($products_arr, $product_item);
    }
    http_response_code(200);
    echo json_encode($products_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No products found."));
}
?>
