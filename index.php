<?php
require_once 'config/Database.php';
require_once 'classes/Product.php';
require_once 'classes/Challenge.php';
include 'includes/header.php';

$database = new Database();
$db = $database->getConnection();

$productModel = new Product($db);
$featuredProducts = $productModel->getFeatured();

$challengeModel = new Challenge($db);
$latestChallenges = $challengeModel->getAllActive();
?>

<!-- Hero Section -->
<div class="relative min-h-screen flex items-center overflow-hidden bg-white dark:bg-gray-900">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-100/50 dark:bg-emerald-900/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-100/50 dark:bg-blue-900/20 rounded-full blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div data-aos="fade-right">
                <span class="inline-block px-4 py-2 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-black uppercase tracking-widest mb-6">
                    Join the Green Revolution
                </span>
                <h1 class="text-6xl lg:text-7xl font-black text-gray-900 dark:text-white leading-[1.1] mb-8">
                    Your Journey to <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-blue-600">Net Zero</span> Starts Here.
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 leading-relaxed max-w-xl">
                    EcoPlade is the all-in-one ecosystem for sustainable living. Track your impact, earn rewards, and join challenges to save our planet.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="auth/register.php" class="bg-emerald-600 text-white px-10 py-5 rounded-2xl font-black hover:bg-emerald-700 transition shadow-2xl shadow-emerald-200 dark:shadow-none hover:-translate-y-1">
                        Get Started Free
                    </a>
                    <a href="marketplace.php" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-10 py-5 rounded-2xl font-black border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition shadow-sm hover:-translate-y-1">
                        Explore Rewards
                    </a>
                </div>
                <div class="mt-12 flex items-center gap-6">
                    <div class="flex -space-x-4">
                        <img src="https://i.pravatar.cc/100?u=1" class="w-12 h-12 rounded-full border-4 border-white dark:border-gray-900">
                        <img src="https://i.pravatar.cc/100?u=2" class="w-12 h-12 rounded-full border-4 border-white dark:border-gray-900">
                        <img src="https://i.pravatar.cc/100?u=3" class="w-12 h-12 rounded-full border-4 border-white dark:border-gray-900">
                    </div>
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">
                        <span class="text-emerald-600 dark:text-emerald-400">10,000+</span> Eco Warriors Joined
                    </p>
                </div>
            </div>
            
            <div class="relative" data-aos="fade-left" data-aos-delay="200">
                <div class="relative z-10 bg-white/70 dark:bg-gray-800/70 backdrop-blur-2xl p-8 rounded-[40px] shadow-2xl border border-white/20">
                    <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80" 
                         class="rounded-[30px] shadow-lg mb-8 w-full h-80 object-cover">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-6 rounded-3xl">
                            <p class="text-emerald-600 dark:text-emerald-400 font-black text-2xl">2.5M+</p>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">CO2 Saved (kg)</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-3xl">
                            <p class="text-blue-600 dark:text-blue-400 font-black text-2xl">50k+</p>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mt-1">Trees Equivalent</p>
                        </div>
                    </div>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute -top-10 -right-10 w-32 h-32 bg-yellow-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
                <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
            </div>
        </div>
    </div>
</div>

<!-- Features Grid -->
<section class="py-32 bg-gray-50 dark:bg-gray-800/50 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20" data-aos="fade-up">
            <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-6">Complete Eco Ecosystem</h2>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Everything you need to master sustainable living in one platform.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/30 rounded-[30px] flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i class="fas fa-calculator text-3xl text-emerald-600"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">Smart Calculator</h3>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Advanced footprint tracking for transport, energy, and waste with real-time analytics.</p>
            </div>
            <div class="group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/30 rounded-[30px] flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i class="fas fa-gift text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">Reward Marketplace</h3>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Turn your positive impact into real rewards. Redeem points for premium eco-products.</p>
            </div>
            <div class="group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-yellow-100 dark:bg-yellow-900/30 rounded-[30px] flex items-center justify-center mb-8 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                    <i class="fas fa-users text-3xl text-yellow-600"></i>
                </div>
                <h3 class="text-2xl font-bold mb-4 dark:text-white">Community Challenges</h3>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Join teams and compete in global challenges to accelerate collective CO2 reduction.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Rewards Preview -->
<section class="py-32 bg-white dark:bg-gray-900 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-16">
            <div data-aos="fade-right">
                <h2 class="text-4xl font-black text-gray-900 dark:text-white mb-4">Featured Rewards</h2>
                <p class="text-gray-500 dark:text-gray-400">Top picks from our community</p>
            </div>
            <a href="marketplace.php" class="text-emerald-600 font-bold hover:underline" data-aos="fade-left">View All Products <i class="fas fa-arrow-right ml-2"></i></a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <?php while($row = $featuredProducts->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-[32px] overflow-hidden hover:shadow-2xl transition duration-500" data-aos="zoom-in">
                    <img src="<?php echo $row['image_url']; ?>" class="w-full h-48 object-cover">
                    <div class="p-6 text-center">
                        <h4 class="font-bold dark:text-white mb-2"><?php echo $row['name']; ?></h4>
                        <p class="text-emerald-600 font-black text-xl"><?php echo number_format($row['points_cost']); ?> <span class="text-xs uppercase">Pts</span></p>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-emerald-600 rounded-[60px] p-16 text-center text-white relative overflow-hidden shadow-2xl shadow-emerald-200 dark:shadow-none" data-aos="flip-up">
            <div class="absolute inset-0 opacity-10">
                <i class="fas fa-globe-americas text-[200px] absolute -top-20 -right-20"></i>
            </div>
            <h2 class="text-5xl font-black mb-8 relative z-10">Ready to Change the World?</h2>
            <p class="text-xl text-emerald-100 mb-10 max-w-2xl mx-auto relative z-10">
                Join thousands of others making a difference every single day.
            </p>
            <a href="auth/register.php" class="bg-white text-emerald-600 px-12 py-5 rounded-2xl font-black hover:bg-gray-50 transition relative z-10 inline-block shadow-xl">
                Create My Account
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
