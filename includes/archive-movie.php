<?php
function check_poster($url) {
    $headers = get_headers($url);
    if (strpos($headers[0], '200') !== false) {
        return $url;
    } else {
        return "https://via.placeholder.com/150";
    }
}

// Define a multidimensional array to store movie details
$movies = Movies();
if (!empty($movies)) {
    $i = 0;
    echo '<div class="row">';
    // Loop through the movies array and generate HTML for each movie
    foreach ($movies as $movie) {
        $cleanedDescription = preg_replace('/[^\p{L}\p{N}\s]/u', '', $movie['plot']);
        $trimmedDescription = strlen($cleanedDescription) > 100 ? substr($cleanedDescription, 0, 100) . '...' : $cleanedDescription;
        $posterUrl = check_poster($movie['posterUrl']);
        echo '<div class="col-md-3" id="movie_' . $movie['id'] . '">';
        echo '<div class="card">';
        echo '<img src="' . $posterUrl . '" class="card-img-top" alt="..." />';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $movie['title'] . '</h5>';
        echo '<p class="card-text">' . $trimmedDescription . '</p>';
        echo '<a href="movie.php?id=' . $movie['id'] . '" class="btn btn-primary">Read more</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        $i++;
        // Close the row div and start a new row after displaying 4 movies
        if ($i % 4 == 0) {
            echo '</div><div class="row">';
        }
    }
    echo '</div>'; // Close the last row div
}
?>
