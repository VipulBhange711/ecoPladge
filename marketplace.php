<?php
require_once 'config/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Category.php';
include 'includes/header.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$categoryModel = new Category($db);

$products = $productModel->getAll();
$categories = $categoryModel->getAll();

$active_cat = $_GET['category'] ?? 'all';
?>

<div class="bg-emerald-600 py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <i class="fas fa-leaf text-9xl absolute -top-10 -left-10 rotate-12"></i>
        <i class="fas fa-recycle text-9xl absolute -bottom-10 -right-10 -rotate-12"></i>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl font-black text-white mb-6" data-aos="fade-up">Eco Reward Marketplace</h1>
        <p class="text-xl text-emerald-100 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Redeem your hard-earned Eco Points for sustainable products that help you live a greener life.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="marketplace()">
    
    <!-- Category Filter -->
    <div class="flex flex-wrap justify-center gap-4 mb-12" data-aos="fade-up">
        <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-8 py-3 rounded-2xl font-bold shadow-sm transition-all border border-gray-100">
            All Products
        </button>
        <?php while($cat = $categories->fetch(PDO::FETCH_ASSOC)): ?>
            <button @click="filter = '<?php echo $cat['id']; ?>'" :class="filter == '<?php echo $cat['id']; ?>' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'" class="px-8 py-3 rounded-2xl font-bold shadow-sm transition-all border border-gray-100 flex items-center">
                <i class="fas <?php echo $cat['icon']; ?> mr-2"></i>
                <?php echo $cat['name']; ?>
            </button>
        <?php endwhile; ?>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <?php while ($row = $products->fetch(PDO::FETCH_ASSOC)): ?>
            <div x-show="filter === 'all' || filter == '<?php echo $row['category_id']; ?>'" 
                 class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 group"
                 data-aos="fade-up">
                <div class="relative h-64 overflow-hidden">
                    <img src="<?php echo htmlspecialchars($row['image_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                        <div class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                            -<?php echo $row['carbon_saved_kg']; ?>kg CO2
                        </div>
                        <?php if($row['is_featured']): ?>
                            <div class="bg-yellow-400 text-white px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg">
                                <i class="fas fa-star mr-1"></i> Featured
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-emerald-600 transition-colors"><?php echo htmlspecialchars($row['name']); ?></h3>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 line-clamp-2"><?php echo htmlspecialchars($row['description']); ?></p>
                    
                    <div class="flex items-center justify-between mt-auto pt-6 border-t border-gray-50 dark:border-gray-700">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-black tracking-widest mb-1">Redeem for</p>
                            <p class="text-2xl font-black text-emerald-600"><?php echo number_format($row['points_cost']); ?> <span class="text-sm">Pts</span></p>
                        </div>
                        <button @click="redeemProduct(<?php echo $row['id']; ?>, '<?php echo addslashes($row['name']); ?>', <?php echo $row['points_cost']; ?>)" 
                                class="bg-gray-900 dark:bg-emerald-600 text-white p-4 rounded-2xl hover:bg-emerald-600 dark:hover:bg-emerald-700 transition shadow-lg disabled:opacity-50"
                                <?php echo (isset($_SESSION['user_points']) && $_SESSION['user_points'] < $row['points_cost']) ? 'disabled' : ''; ?>>
                            <i class="fas fa-shopping-basket text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function marketplace() {
    return {
        filter: 'all',
        async redeemProduct(id, name, cost) {
            const result = await Swal.fire({
                title: 'Confirm Redemption',
                text: `Redeem ${name} for ${cost} points?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'Yes, redeem it!'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'redeem_reward');
                formData.append('product_id', id);

                const res = await fetch('api/user_actions.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Redeemed!',
                        text: 'Your reward is on the way. Check your dashboard for status.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2500
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Redemption failed', 'error');
                }
            }
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>
