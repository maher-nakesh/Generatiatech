<?php 
function runtime_prettier($minutes) {
if ($minutes < 60) {
    return $minutes . " minute" . ($minutes != 1 ? "s" : "");
} else {
    $hours = floor($minutes / 60);
    $remainingMinutes = $minutes % 60;
    if ($remainingMinutes === 0) {
        return $hours . " hour" . ($hours != 1 ? "s" : "");
    } else {
        return $hours . " hour" . ($hours != 1 ? "s" : "") . " " . $remainingMinutes . " minute" . ($remainingMinutes != 1 ? "s" : "");
    }
}
}

function check_old_movie($releaseYear) {
    $currentYear = date("Y");
    $age = $currentYear - $releaseYear;
    if ($age > 40) {
        return $age;
    } else {
        return false;
    }
}


function connectToDatabase($host = 'localhost', $username = 'php-user', $password = 'php-password', $database = 'php-project') {
    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Return the connection object along with other information
    return [
        'connection' => $conn,
        'host' => $host,
        'username' => $username,
        'password' => $password,
        'database' => $database
    ];
}

// You can call the function without parameters for default values
connectToDatabase();








?>