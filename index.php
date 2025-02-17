<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landside Monitoring System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome (untuk ikon cuaca) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Firebase Config -->
    <script src="/js/firebase-config.js"></script> <!-- Pastikan file ini ada di folder js/ -->
</head>
<body>

    <!-- Header -->
    <header class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <div class="logo fs-4"><i class="fa-solid fa-cloud-sun"></i> Landside Monitoring System</div>
        <button id="darkModeToggle" class="btn btn-dark">Ganti Mode Gelap</button>
    </header>

    <!-- Navigasi Tab -->
    <nav class="mt-3">
        <div class="nav nav-tabs" id="weatherTab" role="tablist">
            <a class="nav-link active" id="today-tab" data-bs-toggle="tab" href="#today" role="tab" aria-controls="today" aria-selected="true">Hari Ini</a>
            <a class="nav-link" id="hourly-tab" data-bs-toggle="tab" href="#hourly" role="tab" aria-controls="hourly" aria-selected="false">Per Jam</a>
            <a class="nav-link" id="10day-tab" data-bs-toggle="tab" href="#10day" role="tab" aria-controls="10day" aria-selected="false">10 Hari</a>
            <a class="nav-link" id="calendar-tab" data-bs-toggle="tab" href="#calendar" role="tab" aria-controls="calendar" aria-selected="false">Kalender</a>
        </div>
    </nav>

    <!-- Konten Tab -->
    <div class="tab-content" id="weatherTabContent">
        <!-- Tab Hari Ini -->
        <div class="tab-pane fade show active" id="today" role="tabpanel" aria-labelledby="today-tab">
            <div class="container mt-4">
                <div class="row">
                    <!-- Cuaca Saat Ini -->
                    <div class="col-md-6 col-12 text-center">
                        <h1>Surabaya, Indonesia</h1>
                        <h2 id="temperature">28°C</h2>
                        <i id="weather-icon" class="fa-solid fa-sun fa-3x"></i>
                        <p>Terasa Seperti: <span id="feels_like">30°C</span></p>
                        <p><i class="fa-solid fa-droplet"></i> Kelembapan: <span id="humidity">75%</span></p>
                        <p><i class="fa-solid fa-wind"></i> Kecepatan Angin: <span id="wind_speed">15 km/jam</span></p>
                    </div>

                    <!-- Kondisi Cuaca Lainnya -->
                    <div class="col-md-6 col-12">
                        <div class="row">
                            <!-- Curah Hujan -->
                            <div class="col-md-6 col-12">
                                <div class="card text-center" data-bs-toggle="modal" data-bs-target="#precipitationModal">
                                    <div class="card-body">
                                        <h5 class="card-title">Curah Hujan</h5>
                                        <p class="card-text" id="precipitation">0%</p>
                                        <small class="text-muted">Hujan berlanjut hingga pukul 1 pagi</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Suhu -->
                            <div class="col-md-6 col-12">
                                <div class="card text-center" data-bs-toggle="modal" data-bs-target="#temperatureModal">
                                    <div class="card-body">
                                        <h5 class="card-title">Suhu</h5>
                                        <p class="card-text" id="temperature-value">28°C</p>
                                        <small class="text-muted">Suhu saat ini</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Kadar Air -->
                            <div class="col-md-6 col-12">
                                <div class="card text-center" data-bs-toggle="modal" data-bs-target="#waterContentModal">
                                    <div class="card-body">
                                        <h5 class="card-title">Nilai Kadar Air</h5>
                                        <p class="card-text text-success" id="soil-moisture-status">Cukup Baik</p>
                                        <small class="text-muted">Nilai Kadar Air: <span id="soil-moisture-value">Loading...</span></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Kemiringan Tanah -->
                            <div class="col-md-6 col-12">
                                <div class="card text-center" data-bs-toggle="modal" data-bs-target="#soilSlopeModal">
                                    <div class="card-body">
                                        <h5 class="card-title">Kemiringan Tanah</h5>
                                        <p class="card-text">Sedang</p>
                                        <small class="text-muted">Indeks kestabilan tanah: 25</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Suhu -->
        <div class="modal fade" id="temperatureModal" tabindex="-1" aria-labelledby="temperatureModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="temperatureModalLabel">Suhu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Grafik Suhu:</h6>
                        <canvas id="temperatureChart"></canvas>
                        <p><strong>Suhu Saat Ini:</strong> 28°C</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Kemiringan Tanah -->
        <div class="modal fade" id="soilSlopeModal" tabindex="-1" aria-labelledby="soilSlopeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="soilSlopeModalLabel">Kemiringan Tanah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Grafik Kemiringan Tanah:</h6>
                        <canvas id="soilSlopeChart"></canvas>
                        <p><strong>Indeks Kestabilan Tanah:</strong> 25</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Curah Hujan -->
        <div class="modal fade" id="precipitationModal" tabindex="-1" aria-labelledby="precipitationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="precipitationModalLabel">Curah Hujan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Grafik Curah Hujan:</h6>
                        <canvas id="rainfallChart"></canvas>
                        <p><strong>Akumulasi Hujan:</strong> 0.11 in (Next 7 Hours)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Kadar Air Tanah -->
        <div class="modal fade" id="waterContentModal" tabindex="-1" aria-labelledby="waterContentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="waterContentModalLabel">Grafik Kadar Air Tanah</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6>Grafik Kadar Air Tanah:</h6>
                        <canvas id="soilMoistureChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- JavaScript & Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/script.js" defer></script>

    <!-- Firebase Fetch Data -->
    <script>
        // Mode Gelap
        const darkModeToggle = document.getElementById("darkModeToggle");
        const body = document.body;
        darkModeToggle.addEventListener("click", () => {
            body.classList.toggle("dark-mode");
            if (body.classList.contains("dark-mode")) {
                darkModeToggle.textContent = "Ganti Mode Terang";
            } else {
                darkModeToggle.textContent = "Ganti Mode Gelap";
            }
        });

        // Firebase Fetch Data
        const database = firebase.database();
        const sensorRef = database.ref('Data/Sensor'); // Path data sensor dari ESP32

        sensorRef.on('value', (snapshot) => {
            const sensorData = snapshot.val();
            
            // Tampilkan data pada halaman web
            const soilMoistureValue = sensorData;  // Misalnya sensor mengirim nilai kadar air
            document.getElementById("soil-moisture-value").textContent = `${soilMoistureValue}%`;

            // Tentukan status kadar air berdasarkan nilai
            const soilMoistureStatus = soilMoistureValue > 60 ? 'Cukup Baik' : 'Kurang';
            document.getElementById("soil-moisture-status").textContent = soilMoistureStatus;
        });
    </script>

</body>
</html>
