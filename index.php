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
  ?>
 <?php
date_default_timezone_set('Europe/Bucharest');
$currentTime = date("H");
$greeting = "";

if ($currentTime >= 5 && $currentTime < 12) {
    $greeting = "Good Morning";
} elseif ($currentTime >= 12 && $currentTime < 18) {
    $greeting = "Good Afternoon";
} elseif ($currentTime >= 18 && $currentTime < 22) {
    $greeting = "Good Evening";
} else {
    $greeting = "Good Night";
}
?>

<div class="container">
  <!-- Content here -->
  <div class="alert alert-warning alert-dismissible fade show container-fluid" role="alert" style="width: 30%;">
    <strong><?php echo $greeting; ?>!!</strong>

    <a href="/movies.php" class="btn btn-primary btn-sm" style="margin-left: 8%;">View All Movies</a>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
</div>


<?php
  include './includes/footer.php';

?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>