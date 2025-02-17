<?php
// Ini jika lo perlu buat fetch data dari server atau API

header('Content-Type: application/json');

// Database atau API untuk cuaca
$data = [
    'temperature' => 28,
    'humidity' => 75,
    'wind_speed' => 15,
    'soilMoisture' => 70
];

// Mengirim data dalam format JSON
echo json_encode($data);
?>
