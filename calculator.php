<?php include 'includes/header.php'; ?>

<div class="bg-emerald-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4">Carbon Footprint Calculator</h1>
        <p class="text-emerald-100 max-w-2xl mx-auto">Measure your monthly environmental impact and find ways to improve.</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 mb-20">
    <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
        <form id="calculatorForm" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Transport -->
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-gray-700 font-bold flex items-center mb-2">
                            <i class="fas fa-car text-emerald-500 mr-2"></i> Monthly Driving (miles)
                        </span>
                        <input type="number" id="transport" class="block w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g. 500">
                    </label>
                </div>
                
                <!-- Energy -->
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-gray-700 font-bold flex items-center mb-2">
                            <i class="fas fa-bolt text-emerald-500 mr-2"></i> Electricity (kWh/month)
                        </span>
                        <input type="number" id="energy" class="block w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g. 300">
                    </label>
                </div>

                <!-- Waste -->
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-gray-700 font-bold flex items-center mb-2">
                            <i class="fas fa-trash text-emerald-500 mr-2"></i> Monthly Waste (kg)
                        </span>
                        <input type="number" id="waste" class="block w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="e.g. 20">
                    </label>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100">
                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                    Calculate Impact
                </button>
            </div>
        </form>

        <!-- Results (Hidden by default) -->
        <div id="results" class="hidden mt-12 pt-12 border-t-2 border-dashed border-gray-100 text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Your Monthly Impact</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500 mb-1">Transport</p>
                    <p class="text-2xl font-bold text-emerald-600"><span id="resTransport">0</span>kg</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500 mb-1">Energy</p>
                    <p class="text-2xl font-bold text-emerald-600"><span id="resEnergy">0</span>kg</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl">
                    <p class="text-sm text-gray-500 mb-1">Waste</p>
                    <p class="text-2xl font-bold text-emerald-600"><span id="resWaste">0</span>kg</p>
                </div>
            </div>
            <div class="bg-emerald-600 text-white p-8 rounded-2xl mb-8">
                <p class="text-emerald-100 text-lg mb-2">Total Carbon Footprint</p>
                <p class="text-5xl font-extrabold"><span id="resTotal">0</span> <span class="text-xl">kg CO2e</span></p>
            </div>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <button id="saveBtn" class="bg-emerald-100 text-emerald-700 px-8 py-3 rounded-lg font-bold hover:bg-emerald-200 transition">
                    Save to Dashboard
                </button>
            <?php else: ?>
                <p class="text-gray-500 italic"><a href="auth/login.php" class="text-emerald-600 underline font-semibold">Login</a> to save your footprint history.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="js/calculator.js"></script>
<?php include 'includes/footer.php'; ?>
