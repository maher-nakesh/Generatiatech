<!DOCTYPE html>
<html lang="en">

<head>
  <title>mohammad nakshbandi</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">

</head>

<body>
  <!-- Your content goes here -->
  <?php
  // Include the header
  include './includes/header.php';
  // Filter movies by ID
  $movieId = isset($_GET['id']) ? $_GET['id'] : null; // Assuming the ID is set in the GET parameter
  $movies = array_filter(Movies(), function ($movie) use ($movieId) {
    return $movie['id'] === (int)$movieId; // Cast to integer for comparison
  });

  if (!empty($movies)) {
    $movie = reset($movies); // Get the first element of the filtered array
  }
  ?>
  <div class="container">
    <!-- Content here -->
    <?php if (!empty($movie)) { ?>
      <h1>Movie Details: <?php echo $movie['title']; ?></h1>
      <div class="row">
        <!-- First Column: Movie Poster -->
        <div class="col-md-4">
          <img src="<?php echo $movie['posterUrl']; ?>" class="card-img-top" alt="Movie Poster" />
        </div>
        <!-- Second Column: Movie Information -->
        <div class="col-md-8 movie-info">
          <h2><?php echo $movie['title']; ?></h2>
          <p><strong>Year of Release:</strong> <?php echo $movie['year']; ?></p>
          <?php
          $age = check_old_movie($movie['year']);

          if ($age) {
            echo '<p><span class="badge bg-warning text-dark">Old movie: ' . $age . ' years</span></p>';
          }
          ?>
          <p><strong>Description:</strong> <?php echo $movie['plot']; ?></p>
          <p><strong>Length:</strong> <?php echo runtime_prettier($movie['runtime']); ?></p>
          <p><strong>Director:</strong> <?php echo $movie['director']; ?></p>
          <p><strong>Category:</strong> <?php echo implode(', ', $movie['genres']); ?></p>
          <p><strong>Main Actors:</strong> <?php echo $movie['actors']; ?></p>
        </div>
      </div>
    <?php } else { ?>
      <div class="alert alert-danger" role="alert">
        Movie not found.
      </div>
      <a href="movies.php" class="btn btn-primary">Back to Movies Page</a>
    <?php } ?>

  </div>
  <?php
  include './includes/footer.php';

  ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>

</body>

</html>
