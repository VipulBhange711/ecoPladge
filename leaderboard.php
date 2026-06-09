<?php
require_once 'config/Database.php';
require_once 'classes/User.php';
include 'includes/header.php';

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$leaderboard = $userModel->getLeaderboard(20);
?>

<div class="bg-gradient-to-br from-yellow-400 to-orange-500 py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <i class="fas fa-crown text-9xl absolute -top-10 -left-10 rotate-12"></i>
        <i class="fas fa-trophy text-9xl absolute -bottom-10 -right-10 -rotate-12"></i>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl font-black text-white mb-6" data-aos="fade-up">Eco Leaderboard</h1>
        <p class="text-xl text-yellow-100 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Meet the champions of sustainability. Every action counts towards a greener future.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 -mt-10 relative z-20">
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden" data-aos="fade-up">
        <div class="p-8 border-b border-gray-50 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50 flex justify-between items-center">
            <h3 class="text-xl font-bold dark:text-white">Top 20 Eco Warriors</h3>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Updated Real-time</span>
        </div>
        
        <div class="divide-y divide-gray-50 dark:divide-gray-700">
            <?php foreach ($leaderboard as $index => $user): ?>
                <div class="p-6 flex items-center hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors group">
                    <div class="w-12 text-center">
                        <?php if($index == 0): ?>
                            <span class="text-3xl">🥇</span>
                        <?php elseif($index == 1): ?>
                            <span class="text-3xl">🥈</span>
                        <?php elseif($index == 2): ?>
                            <span class="text-3xl">🥉</span>
                        <?php else: ?>
                            <span class="text-xl font-black text-gray-300 dark:text-gray-600">#<?php echo $index + 1; ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="ml-6 relative">
                        <img src="<?php echo $user['avatar_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&background=10b981&color=fff'; ?>" 
                             class="w-14 h-14 rounded-2xl object-cover shadow-sm group-hover:scale-110 transition duration-300">
                        <div class="absolute -bottom-1 -right-1 bg-emerald-500 text-white w-6 h-6 rounded-lg flex items-center justify-center font-bold text-[10px] border-2 border-white dark:border-gray-800">
                            <?php echo $user['level']; ?>
                        </div>
                    </div>
                    
                    <div class="ml-6 flex-grow">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($user['name']); ?></h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Eco Guardian Level</p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-2xl font-black text-emerald-600"><?php echo number_format($user['points']); ?></p>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Eco Points</p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
