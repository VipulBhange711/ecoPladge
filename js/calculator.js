document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('calculatorForm');
    const results = document.getElementById('results');
    const saveBtn = document.getElementById('saveBtn');

    let lastCalculation = null;

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const transport = parseFloat(document.getElementById('transport').value) || 0;
        const energy = parseFloat(document.getElementById('energy').value) || 0;
        const waste = parseFloat(document.getElementById('waste').value) || 0;

        // Constants for calculation (Approximate kg CO2 per unit)
        const transportFactor = 0.411; // kg per mile
        const energyFactor = 0.45;    // kg per kWh
        const wasteFactor = 0.5;      // kg per kg waste

        const resTransport = (transport * transportFactor).toFixed(2);
        const resEnergy = (energy * energyFactor).toFixed(2);
        const resWaste = (waste * wasteFactor).toFixed(2);
        const resTotal = (parseFloat(resTransport) + parseFloat(resEnergy) + parseFloat(resWaste)).toFixed(2);

        lastCalculation = {
            transport: resTransport,
            energy: resEnergy,
            waste: resWaste,
            total: resTotal
        };

        // Update UI
        document.getElementById('resTransport').innerText = resTransport;
        document.getElementById('resEnergy').innerText = resEnergy;
        document.getElementById('resWaste').innerText = resWaste;
        document.getElementById('resTotal').innerText = resTotal;

        results.classList.remove('hidden');
        results.scrollIntoView({ behavior: 'smooth' });
    });

    if (saveBtn) {
        saveBtn.addEventListener('click', async () => {
            if (!lastCalculation) return;

            saveBtn.disabled = true;
            saveBtn.innerText = 'Saving...';

            try {
                const response = await fetch('api/footprint.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(lastCalculation)
                });

                const data = await response.json();

                if (response.ok) {
                    alert('Footprint saved successfully!');
                    saveBtn.innerText = 'Saved!';
                } else {
                    alert(data.message || 'Error saving footprint.');
                    saveBtn.disabled = false;
                    saveBtn.innerText = 'Save to Dashboard';
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                saveBtn.disabled = false;
                saveBtn.innerText = 'Save to Dashboard';
            }
        });
    }
});
