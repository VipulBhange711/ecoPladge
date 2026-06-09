<?php
require_once '../config/Database.php';
require_once '../classes/User.php';
require_once '../classes/DailyLog.php';
require_once '../classes/Challenge.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'log_action':
        $log = new DailyLog($db);
        $log->user_id = $_SESSION['user_id'];
        $log->action_type = $_POST['action_type'];
        
        // Mock CO2 values based on action
        $co2_values = [
            'recycle' => 0.5,
            'water' => 0.2,
            'conserve' => 1.5,
            'plant' => 10.0
        ];
        $log->co2_saved_kg = $co2_values[$log->action_type] ?? 0;
        
        if ($log->create()) {
            // Add points to user
            $user = new User($db);
            $user->id = $_SESSION['user_id'];
            $points_earned = (int)($log->co2_saved_kg * 10);
            $user->addPoints($points_earned);
            
            // Update session points for real-time feel
            $_SESSION['user_points'] = ($_SESSION['user_points'] ?? 0) + $points_earned;
            
            echo json_encode([
                'success' => true, 
                'points_earned' => $points_earned, 
                'co2_saved' => $log->co2_saved_kg
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'update_profile':
        $user = new User($db);
        $user->id = $_SESSION['user_id'];
        $user->name = $_POST['name'];
        $user->bio = $_POST['bio'];
        $user->avatar_url = $_POST['avatar_url'];
        
        if ($user->updateProfile()) {
            $_SESSION['user_name'] = $user->name;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        break;

    case 'join_challenge':
        $challenge = new Challenge($db);
        $success = $challenge->join($_SESSION['user_id'], $_POST['challenge_id']);
        echo json_encode(['success' => $success]);
        break;

    case 'redeem_reward':
        $product_id = $_POST['product_id'];
        
        // 1. Get product info
        $stmt = $db->prepare("SELECT points_cost, name FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // 2. Check if user has enough points
        $stmt = $db->prepare("SELECT points FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_points = $stmt->fetchColumn();
        
        if ($user_points < $product['points_cost']) {
            echo json_encode(['success' => false, 'message' => 'Insufficient points']);
            exit();
        }
        
        // 3. Deduct points and create redemption record
        try {
            $db->beginTransaction();
            
            // Deduct points
            $stmt = $db->prepare("UPDATE users SET points = points - ? WHERE id = ?");
            $stmt->execute([$product['points_cost'], $_SESSION['user_id']]);
            
            // Create record
            $stmt = $db->prepare("INSERT INTO rewards_redemption (user_id, product_id, points_spent) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $product_id, $product['points_cost']]);
            
            $db->commit();
            
            // Update session
            $_SESSION['user_points'] -= $product['points_cost'];
            
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'message' => 'Redemption failed: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>
