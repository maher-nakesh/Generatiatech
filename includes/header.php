<?php include_once 'functions.php' ?>
  <!-- Your content goes here -->
  <div class="container">
      <nav class="navbar navbar-expand-lg bg-body-tertiary">
          <?php
            $menuItems = [
                ['name' => 'Home', 'link' => 'index.php'],
                ['name' => 'Movie', 'link' => 'movies.php'],
                ['name' => 'Contact', 'link' => 'contacts.php',],
                ['name' => 'Categories ', 'link' => 'genres.php',],
            ];
            ?>
          <div class="container-fluid">
              <a class="navbar-brand" href="/index.php">MN</a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                      <?php
                        $currentFile = basename($_SERVER['REQUEST_URI']);
                        foreach ($menuItems as $item) {
                            echo '<li class="nav-item">';
                            echo  '<a class="nav-link " aria-current="page"  ';
                            if ($item['link'] === $currentFile) {
                                echo ' active';
                            }
                            echo 'href="' . $item['link'] . '">' . $item['name'] . '</a>';
                            echo '</li>';
                        }
                        ?>
                  </ul>
                  <?php include 'search-form.php' ?>
              </div>
          </div>
      </nav>
  </div>
  <?php
    function Movies()
    {
        $currentFile = basename($_SERVER['SCRIPT_NAME']);
        if ($currentFile !== 'index.php' && $currentFile !== 'contact.php') {
            $movies = json_decode(file_get_contents('./assets/movies-list-db.json'), true)['movies'];
            return $movies;
        }
    }
    ?>