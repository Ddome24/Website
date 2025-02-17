const firebaseConfig = {
    apiKey: "AIzaSyCOE7smFK1akjU58rWME1vW5cTE7XUWCOg",
    authDomain: "cobasensor-a57c6.firebaseapp.com",
    databaseURL: "https://cobasensor-a57c6-default-rtdb.firebaseio.com",
    projectId: "cobasensor-a57c6",
    storageBucket: "cobasensor-a57c6.firebasestorage.app",
    messagingSenderId: "885183705367",
    appId: "1:885183705367:web:55599bf8277c68734e4752",
    measurementId: "G-ES0V7E4H2C"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

$(document).ready(function() {
    var database = firebase.database();

    // Monitor changes in the 'Sensor' data
    database.ref('Data/Sensor').on('value', function(snapshot) {
        var sensorValue = snapshot.val();
        if (sensorValue) {
            // Update the sensor value in the web page
            $('#soil-moisture-value').text(sensorValue + '%'); // Update the value dynamically
            // Optionally, you can update the status based on the sensor value
            if (sensorValue > 60) {
                $('#soil-moisture-status').text('Cukup Baik');
            } else {
                $('#soil-moisture-status').text('Kurang');
            }
        } else {
            $('#soil-moisture-value').text('No data available');
            $('#soil-moisture-status').text('Data unavailable');
        }
    });
});
