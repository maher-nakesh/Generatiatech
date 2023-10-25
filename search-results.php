<?php
define('INITIALS', 'MN');
?>
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

  $searchPhrase = $_GET['search'] ?? '';

  if ($searchPhrase === '') {
    echo '<div class="container">You have reached this page without searching for something.</div>';
  } elseif (strlen($searchPhrase) < 3) {
    echo '<div class="container">Please enter at least 3 characters in the search field.</div>';
  } else {
    $movies = Movies();
    $filteredMovies = array_filter($movies, function ($movie) use ($searchPhrase) {
      return stripos($movie['title'], $searchPhrase) !== false;
    });

    if (count($filteredMovies) === 0) {
      echo '<div class="container">No movies found with the search phrase "' . $searchPhrase . '". Please try again with a different search term.</div>';
    } else {
      ?>
      <div class="container">
        <div class="row">
          <?php
          foreach ($filteredMovies as $movie) {
            $cleanedDescription = preg_replace('/[^\p{L}\p{N}\s]/u', '', $movie['plot']);
            ?>
            <div class="col-md-3" id="movie_<?php echo $movie['id']; ?>">
              <div class="card">
                <img src="<?php echo $movie['posterUrl']; ?>" class="card-img-top" alt="..." />
                <div class="card-body">
                  <h5 class="card-title"><?php echo $movie['title']; ?></h5>
                  <p class="card-text"><?php echo rtrim($cleanedDescription, '.') . ' ...'; ?></p>
                  <a href="movie.php?id=<?php echo $movie['id']; ?>" class="btn btn-primary">Read more</a>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
        </div>
        <?php include './includes/search-form.php'; ?>
      </div>
  <?php
    }
  }
  ?>

  <?php include './includes/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
