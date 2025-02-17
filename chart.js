const rainfallChart = document.getElementById('rainfallChart').getContext('2d');
const soilMoistureChart = document.getElementById('soilMoistureChart').getContext('2d');

weatherRef.on('value', (snapshot) => {
    const data = snapshot.val();
    
    // Update Nilai Kadar Air Tanah
    const soilMoistureValue = data.soilMoisture;
    document.getElementById("soil-moisture-value").textContent = `${soilMoistureValue}%`;

    // Update Grafik Curah Hujan
    const rainfallData = data.rainfall;  // Asumsikan data curah hujan ada di Firebase

    // Buat grafik curah hujan
    new Chart(rainfallChart, {
        type: 'line',
        data: {
            labels: rainfallData.timestamps,  // Misalnya data timestamp
            datasets: [{
                label: 'Curah Hujan (mm)',
                data: rainfallData.values,  // Nilai curah hujan
                borderColor: 'blue',
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jam'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Curah Hujan (mm)'
                    }
                }
            }
        }
    });

    // Update Grafik Kadar Air Tanah
    const soilMoistureData = data.soilMoistureData;  // Asumsikan ada data kadar air

    // Buat grafik kadar air
    new Chart(soilMoistureChart, {
        type: 'bar',
        data: {
            labels: soilMoistureData.timestamps,  // Timestamps
            datasets: [{
                label: 'Kadar Air Tanah (%)',
                data: soilMoistureData.values,  // Nilai kadar air tanah
                backgroundColor: 'green'
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Jam'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Kadar Air Tanah (%)'
                    }
                }
            }
        }
    });
});
