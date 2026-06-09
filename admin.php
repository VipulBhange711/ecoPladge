<?php
require_once 'config/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Challenge.php';
require_once 'classes/Category.php';
require_once 'classes/EcoTip.php';
require_once 'classes/User.php';
include 'includes/header.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: dashboard.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$challengeModel = new Challenge($db);
$categoryModel = new Category($db);
$tipModel = new EcoTip($db);
$userModel = new User($db);

$active_tab = $_GET['tab'] ?? 'products';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="adminPanel()">
    <div class="mb-12 flex justify-between items-end" data-aos="fade-up">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Admin Control Center</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Scale the sustainability ecosystem of EcoPlade.</p>
        </div>
        <div class="flex space-x-3">
            <button @click="openModal('product')" class="bg-emerald-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                <i class="fas fa-plus mr-2"></i> Add Product
            </button>
            <button @click="openModal('challenge')" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                <i class="fas fa-trophy mr-2"></i> New Challenge
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Products</p>
            <h3 class="text-3xl font-bold mt-1 dark:text-white"><?php echo $productModel->getAll()->rowCount(); ?></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Active Challenges</p>
            <h3 class="text-3xl font-bold mt-1 dark:text-white"><?php echo $challengeModel->getAllActive()->rowCount(); ?></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Eco Tips</p>
            <h3 class="text-3xl font-bold mt-1 dark:text-white"><?php echo $tipModel->getAll()->rowCount(); ?></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <p class="text-gray-500 text-sm font-medium uppercase tracking-wider">Community Members</p>
            <h3 class="text-3xl font-bold mt-1 dark:text-white"><?php echo $db->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></h3>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex space-x-1 p-1 bg-gray-100 dark:bg-gray-800 rounded-xl mb-8 w-max">
        <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-white dark:bg-gray-700 shadow text-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all">Products</button>
        <button @click="tab = 'challenges'" :class="tab === 'challenges' ? 'bg-white dark:bg-gray-700 shadow text-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all">Challenges</button>
        <button @click="tab = 'categories'" :class="tab === 'categories' ? 'bg-white dark:bg-gray-700 shadow text-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all">Categories</button>
        <button @click="tab = 'tips'" :class="tab === 'tips' ? 'bg-white dark:bg-gray-700 shadow text-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all">Eco Tips</button>
        <button @click="tab = 'users'" :class="tab === 'users' ? 'bg-white dark:bg-gray-700 shadow text-emerald-600' : 'text-gray-500 hover:text-gray-700'" class="px-6 py-2.5 text-sm font-bold rounded-lg transition-all">Users</button>
    </div>

    <!-- Tab Content -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden min-h-[500px]">
        
        <!-- Products Tab -->
        <div x-show="tab === 'products'" class="p-8" x-cloak>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-400 text-xs uppercase tracking-widest border-b border-gray-50 dark:border-gray-700">
                            <th class="pb-6">Product Information</th>
                            <th class="pb-6">Category</th>
                            <th class="pb-6">Pricing & Points</th>
                            <th class="pb-6">Impact</th>
                            <th class="pb-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                        <?php $p_stmt = $productModel->getAll(); while($row = $p_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                <td class="py-6">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <img src="<?php echo $row['image_url']; ?>" class="w-14 h-14 rounded-2xl object-cover shadow-sm">
                                            <?php if($row['is_featured']): ?>
                                                <span class="absolute -top-2 -right-2 bg-yellow-400 text-white p-1 rounded-full text-[10px]">
                                                    <i class="fas fa-star"></i>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-bold text-gray-900 dark:text-white"><?php echo $row['name']; ?></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Stock: <?php echo $row['stock_quantity']; ?> units</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6">
                                    <span class="px-3 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold">
                                        <?php echo $row['category_name'] ?? 'Uncategorized'; ?>
                                    </span>
                                </td>
                                <td class="py-6">
                                    <p class="font-bold text-gray-900 dark:text-white">$<?php echo $row['price']; ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo $row['points_cost']; ?> pts</p>
                                </td>
                                <td class="py-6">
                                    <div class="flex items-center text-emerald-600 dark:text-emerald-400 font-bold">
                                        <i class="fas fa-leaf mr-2 text-xs"></i>
                                        <?php echo $row['carbon_saved_kg']; ?> kg
                                    </div>
                                </td>
                                <td class="py-6 text-right">
                                    <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button @click="editProduct(<?php echo htmlspecialchars(json_encode($row)); ?>)" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="deleteProduct(<?php echo $row['id']; ?>)" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Challenges Tab -->
        <div x-show="tab === 'challenges'" class="p-8" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php $c_stmt = $challengeModel->getAllActive(); while($row = $c_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="p-6 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-750 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-xl font-bold dark:text-white"><?php echo $row['title']; ?></h4>
                            <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full text-xs font-bold">
                                <?php echo $row['points_reward']; ?> Pts
                            </span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4"><?php echo $row['description']; ?></p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span><i class="far fa-calendar-alt mr-1"></i> Ends: <?php echo $row['end_date'] ?? 'No expiry'; ?></span>
                            <span class="font-bold text-emerald-600">Goal: <?php echo $row['target_value']; ?> kg CO2</span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Categories Tab -->
        <div x-show="tab === 'categories'" class="p-8" x-cloak>
             <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php $cat_stmt = $categoryModel->getAll(); while($row = $cat_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="p-6 rounded-2xl border border-gray-100 dark:border-gray-700 text-center hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition group">
                        <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                            <i class="fas <?php echo $row['icon']; ?> text-emerald-600"></i>
                        </div>
                        <h4 class="font-bold dark:text-white"><?php echo $row['name']; ?></h4>
                    </div>
                <?php endwhile; ?>
                <button @click="openModal('category')" class="p-6 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center hover:border-emerald-500 transition group">
                    <i class="fas fa-plus text-gray-300 group-hover:text-emerald-500 mb-2"></i>
                    <span class="text-gray-400 text-sm font-bold group-hover:text-emerald-500">Add Category</span>
                </button>
            </div>
        </div>

    </div>

    <!-- Modals -->
    <!-- Product Modal -->
    <template x-if="modals.product">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl" @click.away="closeModal('product')" data-aos="zoom-in">
                <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-2xl font-bold dark:text-white" x-text="editMode ? 'Edit Product' : 'Add New Product'"></h3>
                    <button @click="closeModal('product')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <form @submit.prevent="saveProduct" class="p-8 grid grid-cols-2 gap-6">
                    <input type="hidden" name="id" x-model="formData.product.id">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
                        <input type="text" x-model="formData.product.name" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea x-model="formData.product.description" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Price ($)</label>
                        <input type="number" step="0.01" x-model="formData.product.price" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Points Cost</label>
                        <input type="number" x-model="formData.product.points_cost" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Carbon Saved (kg)</label>
                        <input type="number" step="0.1" x-model="formData.product.carbon_saved_kg" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Stock Quantity</label>
                        <input type="number" x-model="formData.product.stock_quantity" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Image URL</label>
                        <input type="url" x-model="formData.product.image_url" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                        <select x-model="formData.product.category_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            <option value="">Select Category</option>
                            <?php $cat_list = $categoryModel->getAll(); while($c = $cat_list->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="flex items-center space-x-3 mt-8">
                        <input type="checkbox" x-model="formData.product.is_featured" class="w-5 h-5 text-emerald-600 rounded">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300">Featured Product</label>
                    </div>
                    <div class="col-span-2 flex justify-end space-x-4 mt-8">
                        <button type="button" @click="closeModal('product')" class="px-6 py-3 text-gray-500 font-bold hover:bg-gray-50 rounded-xl transition">Cancel</button>
                        <button type="submit" class="px-10 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script>
function adminPanel() {
    return {
        tab: 'products',
        modals: { product: false, challenge: false, category: false },
        editMode: false,
        formData: {
            product: { id: '', name: '', description: '', price: '', carbon_saved_kg: '', image_url: '', points_cost: 0, stock_quantity: 0, category_id: '', is_featured: false }
        },
        openModal(type) {
            this.modals[type] = true;
            this.editMode = false;
        },
        closeModal(type) {
            this.modals[type] = false;
            // Reset form
            this.formData.product = { id: '', name: '', description: '', price: '', carbon_saved_kg: '', image_url: '', points_cost: 0, stock_quantity: 0, category_id: '', is_featured: false };
        },
        editProduct(product) {
            this.editMode = true;
            this.formData.product = { ...product, is_featured: !!parseInt(product.is_featured) };
            this.modals.product = true;
        },
        async saveProduct() {
            const formData = new FormData();
            formData.append('action', 'save_product');
            Object.keys(this.formData.product).forEach(key => {
                formData.append(key, this.formData.product[key]);
            });

            const res = await fetch('api/admin_actions.php', { method: 'POST', body: formData });
            const data = await res.json();
            if(data.success) {
                Swal.fire('Success', 'Product saved successfully!', 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', 'Failed to save product', 'error');
            }
        },
        async deleteProduct(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'delete_product');
                formData.append('id', id);
                const res = await fetch('api/admin_actions.php', { method: 'POST', body: formData });
                const data = await res.json();
                if(data.success) {
                    Swal.fire('Deleted!', 'Product has been deleted.', 'success').then(() => location.reload());
                }
            }
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>
