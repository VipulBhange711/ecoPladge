<?php include 'includes/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        <!-- Contact Info -->
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-6">Get in Touch</h1>
            <p class="text-lg text-gray-600 mb-10">Have questions about our eco-friendly products or how to reduce your carbon footprint? Our team is here to help.</p>
            
            <div class="space-y-8">
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-emerald-100 p-3 rounded-lg text-emerald-600">
                        <i class="fas fa-envelope text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-gray-900">Email</h4>
                        <p class="text-gray-600">info@ecoplade.com</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-emerald-100 p-3 rounded-lg text-emerald-600">
                        <i class="fas fa-phone text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-gray-900">Phone</h4>
                        <p class="text-gray-600">+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-emerald-100 p-3 rounded-lg text-emerald-600">
                        <i class="fas fa-map-marker-alt text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-gray-900">Office</h4>
                        <p class="text-gray-600">123 Green Way, Eco City, EC 54321</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10 border border-gray-100">
            <form id="contactForm" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" required class="w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="John Doe">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" required class="w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="john@example.com">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                    <textarea id="message" required rows="5" class="w-full px-4 py-3 rounded-lg border-gray-200 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Your message here..."></textarea>
                </div>
                <button type="submit" id="submitBtn" class="w-full bg-emerald-600 text-white py-4 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">
                    Send Message
                </button>
            </form>
            <div id="formStatus" class="hidden mt-6 p-4 rounded-lg text-center"></div>
        </div>
    </div>
</div>

<script>
document.getElementById('contactForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const status = document.getElementById('formStatus');
    
    btn.disabled = true;
    btn.innerText = 'Sending...';

    const formData = {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        message: document.getElementById('message').value
    };

    try {
        const response = await fetch('api/contact.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        status.classList.remove('hidden', 'bg-red-100', 'text-red-700', 'bg-green-100', 'text-green-700');
        if (response.ok) {
            status.innerText = 'Thank you! Your message has been sent.';
            status.classList.add('bg-green-100', 'text-green-700');
            document.getElementById('contactForm').reset();
        } else {
            status.innerText = data.message || 'Error sending message.';
            status.classList.add('bg-red-100', 'text-red-700');
        }
    } catch (error) {
        status.innerText = 'An error occurred. Please try again later.';
        status.classList.add('bg-red-100', 'text-red-700');
    } finally {
        btn.disabled = false;
        btn.innerText = 'Send Message';
    }
});
</script>

<?php include 'includes/footer.php'; ?>
