<?php
if (isset($_POST['favorite'])) {
    $favorite = $_POST['favorite'];
    $cookie_name = "favorite_movies";

    // Check if the cookie already exists
    if (isset($_COOKIE[$cookie_name])) {
        $existing_movies = explode(",", $_COOKIE[$cookie_name]);
        if (in_array($favorite, $existing_movies)) {
            $existing_movies = array_diff($existing_movies, [$favorite]); // Remove the movie if already favorited
            $cookie_value = implode(",", $existing_movies);

            // Store the number of favorites in a JSON file
            $favorites_file = './assets/movie-favorites.json';
            $favorites_data = [];

            if (file_exists($favorites_file)) {
                $favorites_data = json_decode(file_get_contents($favorites_file), true);
            }

            if (isset($favorites_data[$favorite])) {
                $favorites_data[$favorite] -= 1; // Decrement the value
                if ($favorites_data[$favorite] <= 0) {
                    unset($favorites_data[$favorite]);
                }
            }

            file_put_contents($favorites_file, json_encode($favorites_data));
        } else {
            $existing_movies[] = $favorite;
            $cookie_value = implode(",", $existing_movies);

            // Store the number of favorites in a JSON file
            $favorites_file = './assets/movie-favorites.json';
            $favorites_data = [];

            if (file_exists($favorites_file)) {
                $favorites_data = json_decode(file_get_contents($favorites_file), true);
            }

            if (isset($favorites_data[$favorite])) {
                $favorites_data[$favorite] += 1; // Increment the value
            } else {
                $favorites_data[$favorite] = 1; // Add a new element
            }

            file_put_contents($favorites_file, json_encode($favorites_data));
        }
    } else {
        $cookie_value = $favorite;

        // Store the number of favorites in a JSON file
        $favorites_file = './assets/movie-favorites.json';
        $favorites_data = [];

        if (file_exists($favorites_file)) {
            $favorites_data = json_decode(file_get_contents($favorites_file), true);
        }

        if (isset($favorites_data[$favorite])) {
            $favorites_data[$favorite] += 1; // Increment the value
        } else {
            $favorites_data[$favorite] = 1; // Add a new element
        }

        file_put_contents($favorites_file, json_encode($favorites_data));
    }

    // Set the cookie with the updated value
    setcookie($cookie_name, $cookie_value, time() + (365 * 24 * 60 * 60), "/"); // 1 year
}

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

// Button logic
$button_text = 'Add to Favorites';
$cookie_name = "favorite_movies";
$favorite_count = 0;
if (isset($_COOKIE[$cookie_name])) {
    $existing_movies = explode(",", $_COOKIE[$cookie_name]);
    if (in_array($movie['id'], $existing_movies)) {
        $button_text = 'Remove from Favorites';

        // Handle Remove from Favorites
        if (isset($_POST['favorite'])) {
            $existing_movies = array_diff($existing_movies, [$_POST['favorite']]); // Remove the movie if already favorited
            $cookie_value = implode(",", $existing_movies);

            // Store the number of favorites in a JSON file
            $favorites_file = './assets/movie-favorites.json';
            $favorites_data = [];

            if (file_exists($favorites_file)) {
                $favorites_data = json_decode(file_get_contents($favorites_file), true);
            }

            if (isset($favorites_data[$_POST['favorite']])) {
                $favorites_data[$_POST['favorite']] -= 1; // Decrement the value
                if ($favorites_data[$_POST['favorite']] <= 0) {
                    unset($favorites_data[$_POST['favorite']]);
                }
            }

            file_put_contents($favorites_file, json_encode($favorites_data));
        }
    }
}

if (file_exists('./assets/movie-favorites.json')) {
    $favorites_data = json_decode(file_get_contents('./assets/movie-favorites.json'), true);
    if (isset($favorites_data[$movie['id']])) {
        $favorite_count = $favorites_data[$movie['id']];
    }
}
include_once 'includes/functions.php';

// Review form
$formSubmitted = false;
$movieId = isset($movie['id']) ? $movie['id'] : null;

$dbInfo = connectToDatabase();
$conn = $dbInfo['connection'];

// Check if the "reviews" table exists
$tableExists = $conn->query("SHOW TABLES LIKE 'reviews'")->num_rows > 0;

// If the "reviews" table doesn't exist, create it
if (!$tableExists) {
    $createTableQuery = "CREATE TABLE reviews (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            email VARCHAR(255) NOT NULL,
                            review TEXT NOT NULL,
                            movie_id INT NOT NULL,
                            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                        )";

    if ($conn->query($createTableQuery) === TRUE) {
        echo '<script>console.log("Table created successfully");</script>';
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Error creating table: ' . $conn->error . '
              </div>';
    }
}

// Check if the review form is submitted
if (isset($_POST['submitReview'])) {
    // Check if a review already exists for the same movie and email
    $existingReviewQuery = "SELECT id FROM reviews WHERE movie_id = ? AND email = ?";
    $stmtExistingReview = $conn->prepare($existingReviewQuery);

    if ($stmtExistingReview) {
        $stmtExistingReview->bind_param('is', $movieId, $_POST['email']);
        $stmtExistingReview->execute();
        $stmtExistingReview->store_result();

        if ($stmtExistingReview->num_rows > 0) {
            echo '<div class="alert alert-danger" role="alert">
                    It seems that you have already left a review for this movie. You cannot leave more than one review for the same movie.
                  </div>';
            $stmtExistingReview->close();
        } else {
            $stmtExistingReview->close();

            // Prepare and execute the SQL statement to insert the review
            $insertReviewQuery = "INSERT INTO reviews (name, email, review, movie_id, created_at) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
            $stmtInsertReview = $conn->prepare($insertReviewQuery);

            if ($stmtInsertReview) {
                $stmtInsertReview->bind_param('ssii', $_POST['name'], $_POST['email'], $_POST['review'], $movieId);

                if ($stmtInsertReview->execute()) {
                    echo '<div class="alert alert-success" role="alert">
                            Form submitted successfully! Thank you for your review.
                          </div>';
                    $formSubmitted = true;
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                            Error submitting form: ' . $stmtInsertReview->error . '
                          </div>';
                }

                $stmtInsertReview->close();
            } else {
                echo '<div class="alert alert-danger" role="alert">
                        Error preparing statement: ' . $conn->error . '
                      </div>';
            }
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">
                Error preparing statement: ' . $conn->error . '
              </div>';
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>mohammad nakshbandi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <!-- Your content goes here -->
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

                    <!-- Form for adding to favorites -->
                    <form action="" method="POST">
                        <input type="hidden" name="favorite" value="<?php echo $movie['id']; ?>">
                        <button type="submit" class="btn <?php echo $button_text === 'Remove from Favorites' ? 'btn-danger' : 'btn-primary'; ?>">
                            <?php echo $button_text; ?>
                        </button>
                        <span class="badge bg-secondary"><?php echo $favorite_count; ?> Favorites</span>
                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-danger" role="alert">
                Movie not found.
            </div>
            <a href="movies.php" class="btn btn-primary">Back to Movies Page</a>
        <?php } ?>

        <!-- Form for submitting a review -->
        <div class="row">
            <div class="col-md-12">
                <?php if (!$formSubmitted) { ?>
                    <h3>Submit a Review</h3>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Review message</label>
                            <textarea class="form-control" id="review" name="review" rows="5" required></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="agree" name="agree" required>
                            <label class="form-check-label" for="agree">I agree with the processing of personal data</label>
                        </div>
                        <button type="submit" class="btn btn-primary" name="submitReview">Submit Review</button>
                    </form>
                <?php } ?>

            </div>
        </div>
        <!-- Display existing reviews for the current movie -->

        <div class="row">
            <div class="col-md-12">
                <h3>Movie Reviews</h3>

                <?php
                global $conn;  // Add this line to make $conn a global variable

                // Fetch reviews for the current movie from the database
                $movieId = $movie['id'];  // Assuming $movie is defined earlier
                $reviewsQuery = "SELECT name, review FROM reviews WHERE movie_id = $movieId ORDER BY created_at DESC";
                $result = $conn->query($reviewsQuery);

                if ($result) :
                    if ($result->num_rows > 0) :
                        while ($row = $result->fetch_assoc()) :
                ?>
                            <div class="mb-3">
                                <strong>Author:</strong> <?= htmlspecialchars($row['name']) ?><br>
                                <strong>Review:</strong> <?= htmlspecialchars($row['review']) ?>
                            </div>
                <?php
                        endwhile;
                    else :
                        echo '<p>Be the first to leave a review for this movie!</p>';
                    endif;
                else :
                    echo '<div class="alert alert-danger" role="alert">
                    Error querying reviews: ' . $conn->error . '
                  </div>';
                endif;
                ?>
            </div>
        </div>
        <?php $conn->close(); ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/js/bootstrap.min.js">
    </script>

</body>

</html>