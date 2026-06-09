<?php 
require_once 'config/Database.php';
require_once 'classes/Product.php';
include 'includes/header.php'; 

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);
$stmt = $product->getAll();
?>

<div class="bg-emerald-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Eco-Friendly Products</h1>
        <p class="text-lg text-gray-600">Switch to sustainable alternatives and save carbon today.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="relative h-64">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-full h-full object-cover">
                    <div class="absolute top-4 right-4 bg-emerald-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                        -<?php echo htmlspecialchars($row['carbon_saved_kg']); ?>kg CO2
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-2xl font-bold text-emerald-600">$<?php echo htmlspecialchars($row['price']); ?></span>
                        <button class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition">View Details</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
