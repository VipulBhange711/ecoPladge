<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoPlade - Sustainable Ecosystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        [x-cloak] { display: none !important; }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark bg-gray-900': darkMode }">
    <nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="index.php" class="flex-shrink-0 flex items-center group">
                        <div class="bg-emerald-100 p-2 rounded-lg group-hover:bg-emerald-200 transition-colors mr-2">
                            <i class="fas fa-leaf text-emerald-600 text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">EcoPlade</span>
                    </a>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-8">
                        <a href="index.php" class="text-gray-900 dark:text-gray-100 inline-flex items-center px-1 pt-1 border-b-2 border-emerald-500 text-sm font-medium">Home</a>
                        <a href="products.php" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Products</a>
                        <a href="calculator.php" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Calculator</a>
                        <a href="contact.php" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium">Contact</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="ml-3 relative flex items-center space-x-4">
                            <div class="flex flex-col items-end">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                <span class="text-xs text-emerald-600 font-medium"><?php echo $_SESSION['user_points'] ?? 0; ?> Points</span>
                            </div>
                            <a href="dashboard.php" class="text-gray-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 text-sm font-medium">Dashboard</a>
                            <?php if($_SESSION['user_role'] == 'admin'): ?>
                                <a href="admin.php" class="text-emerald-600 dark:text-emerald-400 hover:underline text-sm font-medium">Admin</a>
                            <?php endif; ?>
                            <a href="auth/logout.php" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm hover:shadow-md transition-all">Logout</a>
                        </div>
                    <?php else: ?>
                        <div class="space-x-4">
                            <a href="auth/login.php" class="text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 text-sm font-medium transition-colors">Login</a>
                            <a href="auth/register.php" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 shadow-sm hover:shadow-md transition-all">Register</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <script>AOS.init({ duration: 800, once: true });</script>
    <main class="flex-grow">
