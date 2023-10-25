<?php
define('INITIALS', 'MN');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>mohammad nakshbandi<?php if (isset($_GET['genre'])) { echo ' - ' . $_GET['genre'] . ' Movies'; } ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="style.css" />
</head>

<body>
  <!-- Your content goes here -->
  <?php
  // Include the header
  include './includes/header.php';

  $genres = json_decode(file_get_contents('./assets/movies-list-db.json'), true)['genres'];
  ?>

  <div class="container">
    <!-- Content here -->
    <div class="alert alert-warning alert-dismissible fade show container-fluid" role="alert" style="width: 30%; hieght:50%;">
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          <?php
          if (isset($_GET['genre'])) {
            echo $_GET['genre'] . ' Movies';
          } else {
            echo 'Select Genre';
          }
          ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <?php
          if (!empty($genres)) {
            foreach ($genres as $genre) {
              echo '<li><a class="dropdown-item" href="genres.php?genre=' . urlencode($genre) . '">' . $genre . '</a></li>';
            }
          }
          ?>
        </ul>
      </div>
    </div>

    <?php
    if (isset($_GET['genre'])) {
      $selectedGenre = urldecode($_GET['genre']);
      $movies = array_filter(Movies(), function ($movie) use ($selectedGenre) {
        return in_array($selectedGenre, $movie['genres']);
      });

      if (!empty($movies)) {
        $i = 0;
        echo '<div class="row">';
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
          if ($i % 4 == 0) {
            echo '</div><div class="row">';
          }
        }
        echo '</div>'; // Close the last row div
      } else {
        echo '<p>No movies found in the selected genre.</p>';
      }
    }
    ?>

  </div>

  <?php
  include './includes/footer.php';
  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
