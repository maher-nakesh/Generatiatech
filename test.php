<?php

// Replace these values with your actual database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php-project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the JSON data from file
$jsonData = file_get_contents("./assets/movies-list-db.json");
$data = json_decode($jsonData, true);

// Insert genres into Genres table
foreach ($data['genres'] as $genre) {
    $sql = "INSERT INTO Genres (genre_name) VALUES ('$genre')";
    if ($conn->query($sql) !== true) {
        die("Error inserting genre: " . $conn->error);
    }
}

// Insert movies into Movies table
foreach ($data['movies'] as $movie) {
    $title = $conn->real_escape_string($movie['title']);
    $year = $conn->real_escape_string($movie['year']);
    $runtime = $conn->real_escape_string($movie['runtime']);
    $director = $conn->real_escape_string($movie['director']);
    $actors = $conn->real_escape_string($movie['actors']);
    $plot = $conn->real_escape_string($movie['plot']);
    $posterUrl = $conn->real_escape_string($movie['posterUrl']);

    $sql = "INSERT INTO Movies (title, year, runtime, director, actors, plot, posterUrl) 
            VALUES ('$title', '$year', '$runtime', '$director', '$actors', '$plot', '$posterUrl')";
    if ($conn->query($sql) !== true) {
        die("Error inserting movie: " . $conn->error);
    }

    $movieId = $conn->insert_id; // Get the auto-generated movie_id

    if (isset($movie['genres']) && is_array($movie['genres'])) {
        // Insert genres for the movie into MovieGenres table
        foreach ($movie['genres'] as $genre) {
            $genreIdQuery = "SELECT genre_id FROM Genres WHERE genre_name = '$genre'";
            $result = $conn->query($genreIdQuery);

            // Check if the query was successful before fetching data
            if ($result) {
                $row = $result->fetch_assoc();

                // Check if $row is not null and 'genre_id' is set before accessing
                if ($row !== null && isset($row['genre_id'])) {
                    $genreId = $row['genre_id'];
                    $movieGenresSql = "INSERT INTO MovieGenres (movie_id, genre_id) VALUES ('$movieId', '$genreId')";
                    if ($conn->query($movieGenresSql) !== true) {
                        die("Error inserting movie genre: " . $conn->error);
                    }
                }
            }
        }
    }
}

// Close connection
$conn->close();

echo "Data insertion successful!";

?>
