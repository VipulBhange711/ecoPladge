<?php
require_once 'config/Database.php';
require_once 'classes/Challenge.php';
include 'includes/header.php';

$database = new Database();
$db = $database->getConnection();

$challengeModel = new Challenge($db);
$challenges = $challengeModel->getAllActive();

// Get challenges the user has already joined
$joined_challenges = [];
if (isset($_SESSION['user_id'])) {
    $stmt = $db->prepare("SELECT challenge_id FROM user_challenges WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $joined_challenges = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<div class="bg-blue-600 py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <i class="fas fa-trophy text-9xl absolute -top-10 -left-10 rotate-12"></i>
        <i class="fas fa-medal text-9xl absolute -bottom-10 -right-10 -rotate-12"></i>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-5xl font-black text-white mb-6" data-aos="fade-up">Eco Challenges</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Join community challenges, hit sustainability goals, and earn massive bonus points.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="challengesPage()">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php while ($row = $challenges->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all group" data-aos="fade-up">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-trophy text-2xl"></i>
                    </div>
                    <span class="bg-emerald-500 text-white px-4 py-1.5 rounded-full text-xs font-black shadow-lg">
                        +<?php echo $row['points_reward']; ?> Pts
                    </span>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4"><?php echo htmlspecialchars($row['title']); ?></h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 line-clamp-3"><?php echo htmlspecialchars($row['description']); ?></p>
                
                <div class="space-y-4 mb-8">
                    <div class="flex items-center text-sm font-bold text-emerald-600">
                        <i class="fas fa-bullseye mr-3"></i>
                        Goal: <?php echo $row['target_value']; ?> kg CO2 Saved
                    </div>
                    <div class="flex items-center text-sm font-bold text-blue-600">
                        <i class="fas fa-clock mr-3"></i>
                        Ends: <?php echo $row['end_date'] ? date('M d, Y', strtotime($row['end_date'])) : 'Ongoing'; ?>
                    </div>
                </div>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (in_array($row['id'], $joined_challenges)): ?>
                        <button class="w-full py-4 bg-gray-100 dark:bg-gray-700 text-gray-500 font-bold rounded-2xl cursor-default flex items-center justify-center">
                            <i class="fas fa-check-circle mr-2"></i> Challenge Joined
                        </button>
                    <?php else: ?>
                        <button @click="joinChallenge(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>')" 
                                class="w-full py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition shadow-lg shadow-blue-100 flex items-center justify-center group-hover:scale-[1.02]">
                            Join Challenge
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="auth/login.php" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl text-center block">Login to Join</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<script>
function challengesPage() {
    return {
        async joinChallenge(id, title) {
            const result = await Swal.fire({
                title: 'Ready to start?',
                text: `Join the "${title}" challenge and start tracking your impact!`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Yes, let\'s go!'
            });

            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('action', 'join_challenge');
                formData.append('challenge_id', id);

                const res = await fetch('api/user_actions.php', { method: 'POST', body: formData });
                const data = await res.json();

                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'You have joined the challenge. Good luck!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Error', 'Failed to join challenge', 'error');
                }
            }
        }
    }
}
</script>

<?php include 'includes/footer.php'; ?>
