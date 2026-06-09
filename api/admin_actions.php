<?php
require_once '../config/Database.php';
require_once '../classes/Product.php';
require_once '../classes/Challenge.php';
require_once '../classes/Category.php';
require_once '../classes/EcoTip.php';
require_once '../classes/User.php';

session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'save_product':
        $product = new Product($db);
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $product->id = $_POST['id'];
        }
        $product->name = $_POST['name'];
        $product->description = $_POST['description'];
        $product->price = $_POST['price'];
        $product->carbon_saved_kg = $_POST['carbon_saved_kg'];
        $product->image_url = $_POST['image_url'];
        $product->points_cost = $_POST['points_cost'];
        $product->stock_quantity = $_POST['stock_quantity'];
        $product->category_id = $_POST['category_id'];
        $product->is_featured = isset($_POST['is_featured']) ? 1 : 0;

        if (isset($product->id)) {
            $success = $product->update();
        } else {
            $success = $product->create();
        }
        echo json_encode(['success' => $success]);
        break;

    case 'delete_product':
        $product = new Product($db);
        $success = $product->delete($_POST['id']);
        echo json_encode(['success' => $success]);
        break;

    case 'save_challenge':
        $challenge = new Challenge($db);
        $challenge->title = $_POST['title'];
        $challenge->description = $_POST['description'];
        $challenge->target_value = $_POST['target_value'];
        $challenge->points_reward = $_POST['points_reward'];
        $challenge->start_date = $_POST['start_date'];
        $challenge->end_date = $_POST['end_date'];
        $success = $challenge->create();
        echo json_encode(['success' => $success]);
        break;

    case 'save_category':
        $category = new Category($db);
        $category->name = $_POST['name'];
        $category->icon = $_POST['icon'];
        $category->description = $_POST['description'];
        $success = $category->create();
        echo json_encode(['success' => $success]);
        break;

    case 'save_tip':
        $tip = new EcoTip($db);
        $tip->title = $_POST['title'];
        $tip->content = $_POST['content'];
        $tip->category = $_POST['category'];
        $tip->author_id = $_SESSION['user_id'];
        $success = $tip->create();
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
