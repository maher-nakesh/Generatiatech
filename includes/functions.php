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

?>