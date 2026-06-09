<?php
require_once 'config/Database.php';
require_once 'classes/Footprint.php';
require_once 'classes/User.php';
require_once 'classes/DailyLog.php';
require_once 'classes/Challenge.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

$userModel = new User($db);
$userData = $userModel->getById($_SESSION['user_id']);

$logModel = new DailyLog($db);
$stats = $logModel->getStats($_SESSION['user_id']);

$challengeModel = new Challenge($db);
$userChallenges = $challengeModel->getUserChallenges($_SESSION['user_id']);

// Prepare data for Chart.js (last 7 days of logs)
$logs_stmt = $logModel->getUserLogs($_SESSION['user_id'], 7);
$chartData = [];
while($row = $logs_stmt->fetch(PDO::FETCH_ASSOC)) {
    $chartData[] = [
        'date' => date('D', strtotime($row['logged_at'])),
        'value' => $row['co2_saved_kg']
    ];
}
$chartData = array_reverse($chartData);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="userDashboard()">
    
    <!-- Header Section -->
    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6" data-aos="fade-down">
        <div class="flex items-center">
            <div class="relative">
                <img src="<?php echo $userData['avatar_url'] ?: 'https://ui-avatars.com/api/?name='.urlencode($userData['name']).'&background=10b981&color=fff'; ?>" 
                     class="w-20 h-20 rounded-3xl object-cover border-4 border-white shadow-lg">
                <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold border-2 border-white text-xs">
                    Lvl <?php echo $userData['level']; ?>
                </div>
            </div>
            <div class="ml-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Eco Warrior, <?php echo htmlspecialchars($userData['name']); ?>!</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1"><?php echo $userData['bio'] ?: 'Saving the planet, one step at a time.'; ?></p>
            </div>
        </div>
        <div class="flex space-x-3">
            <button @click="openLogModal" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-xl"></i> Log Daily Action
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm" data-aos="zoom-in" data-aos-delay="100">
            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-4 text-emerald-600">
                <i class="fas fa-leaf text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase">Total CO2 Saved</p>
            <h3 class="text-3xl font-black mt-1 dark:text-white"><?php echo number_format($stats['total_saved'] ?? 0, 1); ?> <span class="text-lg font-normal">kg</span></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm" data-aos="zoom-in" data-aos-delay="200">
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-2xl flex items-center justify-center mb-4 text-yellow-600">
                <i class="fas fa-coins text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase">Eco Points</p>
            <h3 class="text-3xl font-black mt-1 dark:text-white"><?php echo number_format($userData['points']); ?></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm" data-aos="zoom-in" data-aos-delay="300">
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-4 text-blue-600">
                <i class="fas fa-check-double text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase">Actions Logged</p>
            <h3 class="text-3xl font-black mt-1 dark:text-white"><?php echo $stats['total_actions'] ?? 0; ?></h3>
        </div>
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm" data-aos="zoom-in" data-aos-delay="400">
            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-4 text-purple-600">
                <i class="fas fa-tree text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium uppercase">Trees Planted (Equiv)</p>
            <h3 class="text-3xl font-black mt-1 dark:text-white"><?php echo floor(($stats['total_saved'] ?? 0) / 20); ?></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Impact Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <h3 class="text-xl font-bold mb-8 dark:text-white flex items-center">
                <i class="fas fa-chart-line text-emerald-500 mr-3"></i> 7-Day Impact Trend
            </h3>
            <canvas id="impactChart" height="250"></canvas>
        </div>

        <!-- Active Challenges -->
        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <h3 class="text-xl font-bold mb-8 dark:text-white flex items-center">
                <i class="fas fa-trophy text-yellow-500 mr-3"></i> Your Challenges
            </h3>
            <div class="space-y-6">
                <?php while($challenge = $userChallenges->fetch(PDO::FETCH_ASSOC)): ?>
                    <div class="relative">
                        <div class="flex justify-between text-sm font-bold mb-2 dark:text-gray-300">
                            <span><?php echo $challenge['title']; ?></span>
                            <span><?php echo round(($challenge['progress_current'] / $challenge['target_value']) * 100); ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2.5">
                            <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-1000" style="width: <?php echo ($challenge['progress_current'] / $challenge['target_value']) * 100; ?>%"></div>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-widest font-bold">Goal: <?php echo $challenge['target_value']; ?>kg CO2</p>
                    </div>
                <?php endwhile; if($userChallenges->rowCount() == 0): ?>
                    <div class="text-center py-8">
                        <i class="fas fa-flag-checkered text-gray-200 text-4xl mb-4"></i>
                        <p class="text-gray-400 text-sm italic">No active challenges. Join one today!</p>
                        <a href="challenges.php" class="text-emerald-600 text-xs font-bold uppercase mt-4 inline-block hover:underline">Browse Challenges</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Daily Log Modal -->
    <template x-if="logModal">
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl" @click.away="logModal = false" data-aos="zoom-in">
                <div class="p-8 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-2xl font-bold dark:text-white">Log Eco-Action</h3>
                    <button @click="logModal = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-8 grid grid-cols-2 gap-4">
                    <button @click="logAction('recycle')" class="p-6 rounded-2xl border-2 border-gray-50 dark:border-gray-700 hover:border-emerald-500 transition group text-center">
                        <i class="fas fa-recycle text-3xl text-emerald-500 mb-3 group-hover:scale-110 transition"></i>
                        <p class="font-bold text-sm dark:text-white">Recycled</p>
                    </button>
                    <button @click="logAction('water')" class="p-6 rounded-2xl border-2 border-gray-50 dark:border-gray-700 hover:border-emerald-500 transition group text-center">
                        <i class="fas fa-tint text-3xl text-blue-500 mb-3 group-hover:scale-110 transition"></i>
                        <p class="font-bold text-sm dark:text-white">Saved Water</p>
                    </button>
                    <button @click="logAction('conserve')" class="p-6 rounded-2xl border-2 border-gray-50 dark:border-gray-700 hover:border-emerald-500 transition group text-center">
                        <i class="fas fa-lightbulb text-3xl text-yellow-500 mb-3 group-hover:scale-110 transition"></i>
                        <p class="font-bold text-sm dark:text-white">Energy Save</p>
                    </button>
                    <button @click="logAction('plant')" class="p-6 rounded-2xl border-2 border-gray-50 dark:border-gray-700 hover:border-emerald-500 transition group text-center">
                        <i class="fas fa-seedling text-3xl text-green-500 mb-3 group-hover:scale-110 transition"></i>
                        <p class="font-bold text-sm dark:text-white">Planted Tree</p>
                    </button>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
function userDashboard() {
    return {
        logModal: false,
        openLogModal() {
            this.logModal = true;
        },
        async logAction(type) {
            const formData = new FormData();
            formData.append('action', 'log_action');
            formData.append('action_type', type);

            const res = await fetch('api/user_actions.php', { method: 'POST', body: formData });
            const data = await res.json();
            
            if(data.success) {
                this.logModal = false;
                Swal.fire({
                    title: 'Awesome!',
                    text: `You saved ${data.co2_saved}kg of CO2 and earned ${data.points_earned} points!`,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => location.reload());
            }
        },
        init() {
            const ctx = document.getElementById('impactChart').getContext('2d');
            const data = <?php echo json_encode($chartData); ?>;
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: 'CO2 Saved (kg)',
                        data: data.map(d => d.value),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>
