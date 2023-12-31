<!DOCTYPE html>
<html lang="en">

<head>
  <title>mohammad nakshbandi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- Your content goes here -->
  <?php
  // Include the header 
  include './includes/header.php';
  ?>
  <div class="container">
    <?php
    // Define a multidimensional array to store movie details
    $movies = Movies();
    if (!empty($movies)) {
      $i = 0;
      echo '<div class="row">';
      // Check for the "genre" parameter in the link
      if (isset($_GET['genre'])) {
        $genre = $_GET['genre'];
        // Filter movies by genre
        $movies = array_filter($movies, function ($movie) use ($genre) {
          return in_array($genre, $movie['genres']);
        });
        // Modify the page title to include the genre name
        echo '<h1>' . $genre . ' Movies</h1>';
      } else {
        echo '<h1>All Movies</h1>';
      }

      // Loop through the movies array and generate HTML for each movie
      foreach ($movies as $movie) {
        $cleanedDescription = preg_replace('/[^\p{L}\p{N}\s]/u', '', $movie['plot']);
        echo '<div class="col-md-3" id="movie_' . $movie['id'] . '">';
        echo '<div class="card">';
        echo '<img src="' . $movie['posterUrl'] . '" class="card-img-top" alt="..." />';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $movie['title'] . '</h5>';
        echo '<p class="card-text">' . rtrim($cleanedDescription, '.') . ' ...</p>';
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
  </div>

  <?php
  // Include the footer
  include './includes/footer.php';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
