<?php
// Check if the search parameter is set in the GET request
$searchValue = isset($_GET['search']) ? $_GET['search'] : '';
?>

<form class="d-flex" role="search" method="GET" action="search-results.php">
    <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search" value="<?php echo htmlspecialchars($searchValue); ?>" />
    <button class="btn btn-outline-success" type="submit">Search</button>
</form>
